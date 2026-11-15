<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SampleRequestPolicy extends MillPolicy
{
    protected string $permission = 'crm.sampleRequest';
}
