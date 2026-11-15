<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class BondedRegisterPolicy extends MillPolicy
{
    protected string $permission = 'bonded.bondedRegister';
}
