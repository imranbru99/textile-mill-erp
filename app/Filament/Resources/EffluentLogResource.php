<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EffluentLogResource\Pages\CreateEffluentLog;
use App\Filament\Resources\EffluentLogResource\Pages\EditEffluentLog;
use App\Filament\Resources\EffluentLogResource\Pages\ListEffluentLogs;
use App\Modules\Dyeing\Models\EffluentLog;
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

class EffluentLogResource extends Resource
{
    protected static ?string $model = EffluentLog::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Wet Processing';

    protected static ?string $moduleKey = 'dyeing';

    protected static ?string $permission = 'dyeing.effluentLog';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'dyeing')
            && $user->permits((static::$permission ?? 'dyeing.effluentLog').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            DatePicker::make('log_date')->label('Log Date'),
            TextInput::make('ph')->label('Ph')->numeric(),
            TextInput::make('cod')->label('Cod')->numeric(),
            TextInput::make('bod')->label('Bod')->numeric(),
            TextInput::make('tds')->label('Tds')->numeric(),
            TextInput::make('color')->label('Color')->maxLength(255),
            Toggle::make('discharged')->label('Discharged'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('log_date')->label('Log Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('ph')->label('Ph')->searchable()->sortable()->toggleable(),
            TextColumn::make('cod')->label('Cod')->searchable()->sortable()->toggleable(),
            TextColumn::make('bod')->label('Bod')->searchable()->sortable()->toggleable(),
            TextColumn::make('tds')->label('Tds')->searchable()->sortable()->toggleable(),
            TextColumn::make('color')->label('Color')->searchable()->sortable()->toggleable(),
            TextColumn::make('discharged')->label('Discharged')->searchable()->sortable()->toggleable(),
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
            'index' => ListEffluentLogs::route('/'),
            'create' => CreateEffluentLog::route('/create'),
            'edit' => EditEffluentLog::route('/{record}/edit'),
        ];
    }
}
