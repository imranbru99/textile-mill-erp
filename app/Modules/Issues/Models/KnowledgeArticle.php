<?php

declare(strict_types=1);

namespace App\Modules\Issues\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KnowledgeArticle extends MillModel
{
    protected $table = 'knowledge_articles';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'title',
        'slug',
        'problem',
        'solution',
        'module_key',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [

        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function millIssues(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\MillIssue::class, 'knowledge_article_id');
    }

}
