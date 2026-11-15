<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class JobPostingPolicy extends MillPolicy
{
    protected string $permission = 'website.jobPosting';
}
