<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CmsBlockResource\Pages\CreateCmsBlock;
use App\Filament\Resources\CmsBlockResource\Pages\EditCmsBlock;
use App\Filament\Resources\CmsBlockResource\Pages\ListCmsBlocks;
use App\Modules\Website\Models\CmsBlock;
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

class CmsBlockResource extends Resource
{
    protected static ?string $model = CmsBlock::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $moduleKey = 'website';

    protected static ?string $permission = 'website.cmsBlock';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'website')
            && $user->permits((static::$permission ?? 'website.cmsBlock').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('cms_page_id')->label('Cms Page Id')->relationship('cmsPage', 'title')->searchable()->preload(),
            TextInput::make('key')->label('Key')->required()->maxLength(255),
            TextInput::make('locale')->label('Locale')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('cms_page_id')->label('Cms Page Id')->sortable()->toggleable(),
            TextColumn::make('key')->label('Key')->searchable()->sortable()->toggleable(),
            TextColumn::make('locale')->label('Locale')->searchable()->sortable()->toggleable(),
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
            'index' => ListCmsBlocks::route('/'),
            'create' => CreateCmsBlock::route('/create'),
            'edit' => EditCmsBlock::route('/{record}/edit'),
        ];
    }
}
