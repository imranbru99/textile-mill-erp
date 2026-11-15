<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PrintTemplateResource\Pages\CreatePrintTemplate;
use App\Filament\Resources\PrintTemplateResource\Pages\EditPrintTemplate;
use App\Filament\Resources\PrintTemplateResource\Pages\ListPrintTemplates;
use App\Modules\Foundation\Models\PrintTemplate;
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

class PrintTemplateResource extends Resource
{
    protected static ?string $model = PrintTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.printTemplate';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.printTemplate').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('document_type')->label('Document Type')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('locale')->label('Locale')->required()->maxLength(255),
            Textarea::make('body')->label('Body')->columnSpanFull(),
            Toggle::make('is_default')->label('Is Default'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('document_type')->label('Document Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('locale')->label('Locale')->searchable()->sortable()->toggleable(),
            TextColumn::make('is_default')->label('Is Default')->searchable()->sortable()->toggleable(),
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
            'index' => ListPrintTemplates::route('/'),
            'create' => CreatePrintTemplate::route('/create'),
            'edit' => EditPrintTemplate::route('/{record}/edit'),
        ];
    }
}
