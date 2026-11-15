<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SubcontractOrderPolicy extends MillPolicy
{
    protected string $permission = 'planning.subcontractOrder';
}
