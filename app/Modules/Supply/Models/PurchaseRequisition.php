<?php

declare(strict_types=1);

namespace App\Modules\Supply\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends MillModel
{
    protected $table = 'purchase_requisitions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'department_id',
        'needed_by',
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
            'needed_by' => 'date',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Department::class, 'department_id');
    }

    public function purchaseRequisitionLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisitionLine::class, 'purchase_requisition_id');
    }

    public function requestForQuotes(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\RequestForQuote::class, 'purchase_requisition_id');
    }

    public function mrpRequirements(): HasMany
    {
        return $this->hasMany(\App\Modules\Planning\Models\MrpRequirement::class, 'purchase_requisition_id');
    }

}
