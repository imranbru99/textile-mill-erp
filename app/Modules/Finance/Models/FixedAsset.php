<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends MillModel
{
    protected $table = 'fixed_assets';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'acquired_on',
        'cost',
        'salvage',
        'life_months',
        'accumulated',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'acquired_on' => 'date',
            'cost' => 'decimal:4',
            'salvage' => 'decimal:4',
            'accumulated' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

}
