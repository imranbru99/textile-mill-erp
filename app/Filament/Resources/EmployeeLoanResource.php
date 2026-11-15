<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeLoanResource\Pages\CreateEmployeeLoan;
use App\Filament\Resources\EmployeeLoanResource\Pages\EditEmployeeLoan;
use App\Filament\Resources\EmployeeLoanResource\Pages\ListEmployeeLoans;
use App\Modules\People\Models\EmployeeLoan;
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

class EmployeeLoanResource extends Resource
{
    protected static ?string $model = EmployeeLoan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|\UnitEnum|null $navigationGroup = 'HR & Payroll';

    protected static ?string $moduleKey = 'hr';

    protected static ?string $permission = 'hr.employeeLoan';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'hr')
            && $user->permits((static::$permission ?? 'hr.employeeLoan').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')->label('Employee Id')->relationship('employee', 'name')->searchable()->preload(),
            TextInput::make('principal')->label('Principal')->numeric(),
            TextInput::make('balance')->label('Balance')->numeric(),
            TextInput::make('installment')->label('Installment')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('employee_id')->label('Employee Id')->sortable()->toggleable(),
            TextColumn::make('principal')->label('Principal')->searchable()->sortable()->toggleable(),
            TextColumn::make('balance')->label('Balance')->searchable()->sortable()->toggleable(),
            TextColumn::make('installment')->label('Installment')->searchable()->sortable()->toggleable(),
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
            'index' => ListEmployeeLoans::route('/'),
            'create' => CreateEmployeeLoan::route('/create'),
            'edit' => EditEmployeeLoan::route('/{record}/edit'),
        ];
    }
}
