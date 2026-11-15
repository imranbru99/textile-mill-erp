<?php

declare(strict_types=1);

namespace App\Modules\Utilities\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends MillModel
{
    protected $table = 'fuel_logs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'log_date',
        'liters',
        'amount',
        'generator',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'liters' => 'decimal:4',
            'amount' => 'decimal:4',
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

}
