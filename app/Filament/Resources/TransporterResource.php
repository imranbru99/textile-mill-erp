<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TransporterResource\Pages\CreateTransporter;
use App\Filament\Resources\TransporterResource\Pages\EditTransporter;
use App\Filament\Resources\TransporterResource\Pages\ListTransporters;
use App\Modules\Logistics\Models\Transporter;
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

class TransporterResource extends Resource
{
    protected static ?string $model = Transporter::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics';

    protected static ?string $moduleKey = 'logistics';

    protected static ?string $permission = 'logistics.transporter';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'logistics')
            && $user->permits((static::$permission ?? 'logistics.transporter').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('phone')->label('Phone')->maxLength(255),
            TextInput::make('vehicle_no')->label('Vehicle No')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('phone')->label('Phone')->searchable()->sortable()->toggleable(),
            TextColumn::make('vehicle_no')->label('Vehicle No')->searchable()->sortable()->toggleable(),
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
            'index' => ListTransporters::route('/'),
            'create' => CreateTransporter::route('/create'),
            'edit' => EditTransporter::route('/{record}/edit'),
        ];
    }
}
