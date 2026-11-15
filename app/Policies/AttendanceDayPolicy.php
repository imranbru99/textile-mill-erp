<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class AttendanceDayPolicy extends MillPolicy
{
    protected string $permission = 'hr.attendanceDay';
}
