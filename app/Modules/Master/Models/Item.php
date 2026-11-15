<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends MillModel
{
    protected $table = 'items';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'slug',
        'item_type',
        'uom_id',
        'count_ne',
        'ply',
        'tpi',
        'gsm',
        'width_inch',
        'epi',
        'ppi',
        'construction',
        'weave',
        'fiber_json',
        'color',
        'shade_code',
        'hazard_class',
        'shelf_life_days',
        'reorder_level',
        'min_qty',
        'max_qty',
        'standard_cost',
        'barcode',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'count_ne' => 'decimal:4',
            'tpi' => 'decimal:4',
            'gsm' => 'decimal:4',
            'width_inch' => 'decimal:4',
            'epi' => 'decimal:4',
            'ppi' => 'decimal:4',
            'fiber_json' => 'array',
            'reorder_level' => 'decimal:4',
            'min_qty' => 'decimal:4',
            'max_qty' => 'decimal:4',
            'standard_cost' => 'decimal:6',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\UnitOfMeasure::class, 'uom_id');
    }

    public function itemLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\ItemLot::class, 'item_id');
    }

    public function quotationLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\QuotationLine::class, 'item_id');
    }

    public function salesOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrderLine::class, 'item_id');
    }

    public function priceListItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\PriceListItem::class, 'item_id');
    }

    public function purchaseRequisitionLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisitionLine::class, 'item_id');
    }

    public function vendorQuoteLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\VendorQuoteLine::class, 'item_id');
    }

    public function purchaseOrderLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrderLine::class, 'item_id');
    }

    public function goodsReceiptLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\GoodsReceiptLine::class, 'item_id');
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockBalance::class, 'item_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Modules\Inventory\Models\StockMovement::class, 'item_id');
    }

    public function shipmentLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\ShipmentLine::class, 'item_id');
    }

    public function yarnLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\YarnLot::class, 'item_id');
    }

    public function weaverBeams(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeaverBeam::class, 'item_id');
    }

    public function fabricRolls(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\FabricRoll::class, 'item_id');
    }

    public function recipeLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\RecipeLine::class, 'item_id');
    }

    public function dyeBatchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatch::class, 'item_id');
    }

    public function dyeBatchChemicals(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatchChemical::class, 'item_id');
    }

    public function finishingRuns(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\FinishingRun::class, 'item_id');
    }

    public function productionPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\ProductionPlan::class, 'item_id');
    }

    public function parentItemBomlines(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\BomLine::class, 'parent_item_id');
    }

    public function componentItemBomlines(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\BomLine::class, 'component_item_id');
    }

    public function mrpRequirements(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\MrpRequirement::class, 'item_id');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\QualityInspection::class, 'item_id');
    }

    public function costSheets(): HasMany
    {
        return $this->hasMany(\App\Modules\Costing\Models\CostSheet::class, 'item_id');
    }

    public function ppeIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\PpeIssue::class, 'item_id');
    }

    public function salesInvoiceLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoiceLine::class, 'item_id');
    }

    public function bondedRegisters(): HasMany
    {
        return $this->hasMany(\App\Modules\Compliance\Models\BondedRegister::class, 'item_id');
    }

    public function sampleRequests(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SampleRequest::class, 'item_id');
    }

}
