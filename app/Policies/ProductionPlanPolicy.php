<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class ProductionPlanPolicy extends MillPolicy
{
    protected string $permission = 'planning.productionPlan';
}
