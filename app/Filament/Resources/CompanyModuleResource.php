<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyModuleResource\Pages\CreateCompanyModule;
use App\Filament\Resources\CompanyModuleResource\Pages\EditCompanyModule;
use App\Filament\Resources\CompanyModuleResource\Pages\ListCompanyModules;
use App\Modules\Foundation\Models\CompanyModule;
use App\Support\Modules\ModuleRegistry;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompanyModuleResource extends Resource
{
    protected static ?string $model = CompanyModule::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.companyModule';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.companyModule').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('module_key')->label('Module Key')->required()->maxLength(255),
            Toggle::make('enabled')->label('Enabled'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('module_key')->label('Module Key')->searchable()->sortable()->toggleable(),
            TextColumn::make('enabled')->label('Enabled')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyModules::route('/'),
            'create' => CreateCompanyModule::route('/create'),
            'edit' => EditCompanyModule::route('/{record}/edit'),
        ];
    }
}
