<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PartyResource\Pages\CreateParty;
use App\Filament\Resources\PartyResource\Pages\EditParty;
use App\Filament\Resources\PartyResource\Pages\ListPartys;
use App\Modules\Commercial\Models\Party;
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

class PartyResource extends Resource
{
    protected static ?string $model = Party::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?string $moduleKey = 'crm';

    protected static ?string $permission = 'crm.party';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'crm')
            && $user->permits((static::$permission ?? 'crm.party').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            Select::make('party_type')->label('Party Type')->options(['buyer' => 'buyer', 'supplier' => 'supplier', 'both' => 'both'])->native(false),
            TextInput::make('country')->label('Country')->required()->maxLength(255),
            TextInput::make('currency_code')->label('Currency Code')->required()->maxLength(255),
            TextInput::make('credit_limit')->label('Credit Limit')->numeric(),
            TextInput::make('rating')->label('Rating')->numeric(),
            Select::make('payment_term_id')->label('Payment Term Id')->relationship('paymentTerm', 'name')->searchable()->preload(),
            Toggle::make('blacklisted')->label('Blacklisted'),
            TextInput::make('phone')->label('Phone')->maxLength(255),
            TextInput::make('email')->label('Email')->maxLength(255),
            Textarea::make('address')->label('Address')->columnSpanFull(),
            TextInput::make('bin_vat')->label('Bin Vat')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('party_type')->label('Party Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('country')->label('Country')->searchable()->sortable()->toggleable(),
            TextColumn::make('currency_code')->label('Currency Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('credit_limit')->label('Credit Limit')->searchable()->sortable()->toggleable(),
            TextColumn::make('rating')->label('Rating')->searchable()->sortable()->toggleable(),
            TextColumn::make('payment_term_id')->label('Payment Term Id')->sortable()->toggleable(),
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
            'index' => ListPartys::route('/'),
            'create' => CreateParty::route('/create'),
            'edit' => EditParty::route('/{record}/edit'),
        ];
    }
}
