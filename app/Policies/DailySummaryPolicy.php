<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class DailySummaryPolicy extends MillPolicy
{
    protected string $permission = 'reports.dailySummary';
}
