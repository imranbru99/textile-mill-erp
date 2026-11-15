<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class AccidentReportPolicy extends MillPolicy
{
    protected string $permission = 'hr.accidentReport';
}
