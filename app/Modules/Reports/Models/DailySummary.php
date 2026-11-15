<?php

declare(strict_types=1);

namespace App\Modules\Reports\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySummary extends MillModel
{
    protected $table = 'daily_summaries';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'summary_date',
        'section',
        'output_qty',
        'efficiency',
        'waste_percent',
        'payload',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'summary_date' => 'date',
            'output_qty' => 'decimal:4',
            'efficiency' => 'decimal:4',
            'waste_percent' => 'decimal:4',
            'payload' => 'array',
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
