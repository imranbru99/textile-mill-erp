<?php

declare(strict_types=1);

namespace App\Modules\Spinning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CottonMixingLine extends MillModel
{
    protected $table = 'cotton_mixing_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'cotton_mixing_id',
        'item_lot_id',
        'kg',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'kg' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function cottonMixing(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Spinning\Models\CottonMixing::class, 'cotton_mixing_id');
    }

    public function itemLot(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\ItemLot::class, 'item_lot_id');
    }

}
