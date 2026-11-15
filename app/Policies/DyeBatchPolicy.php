<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class DyeBatchPolicy extends MillPolicy
{
    protected string $permission = 'dyeing.dyeBatch';
}
