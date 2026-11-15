<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ItemLotResource\Pages\CreateItemLot;
use App\Filament\Resources\ItemLotResource\Pages\EditItemLot;
use App\Filament\Resources\ItemLotResource\Pages\ListItemLots;
use App\Modules\Inventory\Models\ItemLot;
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

class ItemLotResource extends Resource
{
    protected static ?string $model = ItemLot::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $moduleKey = 'inventory';

    protected static ?string $permission = 'inventory.itemLot';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'inventory')
            && $user->permits((static::$permission ?? 'inventory.itemLot').'.view');
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
            Select::make('warehouse_id')->label('Warehouse Id')->relationship('warehouse', 'name')->searchable()->preload(),
            TextInput::make('lot_no')->label('Lot No')->required()->maxLength(255),
            TextInput::make('qty_on_hand')->label('Qty On Hand')->numeric(),
            TextInput::make('qty_reserved')->label('Qty Reserved')->numeric(),
            TextInput::make('unit_cost')->label('Unit Cost')->numeric(),
            DateTimePicker::make('received_at')->label('Received At'),
            DatePicker::make('expiry_date')->label('Expiry Date'),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            TextInput::make('barcode')->label('Barcode')->maxLength(255),
            Select::make('supplier_id')->label('Supplier Id')->relationship('supplier', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('warehouse_id')->label('Warehouse Id')->sortable()->toggleable(),
            TextColumn::make('lot_no')->label('Lot No')->searchable()->sortable()->toggleable(),
            TextColumn::make('qty_on_hand')->label('Qty On Hand')->searchable()->sortable()->toggleable(),
            TextColumn::make('qty_reserved')->label('Qty Reserved')->searchable()->sortable()->toggleable(),
            TextColumn::make('unit_cost')->label('Unit Cost')->searchable()->sortable()->toggleable(),
            TextColumn::make('received_at')->label('Received At')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'posted' => 'Posted', 'closed' => 'Closed']),
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
            'index' => ListItemLots::route('/'),
            'create' => CreateItemLot::route('/create'),
            'edit' => EditItemLot::route('/{record}/edit'),
        ];
    }
}
