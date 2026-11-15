<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class DesignationPolicy extends MillPolicy
{
    protected string $permission = 'master.designation';
}
