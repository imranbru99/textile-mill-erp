<?php

declare(strict_types=1);

namespace App\Modules\Supply\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorQuote extends MillModel
{
    protected $table = 'vendor_quotes';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'request_for_quote_id',
        'rank',
        'score',
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
            'score' => 'decimal:4',
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

    public function requestForQuote(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Supply\Models\RequestForQuote::class, 'request_for_quote_id');
    }

    public function vendorQuoteLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuoteLine::class, 'vendor_quote_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrder::class, 'vendor_quote_id');
    }

}
