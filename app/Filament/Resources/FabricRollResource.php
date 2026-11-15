<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FabricRollResource\Pages\CreateFabricRoll;
use App\Filament\Resources\FabricRollResource\Pages\EditFabricRoll;
use App\Filament\Resources\FabricRollResource\Pages\ListFabricRolls;
use App\Modules\Weaving\Models\FabricRoll;
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

class FabricRollResource extends Resource
{
    protected static ?string $model = FabricRoll::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|\UnitEnum|null $navigationGroup = 'Weaving';

    protected static ?string $moduleKey = 'weaving';

    protected static ?string $permission = 'weaving.fabricRoll';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'weaving')
            && $user->permits((static::$permission ?? 'weaving.fabricRoll').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('roll_no')->label('Roll No')->required()->maxLength(255),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('length_meter')->label('Length Meter')->numeric(),
            TextInput::make('width_inch')->label('Width Inch')->numeric(),
            TextInput::make('gsm')->label('Gsm')->numeric(),
            Select::make('grade')->label('Grade')->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'reject' => 'reject'])->native(false),
            TextInput::make('points')->label('Points')->numeric(),
            TextInput::make('points_per_100')->label('Points Per 100')->numeric(),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            Select::make('weaving_production_id')->label('Weaving Production Id')->relationship('weavingProduction', 'number')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('roll_no')->label('Roll No')->searchable()->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('length_meter')->label('Length Meter')->searchable()->sortable()->toggleable(),
            TextColumn::make('width_inch')->label('Width Inch')->searchable()->sortable()->toggleable(),
            TextColumn::make('gsm')->label('Gsm')->searchable()->sortable()->toggleable(),
            TextColumn::make('grade')->label('Grade')->searchable()->sortable()->toggleable(),
            TextColumn::make('points')->label('Points')->searchable()->sortable()->toggleable(),
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
            'index' => ListFabricRolls::route('/'),
            'create' => CreateFabricRoll::route('/create'),
            'edit' => EditFabricRoll::route('/{record}/edit'),
        ];
    }
}
