<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class PartyPolicy extends MillPolicy
{
    protected string $permission = 'crm.party';
}
