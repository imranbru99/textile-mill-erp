<?php

declare(strict_types=1);

namespace App\Modules\Supply\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends MillModel
{
    protected $table = 'purchase_orders';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'vendor_quote_id',
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

    public function vendorQuote(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Supply\Models\VendorQuote::class, 'vendor_quote_id');
    }

    public function incoterm(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Incoterm::class, 'incoterm_id');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\PaymentTerm::class, 'payment_term_id');
    }

    public function purchaseOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrderLine::class, 'purchase_order_id');
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceipt::class, 'purchase_order_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierBill::class, 'purchase_order_id');
    }

}
