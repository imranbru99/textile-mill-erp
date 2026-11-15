<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\GatePassResource\Pages\CreateGatePass;
use App\Filament\Resources\GatePassResource\Pages\EditGatePass;
use App\Filament\Resources\GatePassResource\Pages\ListGatePasss;
use App\Modules\Gate\Models\GatePass;
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

class GatePassResource extends Resource
{
    protected static ?string $model = GatePass::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Gate & Security';

    protected static ?string $moduleKey = 'gate';

    protected static ?string $permission = 'gate.gatePass';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'gate')
            && $user->permits((static::$permission ?? 'gate.gatePass').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('vehicle_no')->label('Vehicle No')->maxLength(255),
            TextInput::make('driver_name')->label('Driver Name')->maxLength(255),
            Select::make('party_id')->label('Party Id')->relationship('party', 'name')->searchable()->preload(),
            Select::make('pass_type')->label('Pass Type')->options(['returnable' => 'returnable', 'non_returnable' => 'non returnable'])->native(false),
            DatePicker::make('return_due')->label('Return Due'),
            TextInput::make('doc_weight')->label('Doc Weight')->numeric(),
            TextInput::make('bridge_weight')->label('Bridge Weight')->numeric(),
            Select::make('direction')->label('Direction')->options(['in' => 'in', 'out' => 'out'])->native(false),
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
            TextColumn::make('vehicle_no')->label('Vehicle No')->searchable()->sortable()->toggleable(),
            TextColumn::make('driver_name')->label('Driver Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('party_id')->label('Party Id')->sortable()->toggleable(),
            TextColumn::make('pass_type')->label('Pass Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('return_due')->label('Return Due')->searchable()->sortable()->toggleable(),
            TextColumn::make('doc_weight')->label('Doc Weight')->searchable()->sortable()->toggleable(),
            TextColumn::make('bridge_weight')->label('Bridge Weight')->searchable()->sortable()->toggleable(),
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
            'index' => ListGatePasss::route('/'),
            'create' => CreateGatePass::route('/create'),
            'edit' => EditGatePass::route('/{record}/edit'),
        ];
    }
}
