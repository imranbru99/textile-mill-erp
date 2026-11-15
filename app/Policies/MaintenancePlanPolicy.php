<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class MaintenancePlanPolicy extends MillPolicy
{
    protected string $permission = 'maintenance.maintenancePlan';
}
