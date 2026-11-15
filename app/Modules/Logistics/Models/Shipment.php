<?php

declare(strict_types=1);

namespace App\Modules\Logistics\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends MillModel
{
    protected $table = 'shipments';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'sales_order_id',
        'transporter_id',
        'vehicle_no',
        'delivered_at',
        'pod_name',
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
            'delivered_at' => 'datetime',
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

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\SalesOrder::class, 'sales_order_id');
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Logistics\Models\Transporter::class, 'transporter_id');
    }

    public function freightBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\FreightBill::class, 'shipment_id');
    }

    public function shipmentLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\ShipmentLine::class, 'shipment_id');
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoice::class, 'shipment_id');
    }

}
