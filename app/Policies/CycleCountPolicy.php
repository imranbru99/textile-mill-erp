<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CycleCountPolicy extends MillPolicy
{
    protected string $permission = 'inventory.cycleCount';
}
