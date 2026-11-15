<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MrpRequirementResource\Pages\CreateMrpRequirement;
use App\Filament\Resources\MrpRequirementResource\Pages\EditMrpRequirement;
use App\Filament\Resources\MrpRequirementResource\Pages\ListMrpRequirements;
use App\Modules\Planning\Models\MrpRequirement;
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

class MrpRequirementResource extends Resource
{
    protected static ?string $model = MrpRequirement::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Planning';

    protected static ?string $moduleKey = 'planning';

    protected static ?string $permission = 'planning.mrpRequirement';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'planning')
            && $user->permits((static::$permission ?? 'planning.mrpRequirement').'.view');
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
            TextInput::make('required_qty')->label('Required Qty')->numeric(),
            TextInput::make('available_qty')->label('Available Qty')->numeric(),
            TextInput::make('shortage_qty')->label('Shortage Qty')->numeric(),
            DatePicker::make('needed_by')->label('Needed By'),
            Select::make('purchase_requisition_id')->label('Purchase Requisition Id')->relationship('purchaseRequisition', 'number')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('required_qty')->label('Required Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('available_qty')->label('Available Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('shortage_qty')->label('Shortage Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('needed_by')->label('Needed By')->searchable()->sortable()->toggleable(),
            TextColumn::make('purchase_requisition_id')->label('Purchase Requisition Id')->sortable()->toggleable(),
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
            'index' => ListMrpRequirements::route('/'),
            'create' => CreateMrpRequirement::route('/create'),
            'edit' => EditMrpRequirement::route('/{record}/edit'),
        ];
    }
}
