<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class KnowledgeArticlePolicy extends MillPolicy
{
    protected string $permission = 'issues.knowledgeArticle';
}
