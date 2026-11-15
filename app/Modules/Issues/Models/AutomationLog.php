<?php

declare(strict_types=1);

namespace App\Modules\Issues\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationLog extends MillModel
{
    protected $table = 'automation_logs';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'automation_rule_id',
        'mill_issue_id',
        'action',
        'payload',
        'undone_at',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'undone_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function automationRule(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Issues\Models\AutomationRule::class, 'automation_rule_id');
    }

    public function millIssue(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Issues\Models\MillIssue::class, 'mill_issue_id');
    }

}
