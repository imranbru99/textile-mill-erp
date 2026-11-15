<?php

declare(strict_types=1);

namespace App\Modules\Utilities\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends MillModel
{
    protected $table = 'meter_readings';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'utility_meter_id',
        'reading_at',
        'reading',
        'consumption',
        'cost',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'reading_at' => 'datetime',
            'reading' => 'decimal:4',
            'consumption' => 'decimal:4',
            'cost' => 'decimal:4',
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

    public function utilityMeter(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Utilities\Models\UtilityMeter::class, 'utility_meter_id');
    }

}
