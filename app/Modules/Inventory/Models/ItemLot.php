<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemLot extends MillModel
{
    protected $table = 'item_lots';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'item_id',
        'warehouse_id',
        'lot_no',
        'qty_on_hand',
        'qty_reserved',
        'unit_cost',
        'received_at',
        'expiry_date',
        'status',
        'barcode',
        'attributes',
        'supplier_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty_on_hand' => 'decimal:4',
            'qty_reserved' => 'decimal:4',
            'unit_cost' => 'decimal:6',
            'received_at' => 'datetime',
            'expiry_date' => 'date',
            'attributes' => 'array',
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\Warehouse::class, 'warehouse_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Party::class, 'supplier_id');
    }

    public function goodsReceiptLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceiptLine::class, 'item_lot_id');
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockBalance::class, 'item_lot_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockMovement::class, 'item_lot_id');
    }

    public function shipmentLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\ShipmentLine::class, 'item_lot_id');
    }

    public function cottonMixingLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\CottonMixingLine::class, 'item_lot_id');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\QualityInspection::class, 'item_lot_id');
    }

    public function customerClaims(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\CustomerClaim::class, 'item_lot_id');
    }

}
