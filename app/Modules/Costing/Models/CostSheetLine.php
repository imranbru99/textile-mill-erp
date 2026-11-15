<?php

declare(strict_types=1);

namespace App\Modules\Costing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostSheetLine extends MillModel
{
    protected $table = 'cost_sheet_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'cost_sheet_id',
        'element',
        'standard_amount',
        'actual_amount',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'standard_amount' => 'decimal:4',
            'actual_amount' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function costSheet(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Costing\Models\CostSheet::class, 'cost_sheet_id');
    }

}
