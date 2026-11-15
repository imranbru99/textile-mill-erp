<?php

declare(strict_types=1);

namespace App\Modules\Maintenance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceWorkOrder extends MillModel
{
    protected $table = 'maintenance_work_orders';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'breakdown_log_id',
        'kind',
        'started_at',
        'finished_at',
        'downtime_minutes',
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
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
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

    public function machine(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Machine::class, 'machine_id');
    }

    public function breakdownLog(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Maintenance\Models\BreakdownLog::class, 'breakdown_log_id');
    }

}
