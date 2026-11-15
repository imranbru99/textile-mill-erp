<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CalibrationResource\Pages\CreateCalibration;
use App\Filament\Resources\CalibrationResource\Pages\EditCalibration;
use App\Filament\Resources\CalibrationResource\Pages\ListCalibrations;
use App\Modules\Quality\Models\Calibration;
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

class CalibrationResource extends Resource
{
    protected static ?string $model = Calibration::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

    protected static ?string $permission = 'quality.calibration';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'quality')
            && $user->permits((static::$permission ?? 'quality.calibration').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('instrument')->label('Instrument')->required()->maxLength(255),
            DatePicker::make('last_done')->label('Last Done'),
            DatePicker::make('next_due')->label('Next Due'),
            TextInput::make('certificate_no')->label('Certificate No')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('instrument')->label('Instrument')->searchable()->sortable()->toggleable(),
            TextColumn::make('last_done')->label('Last Done')->searchable()->sortable()->toggleable(),
            TextColumn::make('next_due')->label('Next Due')->searchable()->sortable()->toggleable(),
            TextColumn::make('certificate_no')->label('Certificate No')->searchable()->sortable()->toggleable(),
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
            'index' => ListCalibrations::route('/'),
            'create' => CreateCalibration::route('/create'),
            'edit' => EditCalibration::route('/{record}/edit'),
        ];
    }
}
