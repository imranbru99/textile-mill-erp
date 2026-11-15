<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CostSheetPolicy extends MillPolicy
{
    protected string $permission = 'costing.costSheet';
}
