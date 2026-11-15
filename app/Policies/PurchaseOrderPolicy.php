<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PurchaseOrderPolicy extends MillPolicy
{
    protected string $permission = 'purchase.purchaseOrder';
}
