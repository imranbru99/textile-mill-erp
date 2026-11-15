<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages\CreateNewsPost;
use App\Filament\Resources\NewsPostResource\Pages\EditNewsPost;
use App\Filament\Resources\NewsPostResource\Pages\ListNewsPosts;
use App\Modules\Website\Models\NewsPost;
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

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $moduleKey = 'website';

    protected static ?string $permission = 'website.newsPost';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'website')
            && $user->permits((static::$permission ?? 'website.newsPost').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Title')->required()->maxLength(255),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            TextInput::make('locale')->label('Locale')->required()->maxLength(255),
            Textarea::make('body')->label('Body')->columnSpanFull(),
            DateTimePicker::make('published_at')->label('Published At'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('title')->label('Title')->searchable()->sortable()->toggleable(),
            TextColumn::make('slug')->label('Slug')->searchable()->sortable()->toggleable(),
            TextColumn::make('locale')->label('Locale')->searchable()->sortable()->toggleable(),
            TextColumn::make('published_at')->label('Published At')->searchable()->sortable()->toggleable(),
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
            'index' => ListNewsPosts::route('/'),
            'create' => CreateNewsPost::route('/create'),
            'edit' => EditNewsPost::route('/{record}/edit'),
        ];
    }
}
