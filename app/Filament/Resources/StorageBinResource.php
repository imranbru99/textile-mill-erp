<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\StorageBinResource\Pages\CreateStorageBin;
use App\Filament\Resources\StorageBinResource\Pages\EditStorageBin;
use App\Filament\Resources\StorageBinResource\Pages\ListStorageBins;
use App\Modules\Inventory\Models\StorageBin;
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

class StorageBinResource extends Resource
{
    protected static ?string $model = StorageBin::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $moduleKey = 'inventory';

    protected static ?string $permission = 'inventory.storageBin';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'inventory')
            && $user->permits((static::$permission ?? 'inventory.storageBin').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('warehouse_id')->label('Warehouse Id')->relationship('warehouse', 'name')->searchable()->preload(),
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('warehouse_id')->label('Warehouse Id')->sortable()->toggleable(),
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
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
            'index' => ListStorageBins::route('/'),
            'create' => CreateStorageBin::route('/create'),
            'edit' => EditStorageBin::route('/{record}/edit'),
        ];
    }
}
