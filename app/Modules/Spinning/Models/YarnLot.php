<?php

declare(strict_types=1);

namespace App\Modules\Spinning\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YarnLot extends MillModel
{
    protected $table = 'yarn_lots';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'lot_no',
        'item_id',
        'count_ne',
        'ply',
        'tpi',
        'grade',
        'qty_kg',
        'cotton_mixing_id',
        'status',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'count_ne' => 'decimal:4',
            'tpi' => 'decimal:4',
            'qty_kg' => 'decimal:4',
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

    public function cottonMixing(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Spinning\Models\CottonMixing::class, 'cotton_mixing_id');
    }

    public function spinningProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\SpinningProduction::class, 'yarn_lot_id');
    }

    public function yarnTests(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\YarnTest::class, 'yarn_lot_id');
    }

}
