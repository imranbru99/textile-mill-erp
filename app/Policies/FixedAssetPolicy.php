<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class FixedAssetPolicy extends MillPolicy
{
    protected string $permission = 'finance.fixedAsset';
}
