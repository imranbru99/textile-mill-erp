<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LcShipmentResource\Pages\CreateLcShipment;
use App\Filament\Resources\LcShipmentResource\Pages\EditLcShipment;
use App\Filament\Resources\LcShipmentResource\Pages\ListLcShipments;
use App\Modules\Finance\Models\LcShipment;
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

class LcShipmentResource extends Resource
{
    protected static ?string $model = LcShipment::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.lcShipment';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.lcShipment').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('letter_of_credit_id')->label('Letter Of Credit Id')->relationship('letterOfCredit', 'number')->searchable()->preload(),
            TextInput::make('bl_no')->label('Bl No')->maxLength(255),
            DatePicker::make('shipped_on')->label('Shipped On'),
            TextInput::make('amount')->label('Amount')->numeric(),
            DatePicker::make('acceptance_due')->label('Acceptance Due'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('letter_of_credit_id')->label('Letter Of Credit Id')->sortable()->toggleable(),
            TextColumn::make('bl_no')->label('Bl No')->searchable()->sortable()->toggleable(),
            TextColumn::make('shipped_on')->label('Shipped On')->searchable()->sortable()->toggleable(),
            TextColumn::make('amount')->label('Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('acceptance_due')->label('Acceptance Due')->searchable()->sortable()->toggleable(),
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
            'index' => ListLcShipments::route('/'),
            'create' => CreateLcShipment::route('/create'),
            'edit' => EditLcShipment::route('/{record}/edit'),
        ];
    }
}
