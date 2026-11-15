<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingRecordResource\Pages\CreateTrainingRecord;
use App\Filament\Resources\TrainingRecordResource\Pages\EditTrainingRecord;
use App\Filament\Resources\TrainingRecordResource\Pages\ListTrainingRecords;
use App\Modules\People\Models\TrainingRecord;
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

class TrainingRecordResource extends Resource
{
    protected static ?string $model = TrainingRecord::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|\UnitEnum|null $navigationGroup = 'HR & Payroll';

    protected static ?string $moduleKey = 'hr';

    protected static ?string $permission = 'hr.trainingRecord';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'hr')
            && $user->permits((static::$permission ?? 'hr.trainingRecord').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')->label('Employee Id')->relationship('employee', 'name')->searchable()->preload(),
            TextInput::make('topic')->label('Topic')->required()->maxLength(255),
            DatePicker::make('trained_on')->label('Trained On'),
            DatePicker::make('expires_on')->label('Expires On'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('employee_id')->label('Employee Id')->sortable()->toggleable(),
            TextColumn::make('topic')->label('Topic')->searchable()->sortable()->toggleable(),
            TextColumn::make('trained_on')->label('Trained On')->searchable()->sortable()->toggleable(),
            TextColumn::make('expires_on')->label('Expires On')->searchable()->sortable()->toggleable(),
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
            'index' => ListTrainingRecords::route('/'),
            'create' => CreateTrainingRecord::route('/create'),
            'edit' => EditTrainingRecord::route('/{record}/edit'),
        ];
    }
}
