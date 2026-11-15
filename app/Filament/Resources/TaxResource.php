<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TaxResource\Pages\CreateTax;
use App\Filament\Resources\TaxResource\Pages\EditTax;
use App\Filament\Resources\TaxResource\Pages\ListTaxs;
use App\Modules\Master\Models\Tax;
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

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $moduleKey = 'master';

    protected static ?string $permission = 'master.tax';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'master')
            && $user->permits((static::$permission ?? 'master.tax').'.view');
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
            TextInput::make('rate')->label('Rate')->numeric(),
            Select::make('kind')->label('Kind')->options(['vat' => 'vat', 'ait' => 'ait', 'tds' => 'tds', 'sd' => 'sd', 'raw_cotton' => 'raw cotton', 'yarn' => 'yarn', 'greige' => 'greige', 'finished' => 'finished', 'dye_chemical' => 'dye chemical', 'spare' => 'spare', 'scrap' => 'scrap', 'bonded' => 'bonded', 'preventive' => 'preventive', 'breakdown' => 'breakdown', 'received' => 'received', 'issued' => 'issued'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('rate')->label('Rate')->searchable()->sortable()->toggleable(),
            TextColumn::make('kind')->label('Kind')->searchable()->sortable()->toggleable(),
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
            'index' => ListTaxs::route('/'),
            'create' => CreateTax::route('/create'),
            'edit' => EditTax::route('/{record}/edit'),
        ];
    }
}
