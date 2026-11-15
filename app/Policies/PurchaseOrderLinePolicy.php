<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PurchaseOrderLinePolicy extends MillPolicy
{
    protected string $permission = 'purchase.purchaseOrderLine';
}
