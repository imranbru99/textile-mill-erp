<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class YarnLotPolicy extends MillPolicy
{
    protected string $permission = 'spinning.yarnLot';
}
