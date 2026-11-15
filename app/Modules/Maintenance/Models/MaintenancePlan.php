<?php

declare(strict_types=1);

namespace App\Modules\Maintenance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenancePlan extends MillModel
{
    protected $table = 'maintenance_plans';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'name',
        'interval_hours',
        'interval_days',
        'last_done_at',
        'next_due_at',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'interval_hours' => 'decimal:4',
            'last_done_at' => 'datetime',
            'next_due_at' => 'datetime',
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

    public function machine(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Machine::class, 'machine_id');
    }

}
