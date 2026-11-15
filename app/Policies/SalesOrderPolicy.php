<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SalesOrderPolicy extends MillPolicy
{
    protected string $permission = 'crm.salesOrder';
}
