<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MachineResource\Pages\CreateMachine;
use App\Filament\Resources\MachineResource\Pages\EditMachine;
use App\Filament\Resources\MachineResource\Pages\ListMachines;
use App\Modules\Master\Models\Machine;
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

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $moduleKey = 'master';

    protected static ?string $permission = 'master.machine';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'master')
            && $user->permits((static::$permission ?? 'master.machine').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            Select::make('section')->label('Section')->options(['blowroom' => 'blowroom', 'carding' => 'carding', 'drawing' => 'drawing', 'combing' => 'combing', 'roving' => 'roving', 'ring' => 'ring', 'winding' => 'winding', 'packing' => 'packing', 'warping' => 'warping', 'sizing' => 'sizing', 'loom' => 'loom', 'knitting' => 'knitting', 'dyeing' => 'dyeing', 'finishing' => 'finishing', 'utility' => 'utility'])->native(false),
            TextInput::make('make')->label('Make')->maxLength(255),
            TextInput::make('model')->label('Model')->maxLength(255),
            TextInput::make('capacity')->label('Capacity')->numeric(),
            DatePicker::make('install_date')->label('Install Date'),
            TextInput::make('spindle_count')->label('Spindle Count')->numeric(),
            TextInput::make('location')->label('Location')->maxLength(255),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            TextInput::make('running_hours')->label('Running Hours')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('section')->label('Section')->searchable()->sortable()->toggleable(),
            TextColumn::make('make')->label('Make')->searchable()->sortable()->toggleable(),
            TextColumn::make('model')->label('Model')->searchable()->sortable()->toggleable(),
            TextColumn::make('capacity')->label('Capacity')->searchable()->sortable()->toggleable(),
            TextColumn::make('install_date')->label('Install Date')->searchable()->sortable()->toggleable(),
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
            'index' => ListMachines::route('/'),
            'create' => CreateMachine::route('/create'),
            'edit' => EditMachine::route('/{record}/edit'),
        ];
    }
}
