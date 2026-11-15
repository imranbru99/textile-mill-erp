<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalWorkflow extends MillModel
{
    protected $table = 'approval_workflows';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'document_type',
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function workflowApprovallevels(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\ApprovalLevel::class, 'workflow_id');
    }

}
