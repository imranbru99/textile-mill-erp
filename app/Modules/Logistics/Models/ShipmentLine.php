<?php

declare(strict_types=1);

namespace App\Modules\Logistics\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentLine extends MillModel
{
    protected $table = 'shipment_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'shipment_id',
        'item_id',
        'item_lot_id',
        'qty',
        'packages',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Logistics\Models\Shipment::class, 'shipment_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function itemLot(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\ItemLot::class, 'item_lot_id');
    }

}
