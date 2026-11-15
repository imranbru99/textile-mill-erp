<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UnitConversionResource\Pages\CreateUnitConversion;
use App\Filament\Resources\UnitConversionResource\Pages\EditUnitConversion;
use App\Filament\Resources\UnitConversionResource\Pages\ListUnitConversions;
use App\Modules\Master\Models\UnitConversion;
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

class UnitConversionResource extends Resource
{
    protected static ?string $model = UnitConversion::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $moduleKey = 'master';

    protected static ?string $permission = 'master.unitConversion';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'master')
            && $user->permits((static::$permission ?? 'master.unitConversion').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('from_unit_id')->label('From Unit Id')->relationship('fromUnit', 'name')->searchable()->preload(),
            Select::make('to_unit_id')->label('To Unit Id')->relationship('toUnit', 'name')->searchable()->preload(),
            TextInput::make('factor')->label('Factor')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('from_unit_id')->label('From Unit Id')->sortable()->toggleable(),
            TextColumn::make('to_unit_id')->label('To Unit Id')->sortable()->toggleable(),
            TextColumn::make('factor')->label('Factor')->searchable()->sortable()->toggleable(),
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
            'index' => ListUnitConversions::route('/'),
            'create' => CreateUnitConversion::route('/create'),
            'edit' => EditUnitConversion::route('/{record}/edit'),
        ];
    }
}
