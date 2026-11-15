<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class FabricRollPolicy extends MillPolicy
{
    protected string $permission = 'weaving.fabricRoll';
}
