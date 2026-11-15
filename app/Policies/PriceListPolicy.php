<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PriceListPolicy extends MillPolicy
{
    protected string $permission = 'crm.priceList';
}
