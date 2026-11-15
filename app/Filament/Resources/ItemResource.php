<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages\CreateItem;
use App\Filament\Resources\ItemResource\Pages\EditItem;
use App\Filament\Resources\ItemResource\Pages\ListItems;
use App\Modules\Master\Models\Item;
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

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $moduleKey = 'master';

    protected static ?string $permission = 'master.item';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'master')
            && $user->permits((static::$permission ?? 'master.item').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->maxLength(255),
            Select::make('item_type')->label('Item Type')->options(['raw_material' => 'raw material', 'yarn' => 'yarn', 'greige' => 'greige', 'finished_fabric' => 'finished fabric', 'dye' => 'dye', 'chemical' => 'chemical', 'auxiliary' => 'auxiliary', 'packing' => 'packing', 'spare' => 'spare', 'consumable' => 'consumable', 'fuel' => 'fuel', 'wip' => 'wip', 'scrap' => 'scrap'])->native(false),
            Select::make('uom_id')->label('Uom Id')->relationship('uom', 'name')->searchable()->preload(),
            TextInput::make('count_ne')->label('Count Ne')->numeric(),
            TextInput::make('ply')->label('Ply')->numeric(),
            TextInput::make('tpi')->label('Tpi')->numeric(),
            TextInput::make('gsm')->label('Gsm')->numeric(),
            TextInput::make('width_inch')->label('Width Inch')->numeric(),
            TextInput::make('epi')->label('Epi')->numeric(),
            TextInput::make('ppi')->label('Ppi')->numeric(),
            TextInput::make('construction')->label('Construction')->maxLength(255),
            TextInput::make('weave')->label('Weave')->maxLength(255),
            TextInput::make('color')->label('Color')->maxLength(255),
            TextInput::make('shade_code')->label('Shade Code')->maxLength(255),
            TextInput::make('hazard_class')->label('Hazard Class')->maxLength(255),
            TextInput::make('shelf_life_days')->label('Shelf Life Days')->numeric(),
            TextInput::make('reorder_level')->label('Reorder Level')->numeric(),
            TextInput::make('min_qty')->label('Min Qty')->numeric(),
            TextInput::make('max_qty')->label('Max Qty')->numeric(),
            TextInput::make('standard_cost')->label('Standard Cost')->numeric(),
            TextInput::make('barcode')->label('Barcode')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('slug')->label('Slug')->searchable()->sortable()->toggleable(),
            TextColumn::make('item_type')->label('Item Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('uom_id')->label('Uom Id')->sortable()->toggleable(),
            TextColumn::make('count_ne')->label('Count Ne')->searchable()->sortable()->toggleable(),
            TextColumn::make('ply')->label('Ply')->searchable()->sortable()->toggleable(),
            TextColumn::make('tpi')->label('Tpi')->searchable()->sortable()->toggleable(),
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
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
