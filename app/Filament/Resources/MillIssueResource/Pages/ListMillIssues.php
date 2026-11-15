<?php

declare(strict_types=1);

namespace App\Filament\Resources\MillIssueResource\Pages;

use App\Filament\Resources\MillIssueResource;
use Filament\Resources\Pages\ListRecords;

class ListMillIssues extends ListRecords
{
    protected static string $resource = MillIssueResource::class;
}
