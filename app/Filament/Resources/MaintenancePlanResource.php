<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenancePlanResource\Pages\CreateMaintenancePlan;
use App\Filament\Resources\MaintenancePlanResource\Pages\EditMaintenancePlan;
use App\Filament\Resources\MaintenancePlanResource\Pages\ListMaintenancePlans;
use App\Modules\Maintenance\Models\MaintenancePlan;
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

class MaintenancePlanResource extends Resource
{
    protected static ?string $model = MaintenancePlan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $moduleKey = 'maintenance';

    protected static ?string $permission = 'maintenance.maintenancePlan';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'maintenance')
            && $user->permits((static::$permission ?? 'maintenance.maintenancePlan').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('machine_id')->label('Machine Id')->relationship('machine', 'name')->searchable()->preload(),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('interval_hours')->label('Interval Hours')->numeric(),
            TextInput::make('interval_days')->label('Interval Days')->numeric(),
            DateTimePicker::make('last_done_at')->label('Last Done At'),
            DateTimePicker::make('next_due_at')->label('Next Due At'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('interval_hours')->label('Interval Hours')->searchable()->sortable()->toggleable(),
            TextColumn::make('interval_days')->label('Interval Days')->searchable()->sortable()->toggleable(),
            TextColumn::make('last_done_at')->label('Last Done At')->searchable()->sortable()->toggleable(),
            TextColumn::make('next_due_at')->label('Next Due At')->searchable()->sortable()->toggleable(),
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
            'index' => ListMaintenancePlans::route('/'),
            'create' => CreateMaintenancePlan::route('/create'),
            'edit' => EditMaintenancePlan::route('/{record}/edit'),
        ];
    }
}
