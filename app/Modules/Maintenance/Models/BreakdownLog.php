<?php

declare(strict_types=1);

namespace App\Modules\Maintenance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BreakdownLog extends MillModel
{
    protected $table = 'breakdown_logs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'reason_code',
        'started_at',
        'ended_at',
        'minutes',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
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

    public function maintenanceWorkOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Maintenance\Models\MaintenanceWorkOrder::class, 'breakdown_log_id');
    }

}
