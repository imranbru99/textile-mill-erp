<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class FuelLogPolicy extends MillPolicy
{
    protected string $permission = 'utilities.fuelLog';
}
