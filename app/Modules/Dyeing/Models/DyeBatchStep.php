<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DyeBatchStep extends MillModel
{
    protected $table = 'dye_batch_steps';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'dye_batch_id',
        'step_no',
        'name',
        'temperature_c',
        'hold_minutes',
        'ph',
        'confirmed_at',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'temperature_c' => 'decimal:4',
            'ph' => 'decimal:4',
            'confirmed_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function dyeBatch(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Dyeing\Models\DyeBatch::class, 'dye_batch_id');
    }

}
