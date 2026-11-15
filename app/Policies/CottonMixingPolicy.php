<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CottonMixingPolicy extends MillPolicy
{
    protected string $permission = 'spinning.cottonMixing';
}
