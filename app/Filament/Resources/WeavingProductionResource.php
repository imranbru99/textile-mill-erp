<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WeavingProductionResource\Pages\CreateWeavingProduction;
use App\Filament\Resources\WeavingProductionResource\Pages\EditWeavingProduction;
use App\Filament\Resources\WeavingProductionResource\Pages\ListWeavingProductions;
use App\Modules\Weaving\Models\WeavingProduction;
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

class WeavingProductionResource extends Resource
{
    protected static ?string $model = WeavingProduction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|\UnitEnum|null $navigationGroup = 'Weaving';

    protected static ?string $moduleKey = 'weaving';

    protected static ?string $permission = 'weaving.weavingProduction';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'weaving')
            && $user->permits((static::$permission ?? 'weaving.weavingProduction').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('machine_id')->label('Machine Id')->relationship('machine', 'name')->searchable()->preload(),
            Select::make('shift_id')->label('Shift Id')->relationship('shift', 'name')->searchable()->preload(),
            DatePicker::make('production_date')->label('Production Date'),
            TextInput::make('rpm')->label('Rpm')->numeric(),
            TextInput::make('ppi')->label('Ppi')->numeric(),
            TextInput::make('minutes')->label('Minutes')->numeric(),
            TextInput::make('efficiency')->label('Efficiency')->numeric(),
            TextInput::make('meters')->label('Meters')->numeric(),
            TextInput::make('warp_stops')->label('Warp Stops')->numeric(),
            TextInput::make('weft_stops')->label('Weft Stops')->numeric(),
            TextInput::make('mechanical_stops')->label('Mechanical Stops')->numeric(),
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
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('shift_id')->label('Shift Id')->sortable()->toggleable(),
            TextColumn::make('production_date')->label('Production Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('rpm')->label('Rpm')->searchable()->sortable()->toggleable(),
            TextColumn::make('ppi')->label('Ppi')->searchable()->sortable()->toggleable(),
            TextColumn::make('minutes')->label('Minutes')->searchable()->sortable()->toggleable(),
            TextColumn::make('efficiency')->label('Efficiency')->searchable()->sortable()->toggleable(),
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
            'index' => ListWeavingProductions::route('/'),
            'create' => CreateWeavingProduction::route('/create'),
            'edit' => EditWeavingProduction::route('/{record}/edit'),
        ];
    }
}
