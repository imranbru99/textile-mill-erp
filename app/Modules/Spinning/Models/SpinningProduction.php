<?php

declare(strict_types=1);

namespace App\Modules\Spinning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpinningProduction extends MillModel
{
    protected $table = 'spinning_productions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'stage',
        'shift_id',
        'production_date',
        'input_kg',
        'output_kg',
        'waste_kg',
        'efficiency',
        'spindle_rpm',
        'tpi',
        'count_ne',
        'hours',
        'yarn_lot_id',
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
            'production_date' => 'date',
            'input_kg' => 'decimal:4',
            'output_kg' => 'decimal:4',
            'waste_kg' => 'decimal:4',
            'efficiency' => 'decimal:4',
            'spindle_rpm' => 'decimal:4',
            'tpi' => 'decimal:4',
            'count_ne' => 'decimal:4',
            'hours' => 'decimal:4',
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

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Shift::class, 'shift_id');
    }

    public function yarnLot(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Spinning\Models\YarnLot::class, 'yarn_lot_id');
    }

}
