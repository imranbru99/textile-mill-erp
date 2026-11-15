<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalPeriod extends MillModel
{
    protected $table = 'fiscal_periods';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'fiscal_year_id',
        'name',
        'starts_on',
        'ends_on',
        'is_locked',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_locked' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\FiscalYear::class, 'fiscal_year_id');
    }

    public function journalEntrys(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalEntry::class, 'fiscal_period_id');
    }

}
