<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class GatePassPolicy extends MillPolicy
{
    protected string $permission = 'gate.gatePass';
}
