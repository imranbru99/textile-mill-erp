<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CmsPagePolicy extends MillPolicy
{
    protected string $permission = 'website.cmsPage';
}
