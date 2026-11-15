<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends MillModel
{
    protected $table = 'fiscal_years';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'name',
        'starts_on',
        'ends_on',
        'is_closed',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_closed' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function fiscalPeriods(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\FiscalPeriod::class, 'fiscal_year_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Budget::class, 'fiscal_year_id');
    }

}
