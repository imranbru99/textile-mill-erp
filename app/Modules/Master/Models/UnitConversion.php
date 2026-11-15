<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitConversion extends MillModel
{
    protected $table = 'unit_conversions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'from_unit_id',
        'to_unit_id',
        'factor',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'factor' => 'decimal:6',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function fromUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\UnitOfMeasure::class, 'from_unit_id');
    }

    public function toUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\UnitOfMeasure::class, 'to_unit_id');
    }

}
