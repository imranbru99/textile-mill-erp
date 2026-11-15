<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeLine extends MillModel
{
    protected $table = 'recipe_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'recipe_id',
        'item_id',
        'percent_owf',
        'grams_per_liter',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'percent_owf' => 'decimal:4',
            'grams_per_liter' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Dyeing\Models\Recipe::class, 'recipe_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
