<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends MillModel
{
    protected $table = 'parties';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'party_type',
        'country',
        'currency_code',
        'credit_limit',
        'rating',
        'payment_term_id',
        'blacklisted',
        'phone',
        'email',
        'address',
        'bin_vat',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:4',
            'rating' => 'decimal:4',
            'blacklisted' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\PaymentTerm::class, 'payment_term_id');
    }

    public function supplierItemlots(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\ItemLot::class, 'supplier_id');
    }

    public function partyContacts(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\PartyContact::class, 'party_id');
    }

    public function inquirys(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Inquiry::class, 'party_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Quotation::class, 'party_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrder::class, 'party_id');
    }

    public function vendorQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuote::class, 'party_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrder::class, 'party_id');
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceipt::class, 'party_id');
    }

    public function gatePass(): HasMany
    {
        return $this->hasMany(\App\Modules\Gate\Models\GatePass::class, 'party_id');
    }

    public function labDips(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\LabDip::class, 'party_id');
    }

    public function subcontractOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\SubcontractOrder::class, 'party_id');
    }

    public function customerClaims(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\CustomerClaim::class, 'party_id');
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoice::class, 'party_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Receipt::class, 'party_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierBill::class, 'party_id');
    }

    public function supplierPayments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierPayment::class, 'party_id');
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Cheque::class, 'party_id');
    }

    public function letterOfCredits(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LetterOfCredit::class, 'party_id');
    }

    public function sampleRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SampleRequest::class, 'party_id');
    }

}
