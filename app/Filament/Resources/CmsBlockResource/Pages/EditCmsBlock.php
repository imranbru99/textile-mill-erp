<?php

declare(strict_types=1);

namespace App\Filament\Resources\CmsBlockResource\Pages;

use App\Filament\Resources\CmsBlockResource;
use Filament\Resources\Pages\EditRecord;

class EditCmsBlock extends EditRecord
{
    protected static string $resource = CmsBlockResource::class;
}
