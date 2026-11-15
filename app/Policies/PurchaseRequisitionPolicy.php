<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PurchaseRequisitionPolicy extends MillPolicy
{
    protected string $permission = 'purchase.purchaseRequisition';
}
