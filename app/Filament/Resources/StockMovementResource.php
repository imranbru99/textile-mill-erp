<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages\CreateStockMovement;
use App\Filament\Resources\StockMovementResource\Pages\EditStockMovement;
use App\Filament\Resources\StockMovementResource\Pages\ListStockMovements;
use App\Modules\Inventory\Models\StockMovement;
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

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $moduleKey = 'inventory';

    protected static ?string $permission = 'inventory.stockMovement';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'inventory')
            && $user->permits((static::$permission ?? 'inventory.stockMovement').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            Select::make('item_lot_id')->label('Item Lot Id')->relationship('itemLot', 'lot_no')->searchable()->preload(),
            Select::make('warehouse_id')->label('Warehouse Id')->relationship('warehouse', 'name')->searchable()->preload(),
            Select::make('direction')->label('Direction')->options(['in' => 'in', 'out' => 'out'])->native(false),
            TextInput::make('qty')->label('Qty')->numeric(),
            TextInput::make('unit_cost')->label('Unit Cost')->numeric(),
            TextInput::make('movement_type')->label('Movement Type')->required()->maxLength(255),
            TextInput::make('reference_type')->label('Reference Type')->maxLength(255),
            TextInput::make('reference_id')->label('Reference Id')->numeric(),
            TextInput::make('balance_after')->label('Balance After')->numeric(),
            DateTimePicker::make('transacted_at')->label('Transacted At'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('item_lot_id')->label('Item Lot Id')->sortable()->toggleable(),
            TextColumn::make('warehouse_id')->label('Warehouse Id')->sortable()->toggleable(),
            TextColumn::make('direction')->label('Direction')->searchable()->sortable()->toggleable(),
            TextColumn::make('qty')->label('Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('unit_cost')->label('Unit Cost')->searchable()->sortable()->toggleable(),
            TextColumn::make('movement_type')->label('Movement Type')->searchable()->sortable()->toggleable(),
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
            'index' => ListStockMovements::route('/'),
            'create' => CreateStockMovement::route('/create'),
            'edit' => EditStockMovement::route('/{record}/edit'),
        ];
    }
}
