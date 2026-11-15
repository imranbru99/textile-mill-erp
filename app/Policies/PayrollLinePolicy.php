<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PayrollLinePolicy extends MillPolicy
{
    protected string $permission = 'hr.payrollLine';
}
