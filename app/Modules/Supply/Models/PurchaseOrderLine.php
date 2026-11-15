<?php

declare(strict_types=1);

namespace App\Modules\Supply\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderLine extends MillModel
{
    protected $table = 'purchase_order_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'purchase_order_id',
        'item_id',
        'qty',
        'received_qty',
        'rate',
        'amount',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:4',
            'received_qty' => 'decimal:4',
            'rate' => 'decimal:6',
            'amount' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Supply\Models\PurchaseOrder::class, 'purchase_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
