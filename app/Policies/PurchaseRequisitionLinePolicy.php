<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PurchaseRequisitionLinePolicy extends MillPolicy
{
    protected string $permission = 'purchase.purchaseRequisitionLine';
}
