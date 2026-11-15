<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NumberSeriesResource\Pages\CreateNumberSeries;
use App\Filament\Resources\NumberSeriesResource\Pages\EditNumberSeries;
use App\Filament\Resources\NumberSeriesResource\Pages\ListNumberSeriess;
use App\Modules\Foundation\Models\NumberSeries;
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

class NumberSeriesResource extends Resource
{
    protected static ?string $model = NumberSeries::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.numberSeries';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.numberSeries').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('document_type')->label('Document Type')->required()->maxLength(255),
            TextInput::make('prefix')->label('Prefix')->required()->maxLength(255),
            TextInput::make('next_number')->label('Next Number')->numeric(),
            TextInput::make('padding')->label('Padding')->numeric(),
            Select::make('reset_rule')->label('Reset Rule')->options(['never' => 'never', 'yearly' => 'yearly', 'monthly' => 'monthly'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('document_type')->label('Document Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('prefix')->label('Prefix')->searchable()->sortable()->toggleable(),
            TextColumn::make('next_number')->label('Next Number')->searchable()->sortable()->toggleable(),
            TextColumn::make('padding')->label('Padding')->searchable()->sortable()->toggleable(),
            TextColumn::make('reset_rule')->label('Reset Rule')->searchable()->sortable()->toggleable(),
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
            'index' => ListNumberSeriess::route('/'),
            'create' => CreateNumberSeries::route('/create'),
            'edit' => EditNumberSeries::route('/{record}/edit'),
        ];
    }
}
