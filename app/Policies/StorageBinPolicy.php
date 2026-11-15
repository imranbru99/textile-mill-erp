<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class StorageBinPolicy extends MillPolicy
{
    protected string $permission = 'inventory.storageBin';
}
