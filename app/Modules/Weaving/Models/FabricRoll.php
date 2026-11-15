<?php

declare(strict_types=1);

namespace App\Modules\Weaving\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FabricRoll extends MillModel
{
    protected $table = 'fabric_rolls';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'roll_no',
        'item_id',
        'length_meter',
        'width_inch',
        'gsm',
        'grade',
        'points',
        'points_per_100',
        'status',
        'weaving_production_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'length_meter' => 'decimal:4',
            'width_inch' => 'decimal:4',
            'gsm' => 'decimal:4',
            'points' => 'decimal:4',
            'points_per_100' => 'decimal:4',
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

    public function weavingProduction(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Weaving\Models\WeavingProduction::class, 'weaving_production_id');
    }

    public function fabricDefects(): HasMany
    {
        return $this->hasMany(\App\Modules\Quality\Models\FabricDefect::class, 'fabric_roll_id');
    }

}
