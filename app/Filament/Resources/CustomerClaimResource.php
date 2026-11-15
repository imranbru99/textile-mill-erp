<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerClaimResource\Pages\CreateCustomerClaim;
use App\Filament\Resources\CustomerClaimResource\Pages\EditCustomerClaim;
use App\Filament\Resources\CustomerClaimResource\Pages\ListCustomerClaims;
use App\Modules\Quality\Models\CustomerClaim;
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

class CustomerClaimResource extends Resource
{
    protected static ?string $model = CustomerClaim::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

    protected static ?string $permission = 'quality.customerClaim';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'quality')
            && $user->permits((static::$permission ?? 'quality.customerClaim').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('party_id')->label('Party Id')->relationship('party', 'name')->searchable()->preload(),
            Select::make('sales_order_id')->label('Sales Order Id')->relationship('salesOrder', 'number')->searchable()->preload(),
            Select::make('item_lot_id')->label('Item Lot Id')->relationship('itemLot', 'lot_no')->searchable()->preload(),
            TextInput::make('qty')->label('Qty')->numeric(),
            TextInput::make('claim_amount')->label('Claim Amount')->numeric(),
            TextInput::make('resolution')->label('Resolution')->maxLength(255),
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
            TextColumn::make('party_id')->label('Party Id')->sortable()->toggleable(),
            TextColumn::make('sales_order_id')->label('Sales Order Id')->sortable()->toggleable(),
            TextColumn::make('item_lot_id')->label('Item Lot Id')->sortable()->toggleable(),
            TextColumn::make('qty')->label('Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('claim_amount')->label('Claim Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('resolution')->label('Resolution')->searchable()->sortable()->toggleable(),
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
            'index' => ListCustomerClaims::route('/'),
            'create' => CreateCustomerClaim::route('/create'),
            'edit' => EditCustomerClaim::route('/{record}/edit'),
        ];
    }
}
