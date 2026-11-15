<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SpinningProductionResource\Pages\CreateSpinningProduction;
use App\Filament\Resources\SpinningProductionResource\Pages\EditSpinningProduction;
use App\Filament\Resources\SpinningProductionResource\Pages\ListSpinningProductions;
use App\Modules\Spinning\Models\SpinningProduction;
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

class SpinningProductionResource extends Resource
{
    protected static ?string $model = SpinningProduction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Spinning';

    protected static ?string $moduleKey = 'spinning';

    protected static ?string $permission = 'spinning.spinningProduction';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'spinning')
            && $user->permits((static::$permission ?? 'spinning.spinningProduction').'.view');
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
            Select::make('stage')->label('Stage')->options(['blowroom' => 'blowroom', 'carding' => 'carding', 'drawing' => 'drawing', 'combing' => 'combing', 'roving' => 'roving', 'ring' => 'ring', 'winding' => 'winding', 'packing' => 'packing'])->native(false),
            Select::make('shift_id')->label('Shift Id')->relationship('shift', 'name')->searchable()->preload(),
            DatePicker::make('production_date')->label('Production Date'),
            TextInput::make('input_kg')->label('Input Kg')->numeric(),
            TextInput::make('output_kg')->label('Output Kg')->numeric(),
            TextInput::make('waste_kg')->label('Waste Kg')->numeric(),
            TextInput::make('efficiency')->label('Efficiency')->numeric(),
            TextInput::make('spindle_rpm')->label('Spindle Rpm')->numeric(),
            TextInput::make('tpi')->label('Tpi')->numeric(),
            TextInput::make('count_ne')->label('Count Ne')->numeric(),
            TextInput::make('hours')->label('Hours')->numeric(),
            Select::make('yarn_lot_id')->label('Yarn Lot Id')->relationship('yarnLot', 'lot_no')->searchable()->preload(),
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
            TextColumn::make('stage')->label('Stage')->searchable()->sortable()->toggleable(),
            TextColumn::make('shift_id')->label('Shift Id')->sortable()->toggleable(),
            TextColumn::make('production_date')->label('Production Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('input_kg')->label('Input Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('output_kg')->label('Output Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('waste_kg')->label('Waste Kg')->searchable()->sortable()->toggleable(),
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
            'index' => ListSpinningProductions::route('/'),
            'create' => CreateSpinningProduction::route('/create'),
            'edit' => EditSpinningProduction::route('/{record}/edit'),
        ];
    }
}
