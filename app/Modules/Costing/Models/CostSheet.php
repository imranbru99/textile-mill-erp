<?php

declare(strict_types=1);

namespace App\Modules\Costing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostSheet extends MillModel
{
    protected $table = 'cost_sheets';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'sales_order_id',
        'item_id',
        'qty',
        'material_cost',
        'conversion_cost',
        'packing_cost',
        'overhead_cost',
        'total_cost',
        'margin_percent',
        'quote_rate',
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
            'qty' => 'decimal:4',
            'material_cost' => 'decimal:4',
            'conversion_cost' => 'decimal:4',
            'packing_cost' => 'decimal:4',
            'overhead_cost' => 'decimal:4',
            'total_cost' => 'decimal:4',
            'margin_percent' => 'decimal:4',
            'quote_rate' => 'decimal:6',
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

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\SalesOrder::class, 'sales_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function costSheetLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheetLine::class, 'cost_sheet_id');
    }

}
