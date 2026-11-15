<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EffluentLog extends MillModel
{
    protected $table = 'effluent_logs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'log_date',
        'ph',
        'cod',
        'bod',
        'tds',
        'color',
        'discharged',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'ph' => 'decimal:4',
            'cod' => 'decimal:4',
            'bod' => 'decimal:4',
            'tds' => 'decimal:4',
            'discharged' => 'boolean',
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
