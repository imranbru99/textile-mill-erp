<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class EmployeePolicy extends MillPolicy
{
    protected string $permission = 'hr.employee';
}
