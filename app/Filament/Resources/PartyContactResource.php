<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PartyContactResource\Pages\CreatePartyContact;
use App\Filament\Resources\PartyContactResource\Pages\EditPartyContact;
use App\Filament\Resources\PartyContactResource\Pages\ListPartyContacts;
use App\Modules\Commercial\Models\PartyContact;
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

class PartyContactResource extends Resource
{
    protected static ?string $model = PartyContact::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?string $moduleKey = 'crm';

    protected static ?string $permission = 'crm.partyContact';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'crm')
            && $user->permits((static::$permission ?? 'crm.partyContact').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('party_id')->label('Party Id')->relationship('party', 'name')->searchable()->preload(),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('email')->label('Email')->maxLength(255),
            TextInput::make('phone')->label('Phone')->maxLength(255),
            TextInput::make('role')->label('Role')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('party_id')->label('Party Id')->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('email')->label('Email')->searchable()->sortable()->toggleable(),
            TextColumn::make('phone')->label('Phone')->searchable()->sortable()->toggleable(),
            TextColumn::make('role')->label('Role')->searchable()->sortable()->toggleable(),
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
            'index' => ListPartyContacts::route('/'),
            'create' => CreatePartyContact::route('/create'),
            'edit' => EditPartyContact::route('/{record}/edit'),
        ];
    }
}
