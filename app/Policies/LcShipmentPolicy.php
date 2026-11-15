<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class LcShipmentPolicy extends MillPolicy
{
    protected string $permission = 'finance.lcShipment';
}
