<?php

declare(strict_types=1);

namespace App\Modules\Issues\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MillIssue extends MillModel
{
    protected $table = 'mill_issues';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'issue_type',
        'severity',
        'source_type',
        'source_id',
        'owner_id',
        'due_at',
        'sla_hours',
        'recommendation',
        'resolution',
        'knowledge_article_id',
        'number',
        'status',
        'document_date',
        'currency_code',
        'exchange_rate',
        'notes',
        'total_amount',
        'qr_token',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'document_date' => 'date',
            'exchange_rate' => 'decimal:6',
            'total_amount' => 'decimal:4',
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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function knowledgeArticle(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Issues\Models\KnowledgeArticle::class, 'knowledge_article_id');
    }

    public function automationLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\AutomationLog::class, 'mill_issue_id');
    }

}
