<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class FiscalPeriodPolicy extends MillPolicy
{
    protected string $permission = 'finance.fiscalPeriod';
}
