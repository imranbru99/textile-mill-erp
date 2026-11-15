<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class KnittingProductionPolicy extends MillPolicy
{
    protected string $permission = 'knitting.knittingProduction';
}
