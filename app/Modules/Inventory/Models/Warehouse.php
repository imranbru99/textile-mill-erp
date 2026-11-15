<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends MillModel
{
    protected $table = 'warehouses';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'code',
        'name',
        'kind',
        'is_bonded',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_bonded' => 'boolean',
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

    public function itemLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\ItemLot::class, 'warehouse_id');
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceipt::class, 'warehouse_id');
    }

    public function storageBins(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StorageBin::class, 'warehouse_id');
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockBalance::class, 'warehouse_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockMovement::class, 'warehouse_id');
    }

    public function cycleCounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\CycleCount::class, 'warehouse_id');
    }

}
