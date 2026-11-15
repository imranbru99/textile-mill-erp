<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FuelLogResource\Pages\CreateFuelLog;
use App\Filament\Resources\FuelLogResource\Pages\EditFuelLog;
use App\Filament\Resources\FuelLogResource\Pages\ListFuelLogs;
use App\Modules\Utilities\Models\FuelLog;
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

class FuelLogResource extends Resource
{
    protected static ?string $model = FuelLog::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Utilities';

    protected static ?string $moduleKey = 'utilities';

    protected static ?string $permission = 'utilities.fuelLog';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'utilities')
            && $user->permits((static::$permission ?? 'utilities.fuelLog').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            DatePicker::make('log_date')->label('Log Date'),
            TextInput::make('liters')->label('Liters')->numeric(),
            TextInput::make('amount')->label('Amount')->numeric(),
            TextInput::make('generator')->label('Generator')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('log_date')->label('Log Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('liters')->label('Liters')->searchable()->sortable()->toggleable(),
            TextColumn::make('amount')->label('Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('generator')->label('Generator')->searchable()->sortable()->toggleable(),
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
            'index' => ListFuelLogs::route('/'),
            'create' => CreateFuelLog::route('/create'),
            'edit' => EditFuelLog::route('/{record}/edit'),
        ];
    }
}
