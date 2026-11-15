<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class MrpRequirementPolicy extends MillPolicy
{
    protected string $permission = 'planning.mrpRequirement';
}
