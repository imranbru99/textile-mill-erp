<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class VisitorPassPolicy extends MillPolicy
{
    protected string $permission = 'gate.visitorPass';
}
