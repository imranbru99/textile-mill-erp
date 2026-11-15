<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListItem extends MillModel
{
    protected $table = 'price_list_items';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'price_list_id',
        'item_id',
        'rate',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:6',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\PriceList::class, 'price_list_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
