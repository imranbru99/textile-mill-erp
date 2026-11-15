<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FiscalPeriodResource\Pages\CreateFiscalPeriod;
use App\Filament\Resources\FiscalPeriodResource\Pages\EditFiscalPeriod;
use App\Filament\Resources\FiscalPeriodResource\Pages\ListFiscalPeriods;
use App\Modules\Finance\Models\FiscalPeriod;
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

class FiscalPeriodResource extends Resource
{
    protected static ?string $model = FiscalPeriod::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.fiscalPeriod';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.fiscalPeriod').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('fiscal_year_id')->label('Fiscal Year Id')->relationship('fiscalYear', 'name')->searchable()->preload(),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            DatePicker::make('starts_on')->label('Starts On'),
            DatePicker::make('ends_on')->label('Ends On'),
            Toggle::make('is_locked')->label('Is Locked'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('fiscal_year_id')->label('Fiscal Year Id')->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('starts_on')->label('Starts On')->searchable()->sortable()->toggleable(),
            TextColumn::make('ends_on')->label('Ends On')->searchable()->sortable()->toggleable(),
            TextColumn::make('is_locked')->label('Is Locked')->searchable()->sortable()->toggleable(),
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
            'index' => ListFiscalPeriods::route('/'),
            'create' => CreateFiscalPeriod::route('/create'),
            'edit' => EditFiscalPeriod::route('/{record}/edit'),
        ];
    }
}
