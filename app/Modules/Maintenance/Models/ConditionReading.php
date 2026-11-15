<?php

declare(strict_types=1);

namespace App\Modules\Maintenance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionReading extends MillModel
{
    protected $table = 'condition_readings';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'machine_id',
        'reading_at',
        'temperature_c',
        'vibration',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'reading_at' => 'datetime',
            'temperature_c' => 'decimal:4',
            'vibration' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Machine::class, 'machine_id');
    }

}
