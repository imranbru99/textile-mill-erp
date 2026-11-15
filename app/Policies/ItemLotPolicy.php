<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class ItemLotPolicy extends MillPolicy
{
    protected string $permission = 'inventory.itemLot';
}
