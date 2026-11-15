<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CottonPositionPolicy extends MillPolicy
{
    protected string $permission = 'costing.cottonPosition';
}
