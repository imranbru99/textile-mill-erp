<?php

declare(strict_types=1);

namespace App\Modules\Gate\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeighbridgeTicket extends MillModel
{
    protected $table = 'weighbridge_tickets';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'vehicle_no',
        'gross',
        'tare',
        'net',
        'gate_pass_id',
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
            'gross' => 'decimal:4',
            'tare' => 'decimal:4',
            'net' => 'decimal:4',
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

    public function gatePass(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Gate\Models\GatePass::class, 'gate_pass_id');
    }

}
