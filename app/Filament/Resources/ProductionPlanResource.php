<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionPlanResource\Pages\CreateProductionPlan;
use App\Filament\Resources\ProductionPlanResource\Pages\EditProductionPlan;
use App\Filament\Resources\ProductionPlanResource\Pages\ListProductionPlans;
use App\Modules\Planning\Models\ProductionPlan;
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

class ProductionPlanResource extends Resource
{
    protected static ?string $model = ProductionPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Planning';

    protected static ?string $moduleKey = 'planning';

    protected static ?string $permission = 'planning.productionPlan';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'planning')
            && $user->permits((static::$permission ?? 'planning.productionPlan').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('sales_order_id')->label('Sales Order Id')->relationship('salesOrder', 'number')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('qty')->label('Qty')->numeric(),
            DatePicker::make('start_date')->label('Start Date'),
            DatePicker::make('end_date')->label('End Date'),
            DatePicker::make('promise_date')->label('Promise Date'),
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
            TextColumn::make('sales_order_id')->label('Sales Order Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('qty')->label('Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('start_date')->label('Start Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('end_date')->label('End Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('promise_date')->label('Promise Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
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
            'index' => ListProductionPlans::route('/'),
            'create' => CreateProductionPlan::route('/create'),
            'edit' => EditProductionPlan::route('/{record}/edit'),
        ];
    }
}
