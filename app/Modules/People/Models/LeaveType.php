<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends MillModel
{
    protected $table = 'leave_types';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'days_per_year',
        'paid',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'days_per_year' => 'decimal:4',
            'paid' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\LeaveRequest::class, 'leave_type_id');
    }

}
