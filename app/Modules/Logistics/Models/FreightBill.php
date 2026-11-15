<?php

declare(strict_types=1);

namespace App\Modules\Logistics\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreightBill extends MillModel
{
    protected $table = 'freight_bills';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'transporter_id',
        'shipment_id',
        'amount',
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
            'amount' => 'decimal:4',
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

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Logistics\Models\Transporter::class, 'transporter_id');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Logistics\Models\Shipment::class, 'shipment_id');
    }

}
