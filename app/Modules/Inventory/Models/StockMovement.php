<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends MillModel
{
    protected $table = 'stock_movements';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'item_id',
        'item_lot_id',
        'warehouse_id',
        'direction',
        'qty',
        'unit_cost',
        'movement_type',
        'reference_type',
        'reference_id',
        'balance_after',
        'transacted_at',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:4',
            'unit_cost' => 'decimal:6',
            'balance_after' => 'decimal:4',
            'transacted_at' => 'datetime',
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

    public function itemLot(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\ItemLot::class, 'item_lot_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\Warehouse::class, 'warehouse_id');
    }

}
