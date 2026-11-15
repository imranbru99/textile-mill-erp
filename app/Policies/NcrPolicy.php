<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class NcrPolicy extends MillPolicy
{
    protected string $permission = 'quality.ncr';
}
