<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DyeBatchChemical extends MillModel
{
    protected $table = 'dye_batch_chemicals';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'dye_batch_id',
        'item_id',
        'standard_qty',
        'actual_qty',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'standard_qty' => 'decimal:4',
            'actual_qty' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function dyeBatch(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Dyeing\Models\DyeBatch::class, 'dye_batch_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
