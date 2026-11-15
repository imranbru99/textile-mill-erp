<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SupplierBillPolicy extends MillPolicy
{
    protected string $permission = 'finance.supplierBill';
}
