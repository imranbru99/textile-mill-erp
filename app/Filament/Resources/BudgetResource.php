<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages\CreateBudget;
use App\Filament\Resources\BudgetResource\Pages\EditBudget;
use App\Filament\Resources\BudgetResource\Pages\ListBudgets;
use App\Modules\Finance\Models\Budget;
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

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.budget';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.budget').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('fiscal_year_id')->label('Fiscal Year Id')->relationship('fiscalYear', 'name')->searchable()->preload(),
            Select::make('account_id')->label('Account Id')->relationship('account', 'name')->searchable()->preload(),
            Select::make('cost_center_id')->label('Cost Center Id')->relationship('costCenter', 'name')->searchable()->preload(),
            TextInput::make('amount')->label('Amount')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('fiscal_year_id')->label('Fiscal Year Id')->sortable()->toggleable(),
            TextColumn::make('account_id')->label('Account Id')->sortable()->toggleable(),
            TextColumn::make('cost_center_id')->label('Cost Center Id')->sortable()->toggleable(),
            TextColumn::make('amount')->label('Amount')->searchable()->sortable()->toggleable(),
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
            'index' => ListBudgets::route('/'),
            'create' => CreateBudget::route('/create'),
            'edit' => EditBudget::route('/{record}/edit'),
        ];
    }
}
