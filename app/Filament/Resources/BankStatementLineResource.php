<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BankStatementLineResource\Pages\CreateBankStatementLine;
use App\Filament\Resources\BankStatementLineResource\Pages\EditBankStatementLine;
use App\Filament\Resources\BankStatementLineResource\Pages\ListBankStatementLines;
use App\Modules\Finance\Models\BankStatementLine;
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

class BankStatementLineResource extends Resource
{
    protected static ?string $model = BankStatementLine::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.bankStatementLine';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.bankStatementLine').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('bank_account_id')->label('Bank Account Id')->relationship('bankAccount', 'name')->searchable()->preload(),
            DatePicker::make('txn_date')->label('Txn Date'),
            TextInput::make('description')->label('Description')->maxLength(255),
            TextInput::make('amount')->label('Amount')->numeric(),
            Toggle::make('matched')->label('Matched'),
            Select::make('journal_entry_id')->label('Journal Entry Id')->relationship('journalEntry', 'number')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('bank_account_id')->label('Bank Account Id')->sortable()->toggleable(),
            TextColumn::make('txn_date')->label('Txn Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('description')->label('Description')->searchable()->sortable()->toggleable(),
            TextColumn::make('amount')->label('Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('matched')->label('Matched')->searchable()->sortable()->toggleable(),
            TextColumn::make('journal_entry_id')->label('Journal Entry Id')->sortable()->toggleable(),
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
            'index' => ListBankStatementLines::route('/'),
            'create' => CreateBankStatementLine::route('/create'),
            'edit' => EditBankStatementLine::route('/{record}/edit'),
        ];
    }
}
