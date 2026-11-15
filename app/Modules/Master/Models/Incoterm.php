<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incoterm extends MillModel
{
    protected $table = 'incoterms';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
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

    public function salesOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrder::class, 'incoterm_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Supply\Models\PurchaseOrder::class, 'incoterm_id');
    }

}
