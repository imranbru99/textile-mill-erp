<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends MillModel
{
    protected $table = 'payroll_runs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'period_start',
        'period_end',
        'number',
        'status',
        'document_date',
        'currency_code',
        'exchange_rate',
        'notes',
        'total_amount',
        'qr_token',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'document_date' => 'date',
            'exchange_rate' => 'decimal:6',
            'total_amount' => 'decimal:4',
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

    public function payrollLines(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PayrollLine::class, 'payroll_run_id');
    }

}
