<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitOfMeasure extends MillModel
{
    protected $table = 'units_of_measure';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'dimension',
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

    public function fromUnitUnitconversions(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\UnitConversion::class, 'from_unit_id');
    }

    public function toUnitUnitconversions(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\UnitConversion::class, 'to_unit_id');
    }

    public function uomItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Master\Models\Item::class, 'uom_id');
    }

    public function uomSalesorderlines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrderLine::class, 'uom_id');
    }

}
