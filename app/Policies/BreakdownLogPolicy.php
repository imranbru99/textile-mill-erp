<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class BreakdownLogPolicy extends MillPolicy
{
    protected string $permission = 'maintenance.breakdownLog';
}
