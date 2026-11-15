<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class CapaPolicy extends MillPolicy
{
    protected string $permission = 'quality.capa';
}
