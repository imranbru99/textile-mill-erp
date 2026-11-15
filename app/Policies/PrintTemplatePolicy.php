<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PrintTemplatePolicy extends MillPolicy
{
    protected string $permission = 'foundation.printTemplate';
}
