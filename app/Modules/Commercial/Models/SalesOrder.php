<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends MillModel
{
    protected $table = 'sales_orders';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'quotation_id',
        'delivery_date',
        'incoterm_id',
        'payment_term_id',
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
            'delivery_date' => 'date',
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Quotation::class, 'quotation_id');
    }

    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Incoterm::class, 'incoterm_id');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\PaymentTerm::class, 'payment_term_id');
    }

    public function salesOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrderLine::class, 'sales_order_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\Shipment::class, 'sales_order_id');
    }

    public function labDips(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\LabDip::class, 'sales_order_id');
    }

    public function productionPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\ProductionPlan::class, 'sales_order_id');
    }

    public function customerClaims(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\CustomerClaim::class, 'sales_order_id');
    }

    public function costSheets(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheet::class, 'sales_order_id');
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoice::class, 'sales_order_id');
    }

}
