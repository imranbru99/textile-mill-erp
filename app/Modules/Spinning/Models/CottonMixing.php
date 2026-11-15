<?php

declare(strict_types=1);

namespace App\Modules\Spinning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CottonMixing extends MillModel
{
    protected $table = 'cotton_mixings';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'target_micronaire',
        'target_staple',
        'target_strength',
        'laydown_kg',
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
            'target_micronaire' => 'decimal:4',
            'target_staple' => 'decimal:4',
            'target_strength' => 'decimal:4',
            'laydown_kg' => 'decimal:4',
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

    public function cottonMixingLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\CottonMixingLine::class, 'cotton_mixing_id');
    }

    public function yarnLots(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\YarnLot::class, 'cotton_mixing_id');
    }

}
