<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class ShipmentPolicy extends MillPolicy
{
    protected string $permission = 'logistics.shipment';
}
