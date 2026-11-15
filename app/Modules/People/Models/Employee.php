<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends MillModel
{
    protected $table = 'employees';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'employee_no',
        'name',
        'designation_id',
        'department_id',
        'category',
        'join_date',
        'basic_salary',
        'phone',
        'nid',
        'status',
        'device_pin',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'basic_salary' => 'decimal:4',
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

    public function designation(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Designation::class, 'designation_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Department::class, 'department_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\LeaveRequest::class, 'employee_id');
    }

    public function attendanceDays(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AttendanceDay::class, 'employee_id');
    }

    public function payrollLines(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PayrollLine::class, 'employee_id');
    }

    public function employeeLoans(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\EmployeeLoan::class, 'employee_id');
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\TrainingRecord::class, 'employee_id');
    }

    public function accidentReports(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AccidentReport::class, 'employee_id');
    }

    public function ppeIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PpeIssue::class, 'employee_id');
    }

}
