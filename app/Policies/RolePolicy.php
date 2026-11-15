<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class RolePolicy extends MillPolicy
{
    protected string $permission = 'foundation.role';
}
