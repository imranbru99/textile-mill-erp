<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class YarnTestPolicy extends MillPolicy
{
    protected string $permission = 'quality.yarnTest';
}
