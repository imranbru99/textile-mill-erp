<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages\CreateRecipe;
use App\Filament\Resources\RecipeResource\Pages\EditRecipe;
use App\Filament\Resources\RecipeResource\Pages\ListRecipes;
use App\Modules\Dyeing\Models\Recipe;
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

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Wet Processing';

    protected static ?string $moduleKey = 'dyeing';

    protected static ?string $permission = 'dyeing.recipe';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'dyeing')
            && $user->permits((static::$permission ?? 'dyeing.recipe').'.view');
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
            TextInput::make('version')->label('Version')->numeric(),
            TextInput::make('liquor_ratio')->label('Liquor Ratio')->numeric(),
            TextInput::make('dye_class')->label('Dye Class')->maxLength(255),
            Toggle::make('locked')->label('Locked'),
            Select::make('parent_id')->label('Parent Id')->relationship('parent', 'name')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('version')->label('Version')->searchable()->sortable()->toggleable(),
            TextColumn::make('liquor_ratio')->label('Liquor Ratio')->searchable()->sortable()->toggleable(),
            TextColumn::make('dye_class')->label('Dye Class')->searchable()->sortable()->toggleable(),
            TextColumn::make('locked')->label('Locked')->searchable()->sortable()->toggleable(),
            TextColumn::make('parent_id')->label('Parent Id')->sortable()->toggleable(),
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
            'index' => ListRecipes::route('/'),
            'create' => CreateRecipe::route('/create'),
            'edit' => EditRecipe::route('/{record}/edit'),
        ];
    }
}
