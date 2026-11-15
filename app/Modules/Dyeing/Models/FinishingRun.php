<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinishingRun extends MillModel
{
    protected $table = 'finishing_runs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'item_id',
        'process',
        'temperature_c',
        'speed',
        'overfeed',
        'width_inch',
        'gsm',
        'shrinkage_warp',
        'shrinkage_weft',
        'released',
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
            'temperature_c' => 'decimal:4',
            'speed' => 'decimal:4',
            'overfeed' => 'decimal:4',
            'width_inch' => 'decimal:4',
            'gsm' => 'decimal:4',
            'shrinkage_warp' => 'decimal:4',
            'shrinkage_weft' => 'decimal:4',
            'released' => 'boolean',
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

    public function machine(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Machine::class, 'machine_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
