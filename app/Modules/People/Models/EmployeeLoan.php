<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLoan extends MillModel
{
    protected $table = 'employee_loans';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'employee_id',
        'principal',
        'balance',
        'installment',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'principal' => 'decimal:4',
            'balance' => 'decimal:4',
            'installment' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\People\Models\Employee::class, 'employee_id');
    }

}
