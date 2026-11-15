<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MeterReadingResource\Pages\CreateMeterReading;
use App\Filament\Resources\MeterReadingResource\Pages\EditMeterReading;
use App\Filament\Resources\MeterReadingResource\Pages\ListMeterReadings;
use App\Modules\Utilities\Models\MeterReading;
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

class MeterReadingResource extends Resource
{
    protected static ?string $model = MeterReading::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Utilities';

    protected static ?string $moduleKey = 'utilities';

    protected static ?string $permission = 'utilities.meterReading';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'utilities')
            && $user->permits((static::$permission ?? 'utilities.meterReading').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('utility_meter_id')->label('Utility Meter Id')->relationship('utilityMeter', 'name')->searchable()->preload(),
            DateTimePicker::make('reading_at')->label('Reading At'),
            TextInput::make('reading')->label('Reading')->numeric(),
            TextInput::make('consumption')->label('Consumption')->numeric(),
            TextInput::make('cost')->label('Cost')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('utility_meter_id')->label('Utility Meter Id')->sortable()->toggleable(),
            TextColumn::make('reading_at')->label('Reading At')->searchable()->sortable()->toggleable(),
            TextColumn::make('reading')->label('Reading')->searchable()->sortable()->toggleable(),
            TextColumn::make('consumption')->label('Consumption')->searchable()->sortable()->toggleable(),
            TextColumn::make('cost')->label('Cost')->searchable()->sortable()->toggleable(),
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
            'index' => ListMeterReadings::route('/'),
            'create' => CreateMeterReading::route('/create'),
            'edit' => EditMeterReading::route('/{record}/edit'),
        ];
    }
}
