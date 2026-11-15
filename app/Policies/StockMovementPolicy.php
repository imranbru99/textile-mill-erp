<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class StockMovementPolicy extends MillPolicy
{
    protected string $permission = 'inventory.stockMovement';
}
