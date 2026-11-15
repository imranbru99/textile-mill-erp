<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages\CreateSection;
use App\Filament\Resources\SectionResource\Pages\EditSection;
use App\Filament\Resources\SectionResource\Pages\ListSections;
use App\Modules\Foundation\Models\Section;
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

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.section';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.section').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('department_id')->label('Department Id')->relationship('department', 'name')->searchable()->preload(),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('code')->label('Code')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('department_id')->label('Department Id')->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
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
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }
}
