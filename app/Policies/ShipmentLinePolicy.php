<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class ShipmentLinePolicy extends MillPolicy
{
    protected string $permission = 'logistics.shipmentLine';
}
