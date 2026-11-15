<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CottonMixingLinePolicy extends MillPolicy
{
    protected string $permission = 'spinning.cottonMixingLine';
}
