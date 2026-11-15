<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class HsCodePolicy extends MillPolicy
{
    protected string $permission = 'master.hsCode';
}
