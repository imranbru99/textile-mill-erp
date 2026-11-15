<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollLine extends MillModel
{
    protected $table = 'payroll_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'payroll_run_id',
        'employee_id',
        'basic',
        'ot_pay',
        'incentive',
        'bonus',
        'deduction',
        'net',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'basic' => 'decimal:4',
            'ot_pay' => 'decimal:4',
            'incentive' => 'decimal:4',
            'bonus' => 'decimal:4',
            'deduction' => 'decimal:4',
            'net' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\People\Models\PayrollRun::class, 'payroll_run_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\People\Models\Employee::class, 'employee_id');
    }

}
