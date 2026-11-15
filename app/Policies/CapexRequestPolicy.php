<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CapexRequestPolicy extends MillPolicy
{
    protected string $permission = 'projects.capexRequest';
}
