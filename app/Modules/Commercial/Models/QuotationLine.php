<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationLine extends MillModel
{
    protected $table = 'quotation_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'quotation_id',
        'item_id',
        'description',
        'qty',
        'rate',
        'amount',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:4',
            'rate' => 'decimal:6',
            'amount' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Quotation::class, 'quotation_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
