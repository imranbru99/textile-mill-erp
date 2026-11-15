<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityMeterResource\Pages\CreateUtilityMeter;
use App\Filament\Resources\UtilityMeterResource\Pages\EditUtilityMeter;
use App\Filament\Resources\UtilityMeterResource\Pages\ListUtilityMeters;
use App\Modules\Utilities\Models\UtilityMeter;
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

class UtilityMeterResource extends Resource
{
    protected static ?string $model = UtilityMeter::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|\UnitEnum|null $navigationGroup = 'Utilities';

    protected static ?string $moduleKey = 'utilities';

    protected static ?string $permission = 'utilities.utilityMeter';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'utilities')
            && $user->permits((static::$permission ?? 'utilities.utilityMeter').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            Select::make('utility')->label('Utility')->options(['power' => 'power', 'gas' => 'gas', 'water' => 'water', 'steam' => 'steam', 'air' => 'air'])->native(false),
            TextInput::make('uom')->label('Uom')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('utility')->label('Utility')->searchable()->sortable()->toggleable(),
            TextColumn::make('uom')->label('Uom')->searchable()->sortable()->toggleable(),
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
            'index' => ListUtilityMeters::route('/'),
            'create' => CreateUtilityMeter::route('/create'),
            'edit' => EditUtilityMeter::route('/{record}/edit'),
        ];
    }
}
