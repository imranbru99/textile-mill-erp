<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BreakdownLogResource\Pages\CreateBreakdownLog;
use App\Filament\Resources\BreakdownLogResource\Pages\EditBreakdownLog;
use App\Filament\Resources\BreakdownLogResource\Pages\ListBreakdownLogs;
use App\Modules\Maintenance\Models\BreakdownLog;
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

class BreakdownLogResource extends Resource
{
    protected static ?string $model = BreakdownLog::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $moduleKey = 'maintenance';

    protected static ?string $permission = 'maintenance.breakdownLog';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'maintenance')
            && $user->permits((static::$permission ?? 'maintenance.breakdownLog').'.view');
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
            TextInput::make('reason_code')->label('Reason Code')->required()->maxLength(255),
            DateTimePicker::make('started_at')->label('Started At'),
            DateTimePicker::make('ended_at')->label('Ended At'),
            TextInput::make('minutes')->label('Minutes')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('reason_code')->label('Reason Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('started_at')->label('Started At')->searchable()->sortable()->toggleable(),
            TextColumn::make('ended_at')->label('Ended At')->searchable()->sortable()->toggleable(),
            TextColumn::make('minutes')->label('Minutes')->searchable()->sortable()->toggleable(),
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
            'index' => ListBreakdownLogs::route('/'),
            'create' => CreateBreakdownLog::route('/create'),
            'edit' => EditBreakdownLog::route('/{record}/edit'),
        ];
    }
}
