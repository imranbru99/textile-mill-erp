<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ChequeResource\Pages\CreateCheque;
use App\Filament\Resources\ChequeResource\Pages\EditCheque;
use App\Filament\Resources\ChequeResource\Pages\ListCheques;
use App\Modules\Finance\Models\Cheque;
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

class ChequeResource extends Resource
{
    protected static ?string $model = Cheque::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.cheque';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.cheque').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('bank_account_id')->label('Bank Account Id')->relationship('bankAccount', 'name')->searchable()->preload(),
            Select::make('party_id')->label('Party Id')->relationship('party', 'name')->searchable()->preload(),
            TextInput::make('cheque_no')->label('Cheque No')->required()->maxLength(255),
            DatePicker::make('cheque_date')->label('Cheque Date'),
            TextInput::make('amount')->label('Amount')->numeric(),
            Select::make('kind')->label('Kind')->options(['vat' => 'vat', 'ait' => 'ait', 'tds' => 'tds', 'sd' => 'sd', 'raw_cotton' => 'raw cotton', 'yarn' => 'yarn', 'greige' => 'greige', 'finished' => 'finished', 'dye_chemical' => 'dye chemical', 'spare' => 'spare', 'scrap' => 'scrap', 'bonded' => 'bonded', 'preventive' => 'preventive', 'breakdown' => 'breakdown', 'received' => 'received', 'issued' => 'issued'])->native(false),
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
            TextColumn::make('bank_account_id')->label('Bank Account Id')->sortable()->toggleable(),
            TextColumn::make('party_id')->label('Party Id')->sortable()->toggleable(),
            TextColumn::make('cheque_no')->label('Cheque No')->searchable()->sortable()->toggleable(),
            TextColumn::make('cheque_date')->label('Cheque Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('amount')->label('Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('kind')->label('Kind')->searchable()->sortable()->toggleable(),
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
            'index' => ListCheques::route('/'),
            'create' => CreateCheque::route('/create'),
            'edit' => EditCheque::route('/{record}/edit'),
        ];
    }
}
