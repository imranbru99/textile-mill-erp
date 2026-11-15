<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\StaleModelException;
use App\Modules\Foundation\Models\AuditLog;
use App\Support\Context\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class MillModel extends Model
{
    use SoftDeletes;

    protected bool $companyScoped = true;

    public static bool $auditing = true;

    public function isCompanyScoped(): bool
    {
        return $this->companyScoped;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('company', function (Builder $builder): void {
            /** @var MillModel $model */
            $model = $builder->getModel();
            if (! $model->isCompanyScoped()) {
                return;
            }

            $companyId = TenantContext::companyId();
            if ($companyId) {
                $builder->where($model->getTable().'.company_id', $companyId);
            }

            $branchId = TenantContext::branchId();
            if ($branchId && Schema::hasColumn($model->getTable(), 'branch_id')) {
                $builder->where(function (Builder $query) use ($model, $branchId): void {
                    $query->where($model->getTable().'.branch_id', $branchId)
                        ->orWhereNull($model->getTable().'.branch_id');
                });
            }
        });

        static::creating(function (MillModel $model): void {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::ulid();
            }

            if ($model->isCompanyScoped() && empty($model->company_id) && TenantContext::companyId()) {
                $model->company_id = TenantContext::companyId();
            }

            if (auth()->id() && empty($model->created_by)) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function (MillModel $model): void {
            if (auth()->id()) {
                $model->updated_by = auth()->id();
            }
        });

        static::saved(function (MillModel $model): void {
            $model->writeAudit($model->wasRecentlyCreated ? 'created' : 'updated');
        });

        static::deleted(function (MillModel $model): void {
            $model->writeAudit('deleted');
        });
    }

    protected function performUpdate(Builder $query): bool
    {
        $original = $this->getOriginal('lock_version');
        if ($original !== null && ! $this->isDirty('lock_version')) {
            $this->lock_version = ((int) $original) + 1;
            $query->where($this->getTable().'.lock_version', $original);
        }

        $updated = parent::performUpdate($query);
        if ($original !== null && $updated === false) {
            throw new StaleModelException($this);
        }

        return $updated;
    }

    protected function writeAudit(string $event): void
    {
        if (! self::$auditing || $this instanceof AuditLog || ! Schema::hasTable('audit_logs')) {
            return;
        }

        $companyId = $this->getAttribute('company_id') ?? TenantContext::companyId();
        if (! $companyId && $this->getTable() === 'companies') {
            $companyId = $this->getKey();
        }
        if (! $companyId) {
            return;
        }

        $dirty = $this->getChanges();
        unset($dirty['updated_at'], $dirty['lock_version']);
        if ($event === 'updated' && $dirty === []) {
            return;
        }

        AuditLog::withoutEvents(function () use ($event, $dirty, $companyId): void {
            AuditLog::query()->create([
                'public_id' => (string) Str::ulid(),
                'company_id' => $companyId,
                'user_id' => auth()->id(),
                'auditable_type' => $this::class,
                'auditable_id' => $this->getKey(),
                'event' => $event,
                'old_values' => $event === 'created' ? null : array_intersect_key($this->getOriginal(), $dirty),
                'new_values' => $event === 'deleted' ? null : ($event === 'created' ? $this->attributesToArray() : $dirty),
                'ip' => request()?->ip(),
                'user_agent' => substr((string) request()?->userAgent(), 0, 255),
            ]);
        });
    }
}
