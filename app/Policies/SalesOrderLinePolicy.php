<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SalesOrderLinePolicy extends MillPolicy
{
    protected string $permission = 'crm.salesOrderLine';
}
