<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages\CreateEmployee;
use App\Filament\Resources\EmployeeResource\Pages\EditEmployee;
use App\Filament\Resources\EmployeeResource\Pages\ListEmployees;
use App\Modules\People\Models\Employee;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|\UnitEnum|null $navigationGroup = 'HR & Payroll';

    protected static ?string $moduleKey = 'hr';

    protected static ?string $permission = 'hr.employee';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'hr')
            && $user->permits((static::$permission ?? 'hr.employee').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('employee_no')->label('Employee No')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            Select::make('designation_id')->label('Designation Id')->relationship('designation', 'name')->searchable()->preload(),
            Select::make('department_id')->label('Department Id')->relationship('department', 'name')->searchable()->preload(),
            Select::make('category')->label('Category')->options(['permanent' => 'permanent', 'contract' => 'contract', 'piece_rate' => 'piece rate', 'daily' => 'daily', 'agency' => 'agency', 'license' => 'license', 'test' => 'test', 'safety' => 'safety', 'commercial' => 'commercial'])->native(false),
            DatePicker::make('join_date')->label('Join Date'),
            TextInput::make('basic_salary')->label('Basic Salary')->numeric(),
            TextInput::make('phone')->label('Phone')->maxLength(255),
            TextInput::make('nid')->label('Nid')->maxLength(255),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            TextInput::make('device_pin')->label('Device Pin')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('employee_no')->label('Employee No')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('designation_id')->label('Designation Id')->sortable()->toggleable(),
            TextColumn::make('department_id')->label('Department Id')->sortable()->toggleable(),
            TextColumn::make('category')->label('Category')->searchable()->sortable()->toggleable(),
            TextColumn::make('join_date')->label('Join Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('basic_salary')->label('Basic Salary')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'posted' => 'Posted', 'closed' => 'Closed']),
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
