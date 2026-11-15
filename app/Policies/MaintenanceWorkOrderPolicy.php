<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class MaintenanceWorkOrderPolicy extends MillPolicy
{
    protected string $permission = 'maintenance.maintenanceWorkOrder';
}
