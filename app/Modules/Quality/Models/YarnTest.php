<?php

declare(strict_types=1);

namespace App\Modules\Quality\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YarnTest extends MillModel
{
    protected $table = 'yarn_tests';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'yarn_lot_id',
        'uster_u',
        'ipi',
        'hairiness',
        'strength',
        'cv_percent',
        'grade',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'uster_u' => 'decimal:4',
            'ipi' => 'decimal:4',
            'hairiness' => 'decimal:4',
            'strength' => 'decimal:4',
            'cv_percent' => 'decimal:4',
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

    public function yarnLot(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Spinning\Models\YarnLot::class, 'yarn_lot_id');
    }

}
