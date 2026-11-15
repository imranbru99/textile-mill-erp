<?php

declare(strict_types=1);

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use Filament\Resources\Pages\ListRecords;

class ListBranchs extends ListRecords
{
    protected static string $resource = BranchResource::class;
}
