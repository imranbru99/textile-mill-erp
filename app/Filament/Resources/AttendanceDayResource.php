<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceDayResource\Pages\CreateAttendanceDay;
use App\Filament\Resources\AttendanceDayResource\Pages\EditAttendanceDay;
use App\Filament\Resources\AttendanceDayResource\Pages\ListAttendanceDays;
use App\Modules\People\Models\AttendanceDay;
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

class AttendanceDayResource extends Resource
{
    protected static ?string $model = AttendanceDay::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|\UnitEnum|null $navigationGroup = 'HR & Payroll';

    protected static ?string $moduleKey = 'hr';

    protected static ?string $permission = 'hr.attendanceDay';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'hr')
            && $user->permits((static::$permission ?? 'hr.attendanceDay').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('employee_id')->label('Employee Id')->relationship('employee', 'name')->searchable()->preload(),
            DatePicker::make('work_date')->label('Work Date'),
            Select::make('shift_id')->label('Shift Id')->relationship('shift', 'name')->searchable()->preload(),
            DateTimePicker::make('in_at')->label('In At'),
            DateTimePicker::make('out_at')->label('Out At'),
            TextInput::make('worked_minutes')->label('Worked Minutes')->numeric(),
            TextInput::make('late_minutes')->label('Late Minutes')->numeric(),
            TextInput::make('ot_minutes')->label('Ot Minutes')->numeric(),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('employee_id')->label('Employee Id')->sortable()->toggleable(),
            TextColumn::make('work_date')->label('Work Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('shift_id')->label('Shift Id')->sortable()->toggleable(),
            TextColumn::make('in_at')->label('In At')->searchable()->sortable()->toggleable(),
            TextColumn::make('out_at')->label('Out At')->searchable()->sortable()->toggleable(),
            TextColumn::make('worked_minutes')->label('Worked Minutes')->searchable()->sortable()->toggleable(),
            TextColumn::make('late_minutes')->label('Late Minutes')->searchable()->sortable()->toggleable(),
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
            'index' => ListAttendanceDays::route('/'),
            'create' => CreateAttendanceDay::route('/create'),
            'edit' => EditAttendanceDay::route('/{record}/edit'),
        ];
    }
}
