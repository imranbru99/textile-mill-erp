<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages\CreateJobPosting;
use App\Filament\Resources\JobPostingResource\Pages\EditJobPosting;
use App\Filament\Resources\JobPostingResource\Pages\ListJobPostings;
use App\Modules\Website\Models\JobPosting;
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

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $moduleKey = 'website';

    protected static ?string $permission = 'website.jobPosting';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'website')
            && $user->permits((static::$permission ?? 'website.jobPosting').'.view');
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
            TextInput::make('department')->label('Department')->maxLength(255),
            TextInput::make('location')->label('Location')->maxLength(255),
            Textarea::make('body')->label('Body')->columnSpanFull(),
            DatePicker::make('closes_on')->label('Closes On'),
            Toggle::make('is_open')->label('Is Open'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('title')->label('Title')->searchable()->sortable()->toggleable(),
            TextColumn::make('slug')->label('Slug')->searchable()->sortable()->toggleable(),
            TextColumn::make('department')->label('Department')->searchable()->sortable()->toggleable(),
            TextColumn::make('location')->label('Location')->searchable()->sortable()->toggleable(),
            TextColumn::make('closes_on')->label('Closes On')->searchable()->sortable()->toggleable(),
            TextColumn::make('is_open')->label('Is Open')->searchable()->sortable()->toggleable(),
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
            'index' => ListJobPostings::route('/'),
            'create' => CreateJobPosting::route('/create'),
            'edit' => EditJobPosting::route('/{record}/edit'),
        ];
    }
}
