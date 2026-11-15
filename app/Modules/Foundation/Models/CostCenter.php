<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends MillModel
{
    protected $table = 'cost_centers';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [

        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Department::class, 'cost_center_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalLine::class, 'cost_center_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Budget::class, 'cost_center_id');
    }

}
