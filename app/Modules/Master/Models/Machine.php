<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends MillModel
{
    protected $table = 'machines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'code',
        'name',
        'section',
        'make',
        'model',
        'capacity',
        'install_date',
        'spindle_count',
        'location',
        'status',
        'running_hours',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'decimal:4',
            'install_date' => 'date',
            'running_hours' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Branch::class, 'branch_id');
    }

    public function spinningProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\SpinningProduction::class, 'machine_id');
    }

    public function weavingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeavingProduction::class, 'machine_id');
    }

    public function knittingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Knitting\Models\KnittingProduction::class, 'machine_id');
    }

    public function dyeBatchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatch::class, 'machine_id');
    }

    public function finishingRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\FinishingRun::class, 'machine_id');
    }

    public function maintenancePlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenancePlan::class, 'machine_id');
    }

    public function maintenanceWorkOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenanceWorkOrder::class, 'machine_id');
    }

    public function breakdownLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\BreakdownLog::class, 'machine_id');
    }

    public function conditionReadings(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\ConditionReading::class, 'machine_id');
    }

}
