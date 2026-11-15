<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DyeBatch extends MillModel
{
    protected $table = 'dye_batches';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'recipe_id',
        'machine_id',
        'item_id',
        'weight_kg',
        'liquor_ratio',
        'shade_name',
        'is_redye',
        'rft',
        'standard_cost',
        'actual_cost',
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
            'weight_kg' => 'decimal:4',
            'liquor_ratio' => 'decimal:4',
            'is_redye' => 'boolean',
            'rft' => 'boolean',
            'standard_cost' => 'decimal:4',
            'actual_cost' => 'decimal:4',
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

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Dyeing\Models\Recipe::class, 'recipe_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Machine::class, 'machine_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function dyeBatchSteps(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatchStep::class, 'dye_batch_id');
    }

    public function dyeBatchChemicals(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatchChemical::class, 'dye_batch_id');
    }

}
