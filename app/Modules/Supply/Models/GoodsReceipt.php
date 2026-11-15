<?php

declare(strict_types=1);

namespace App\Modules\Supply\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceipt extends MillModel
{
    protected $table = 'goods_receipts';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'purchase_order_id',
        'warehouse_id',
        'party_id',
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

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Supply\Models\PurchaseOrder::class, 'purchase_order_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\Warehouse::class, 'warehouse_id');
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Party::class, 'party_id');
    }

    public function goodsReceiptLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceiptLine::class, 'goods_receipt_id');
    }

}
