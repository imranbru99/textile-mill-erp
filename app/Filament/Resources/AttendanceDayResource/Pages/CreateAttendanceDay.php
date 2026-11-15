<?php

declare(strict_types=1);

namespace App\Filament\Resources\AttendanceDayResource\Pages;

use App\Filament\Resources\AttendanceDayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceDay extends CreateRecord
{
    protected static string $resource = AttendanceDayResource::class;
}
