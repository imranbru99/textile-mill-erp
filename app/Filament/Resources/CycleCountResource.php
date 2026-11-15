<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CycleCountResource\Pages\CreateCycleCount;
use App\Filament\Resources\CycleCountResource\Pages\EditCycleCount;
use App\Filament\Resources\CycleCountResource\Pages\ListCycleCounts;
use App\Modules\Inventory\Models\CycleCount;
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

class CycleCountResource extends Resource
{
    protected static ?string $model = CycleCount::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?string $moduleKey = 'inventory';

    protected static ?string $permission = 'inventory.cycleCount';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'inventory')
            && $user->permits((static::$permission ?? 'inventory.cycleCount').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('warehouse_id')->label('Warehouse Id')->relationship('warehouse', 'name')->searchable()->preload(),
            DatePicker::make('counted_on')->label('Counted On'),
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
            TextColumn::make('warehouse_id')->label('Warehouse Id')->sortable()->toggleable(),
            TextColumn::make('counted_on')->label('Counted On')->searchable()->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable()->toggleable(),
            TextColumn::make('document_date')->label('Document Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('currency_code')->label('Currency Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('exchange_rate')->label('Exchange Rate')->searchable()->sortable()->toggleable(),
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
            'index' => ListCycleCounts::route('/'),
            'create' => CreateCycleCount::route('/create'),
            'edit' => EditCycleCount::route('/{record}/edit'),
        ];
    }
}
