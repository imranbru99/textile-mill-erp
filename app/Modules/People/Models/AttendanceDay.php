<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDay extends MillModel
{
    protected $table = 'attendance_days';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'employee_id',
        'work_date',
        'shift_id',
        'in_at',
        'out_at',
        'worked_minutes',
        'late_minutes',
        'ot_minutes',
        'status',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'in_at' => 'datetime',
            'out_at' => 'datetime',
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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\People\Models\Employee::class, 'employee_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Shift::class, 'shift_id');
    }

}
