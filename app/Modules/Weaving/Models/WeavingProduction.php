<?php

declare(strict_types=1);

namespace App\Modules\Weaving\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeavingProduction extends MillModel
{
    protected $table = 'weaving_productions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'machine_id',
        'shift_id',
        'production_date',
        'rpm',
        'ppi',
        'minutes',
        'efficiency',
        'meters',
        'warp_stops',
        'weft_stops',
        'mechanical_stops',
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
            'rpm' => 'decimal:4',
            'ppi' => 'decimal:4',
            'minutes' => 'decimal:4',
            'efficiency' => 'decimal:4',
            'meters' => 'decimal:4',
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

    public function fabricRolls(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\FabricRoll::class, 'weaving_production_id');
    }

}
