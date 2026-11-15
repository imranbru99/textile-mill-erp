<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AccountMappingResource\Pages\CreateAccountMapping;
use App\Filament\Resources\AccountMappingResource\Pages\EditAccountMapping;
use App\Filament\Resources\AccountMappingResource\Pages\ListAccountMappings;
use App\Modules\Finance\Models\AccountMapping;
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

class AccountMappingResource extends Resource
{
    protected static ?string $model = AccountMapping::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.accountMapping';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.accountMapping').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('event_key')->label('Event Key')->required()->maxLength(255),
            Select::make('debit_account_id')->label('Debit Account Id')->relationship('debitAccount', 'name')->searchable()->preload(),
            Select::make('credit_account_id')->label('Credit Account Id')->relationship('creditAccount', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('event_key')->label('Event Key')->searchable()->sortable()->toggleable(),
            TextColumn::make('debit_account_id')->label('Debit Account Id')->sortable()->toggleable(),
            TextColumn::make('credit_account_id')->label('Credit Account Id')->sortable()->toggleable(),
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
            'index' => ListAccountMappings::route('/'),
            'create' => CreateAccountMapping::route('/create'),
            'edit' => EditAccountMapping::route('/{record}/edit'),
        ];
    }
}
