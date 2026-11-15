<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class WeavingProductionPolicy extends MillPolicy
{
    protected string $permission = 'weaving.weavingProduction';
}
