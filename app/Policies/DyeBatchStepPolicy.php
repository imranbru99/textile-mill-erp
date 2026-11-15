<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class DyeBatchStepPolicy extends MillPolicy
{
    protected string $permission = 'dyeing.dyeBatchStep';
}
