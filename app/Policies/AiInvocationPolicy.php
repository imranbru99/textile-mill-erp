<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class AiInvocationPolicy extends MillPolicy
{
    protected string $permission = 'issues.aiInvocation';
}
