<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ConditionReadingResource\Pages\CreateConditionReading;
use App\Filament\Resources\ConditionReadingResource\Pages\EditConditionReading;
use App\Filament\Resources\ConditionReadingResource\Pages\ListConditionReadings;
use App\Modules\Maintenance\Models\ConditionReading;
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

class ConditionReadingResource extends Resource
{
    protected static ?string $model = ConditionReading::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $moduleKey = 'maintenance';

    protected static ?string $permission = 'maintenance.conditionReading';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'maintenance')
            && $user->permits((static::$permission ?? 'maintenance.conditionReading').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('machine_id')->label('Machine Id')->relationship('machine', 'name')->searchable()->preload(),
            DateTimePicker::make('reading_at')->label('Reading At'),
            TextInput::make('temperature_c')->label('Temperature C')->numeric(),
            TextInput::make('vibration')->label('Vibration')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('reading_at')->label('Reading At')->searchable()->sortable()->toggleable(),
            TextColumn::make('temperature_c')->label('Temperature C')->searchable()->sortable()->toggleable(),
            TextColumn::make('vibration')->label('Vibration')->searchable()->sortable()->toggleable(),
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
            'index' => ListConditionReadings::route('/'),
            'create' => CreateConditionReading::route('/create'),
            'edit' => EditConditionReading::route('/{record}/edit'),
        ];
    }
}
