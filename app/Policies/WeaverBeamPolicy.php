<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class WeaverBeamPolicy extends MillPolicy
{
    protected string $permission = 'weaving.weaverBeam';
}
