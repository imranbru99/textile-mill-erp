<?php

declare(strict_types=1);

namespace App\Modules\Gate\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GatePass extends MillModel
{
    protected $table = 'gate_passes';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'vehicle_no',
        'driver_name',
        'party_id',
        'pass_type',
        'return_due',
        'doc_weight',
        'bridge_weight',
        'direction',
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
            'return_due' => 'date',
            'doc_weight' => 'decimal:4',
            'bridge_weight' => 'decimal:4',
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

    public function party(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Party::class, 'party_id');
    }

    public function weighbridgeTickets(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\WeighbridgeTicket::class, 'gate_pass_id');
    }

}
