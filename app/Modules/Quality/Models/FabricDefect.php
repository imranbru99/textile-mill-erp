<?php

declare(strict_types=1);

namespace App\Modules\Quality\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FabricDefect extends MillModel
{
    protected $table = 'fabric_defects';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'fabric_roll_id',
        'defect_code',
        'points',
        'position_meter',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'position_meter' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function fabricRoll(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Weaving\Models\FabricRoll::class, 'fabric_roll_id');
    }

}
