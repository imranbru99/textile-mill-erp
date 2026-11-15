<?php

declare(strict_types=1);

namespace App\Modules\Planning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MrpRequirement extends MillModel
{
    protected $table = 'mrp_requirements';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'item_id',
        'required_qty',
        'available_qty',
        'shortage_qty',
        'needed_by',
        'purchase_requisition_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'required_qty' => 'decimal:4',
            'available_qty' => 'decimal:4',
            'shortage_qty' => 'decimal:4',
            'needed_by' => 'date',
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function purchaseRequisition(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Supply\Models\PurchaseRequisition::class, 'purchase_requisition_id');
    }

}
