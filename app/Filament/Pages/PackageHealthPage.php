<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Support\Integrations\PackageHealth;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PackageHealthPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Foundation';

    protected static ?string $navigationLabel = 'Packages';

    protected static ?string $title = 'Package health';

    protected string $view = 'filament.pages.package-health';

    public static function canAccess(): bool
    {
        return auth()->user()?->permits('foundation.company.view') ?? false;
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return ['rows' => app(PackageHealth::class)->all()];
    }
}
