<?php

declare(strict_types=1);

namespace App\Modules\Weaving\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeaverBeam extends MillModel
{
    protected $table = 'weaver_beams';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'beam_no',
        'item_id',
        'ends',
        'length_meter',
        'status',
        'size_pickup',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'length_meter' => 'decimal:4',
            'size_pickup' => 'decimal:4',
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

}
