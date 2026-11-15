<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages\CreateBankAccount;
use App\Filament\Resources\BankAccountResource\Pages\EditBankAccount;
use App\Filament\Resources\BankAccountResource\Pages\ListBankAccounts;
use App\Modules\Finance\Models\BankAccount;
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

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.bankAccount';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.bankAccount').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('bank_id')->label('Bank Id')->relationship('bank', 'name')->searchable()->preload(),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('account_no')->label('Account No')->required()->maxLength(255),
            TextInput::make('currency_code')->label('Currency Code')->required()->maxLength(255),
            Select::make('gl_account_id')->label('Gl Account Id')->relationship('glAccount', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('bank_id')->label('Bank Id')->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('account_no')->label('Account No')->searchable()->sortable()->toggleable(),
            TextColumn::make('currency_code')->label('Currency Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('gl_account_id')->label('Gl Account Id')->sortable()->toggleable(),
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
            'index' => ListBankAccounts::route('/'),
            'create' => CreateBankAccount::route('/create'),
            'edit' => EditBankAccount::route('/{record}/edit'),
        ];
    }
}
