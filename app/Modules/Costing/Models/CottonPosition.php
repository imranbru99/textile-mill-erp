<?php

declare(strict_types=1);

namespace App\Modules\Costing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CottonPosition extends MillModel
{
    protected $table = 'cotton_positions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'position_date',
        'open_purchase_kg',
        'open_sales_kg',
        'avg_purchase_rate',
        'avg_sales_rate',
        'forward_cover',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'position_date' => 'date',
            'open_purchase_kg' => 'decimal:4',
            'open_sales_kg' => 'decimal:4',
            'avg_purchase_rate' => 'decimal:6',
            'avg_sales_rate' => 'decimal:6',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

}
