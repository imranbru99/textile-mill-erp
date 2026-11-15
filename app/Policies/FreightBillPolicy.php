<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class FreightBillPolicy extends MillPolicy
{
    protected string $permission = 'logistics.freightBill';
}
