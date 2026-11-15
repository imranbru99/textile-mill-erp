<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends MillModel
{
    protected $table = 'departments';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'code',
        'name',
        'cost_center_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [

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

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\CostCenter::class, 'cost_center_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\Section::class, 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\Employee::class, 'department_id');
    }

    public function purchaseRequisitions(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseRequisition::class, 'department_id');
    }

}
