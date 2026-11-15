<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BondedRegisterResource\Pages\CreateBondedRegister;
use App\Filament\Resources\BondedRegisterResource\Pages\EditBondedRegister;
use App\Filament\Resources\BondedRegisterResource\Pages\ListBondedRegisters;
use App\Modules\Compliance\Models\BondedRegister;
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

class BondedRegisterResource extends Resource
{
    protected static ?string $model = BondedRegister::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|\UnitEnum|null $navigationGroup = 'Compliance';

    protected static ?string $moduleKey = 'bonded';

    protected static ?string $permission = 'bonded.bondedRegister';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'bonded')
            && $user->permits((static::$permission ?? 'bonded.bondedRegister').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('import_qty')->label('Import Qty')->numeric(),
            TextInput::make('consumed_qty')->label('Consumed Qty')->numeric(),
            TextInput::make('exported_qty')->label('Exported Qty')->numeric(),
            TextInput::make('allowed_waste_percent')->label('Allowed Waste Percent')->numeric(),
            TextInput::make('period')->label('Period')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('import_qty')->label('Import Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('consumed_qty')->label('Consumed Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('exported_qty')->label('Exported Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('allowed_waste_percent')->label('Allowed Waste Percent')->searchable()->sortable()->toggleable(),
            TextColumn::make('period')->label('Period')->searchable()->sortable()->toggleable(),
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
            'index' => ListBondedRegisters::route('/'),
            'create' => CreateBondedRegister::route('/create'),
            'edit' => EditBondedRegister::route('/{record}/edit'),
        ];
    }
}
