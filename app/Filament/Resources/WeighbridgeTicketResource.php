<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WeighbridgeTicketResource\Pages\CreateWeighbridgeTicket;
use App\Filament\Resources\WeighbridgeTicketResource\Pages\EditWeighbridgeTicket;
use App\Filament\Resources\WeighbridgeTicketResource\Pages\ListWeighbridgeTickets;
use App\Modules\Gate\Models\WeighbridgeTicket;
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

class WeighbridgeTicketResource extends Resource
{
    protected static ?string $model = WeighbridgeTicket::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Gate & Security';

    protected static ?string $moduleKey = 'gate';

    protected static ?string $permission = 'gate.weighbridgeTicket';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'gate')
            && $user->permits((static::$permission ?? 'gate.weighbridgeTicket').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('vehicle_no')->label('Vehicle No')->required()->maxLength(255),
            TextInput::make('gross')->label('Gross')->numeric(),
            TextInput::make('tare')->label('Tare')->numeric(),
            TextInput::make('net')->label('Net')->numeric(),
            Select::make('gate_pass_id')->label('Gate Pass Id')->relationship('gatePass', 'number')->searchable()->preload(),
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
            TextColumn::make('gross')->label('Gross')->searchable()->sortable()->toggleable(),
            TextColumn::make('tare')->label('Tare')->searchable()->sortable()->toggleable(),
            TextColumn::make('net')->label('Net')->searchable()->sortable()->toggleable(),
            TextColumn::make('gate_pass_id')->label('Gate Pass Id')->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable()->toggleable(),
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
            'index' => ListWeighbridgeTickets::route('/'),
            'create' => CreateWeighbridgeTicket::route('/create'),
            'edit' => EditWeighbridgeTicket::route('/{record}/edit'),
        ];
    }
}
