<?php

declare(strict_types=1);

namespace App\Modules\Planning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomLine extends MillModel
{
    protected $table = 'bom_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'production_plan_id',
        'parent_item_id',
        'component_item_id',
        'qty_per',
        'waste_percent',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty_per' => 'decimal:4',
            'waste_percent' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Planning\Models\ProductionPlan::class, 'production_plan_id');
    }

    public function parentItem(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'parent_item_id');
    }

    public function componentItem(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'component_item_id');
    }

}
