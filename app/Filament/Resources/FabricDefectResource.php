<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FabricDefectResource\Pages\CreateFabricDefect;
use App\Filament\Resources\FabricDefectResource\Pages\EditFabricDefect;
use App\Filament\Resources\FabricDefectResource\Pages\ListFabricDefects;
use App\Modules\Quality\Models\FabricDefect;
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

class FabricDefectResource extends Resource
{
    protected static ?string $model = FabricDefect::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

    protected static ?string $permission = 'quality.fabricDefect';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'quality')
            && $user->permits((static::$permission ?? 'quality.fabricDefect').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('fabric_roll_id')->label('Fabric Roll Id')->relationship('fabricRoll', 'roll_no')->searchable()->preload(),
            TextInput::make('defect_code')->label('Defect Code')->required()->maxLength(255),
            TextInput::make('points')->label('Points')->numeric(),
            TextInput::make('position_meter')->label('Position Meter')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('fabric_roll_id')->label('Fabric Roll Id')->sortable()->toggleable(),
            TextColumn::make('defect_code')->label('Defect Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('points')->label('Points')->searchable()->sortable()->toggleable(),
            TextColumn::make('position_meter')->label('Position Meter')->searchable()->sortable()->toggleable(),
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
            'index' => ListFabricDefects::route('/'),
            'create' => CreateFabricDefect::route('/create'),
            'edit' => EditFabricDefect::route('/{record}/edit'),
        ];
    }
}
