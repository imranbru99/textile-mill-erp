<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DyeBatchResource\Pages\CreateDyeBatch;
use App\Filament\Resources\DyeBatchResource\Pages\EditDyeBatch;
use App\Filament\Resources\DyeBatchResource\Pages\ListDyeBatchs;
use App\Modules\Dyeing\Models\DyeBatch;
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

class DyeBatchResource extends Resource
{
    protected static ?string $model = DyeBatch::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Wet Processing';

    protected static ?string $moduleKey = 'dyeing';

    protected static ?string $permission = 'dyeing.dyeBatch';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'dyeing')
            && $user->permits((static::$permission ?? 'dyeing.dyeBatch').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('recipe_id')->label('Recipe Id')->relationship('recipe', 'name')->searchable()->preload(),
            Select::make('machine_id')->label('Machine Id')->relationship('machine', 'name')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('weight_kg')->label('Weight Kg')->numeric(),
            TextInput::make('liquor_ratio')->label('Liquor Ratio')->numeric(),
            TextInput::make('shade_name')->label('Shade Name')->maxLength(255),
            Toggle::make('is_redye')->label('Is Redye'),
            Toggle::make('rft')->label('Rft'),
            TextInput::make('standard_cost')->label('Standard Cost')->numeric(),
            TextInput::make('actual_cost')->label('Actual Cost')->numeric(),
            TextInput::make('number')->label('Number')->required()->maxLength(255),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            DatePicker::make('document_date')->label('Document Date'),
            TextInput::make('currency_code')->label('Currency Code')->required()->maxLength(255),
            TextInput::make('exchange_rate')->label('Exchange Rate')->numeric(),
            Textarea::make('notes')->label('Notes')->columnSpanFull(),
            TextInput::make('total_amount')->label('Total Amount')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('recipe_id')->label('Recipe Id')->sortable()->toggleable(),
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('weight_kg')->label('Weight Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('liquor_ratio')->label('Liquor Ratio')->searchable()->sortable()->toggleable(),
            TextColumn::make('shade_name')->label('Shade Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('is_redye')->label('Is Redye')->searchable()->sortable()->toggleable(),
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
            'index' => ListDyeBatchs::route('/'),
            'create' => CreateDyeBatch::route('/create'),
            'edit' => EditDyeBatch::route('/{record}/edit'),
        ];
    }
}
