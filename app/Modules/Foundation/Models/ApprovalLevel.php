<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLevel extends MillModel
{
    protected $table = 'approval_levels';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'workflow_id',
        'level',
        'role_slug',
        'min_amount',
        'max_amount',
        'escalate_hours',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:4',
            'max_amount' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\ApprovalWorkflow::class, 'workflow_id');
    }

}
