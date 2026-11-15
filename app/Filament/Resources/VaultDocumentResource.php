<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\VaultDocumentResource\Pages\CreateVaultDocument;
use App\Filament\Resources\VaultDocumentResource\Pages\EditVaultDocument;
use App\Filament\Resources\VaultDocumentResource\Pages\ListVaultDocuments;
use App\Modules\Projects\Models\VaultDocument;
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

class VaultDocumentResource extends Resource
{
    protected static ?string $model = VaultDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|\UnitEnum|null $navigationGroup = 'Projects';

    protected static ?string $moduleKey = 'projects';

    protected static ?string $permission = 'projects.vaultDocument';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'projects')
            && $user->permits((static::$permission ?? 'projects.vaultDocument').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Title')->required()->maxLength(255),
            Select::make('category')->label('Category')->options(['permanent' => 'permanent', 'contract' => 'contract', 'piece_rate' => 'piece rate', 'daily' => 'daily', 'agency' => 'agency', 'license' => 'license', 'test' => 'test', 'safety' => 'safety', 'commercial' => 'commercial'])->native(false),
            TextInput::make('reference_no')->label('Reference No')->maxLength(255),
            DatePicker::make('issued_on')->label('Issued On'),
            DatePicker::make('expires_on')->label('Expires On'),
            TextInput::make('path')->label('Path')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('title')->label('Title')->searchable()->sortable()->toggleable(),
            TextColumn::make('category')->label('Category')->searchable()->sortable()->toggleable(),
            TextColumn::make('reference_no')->label('Reference No')->searchable()->sortable()->toggleable(),
            TextColumn::make('issued_on')->label('Issued On')->searchable()->sortable()->toggleable(),
            TextColumn::make('expires_on')->label('Expires On')->searchable()->sortable()->toggleable(),
            TextColumn::make('path')->label('Path')->searchable()->sortable()->toggleable(),
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
            'index' => ListVaultDocuments::route('/'),
            'create' => CreateVaultDocument::route('/create'),
            'edit' => EditVaultDocument::route('/{record}/edit'),
        ];
    }
}
