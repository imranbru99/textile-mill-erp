<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class UnitOfMeasurePolicy extends MillPolicy
{
    protected string $permission = 'master.unitOfMeasure';
}
