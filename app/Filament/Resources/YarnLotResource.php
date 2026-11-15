<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\YarnLotResource\Pages\CreateYarnLot;
use App\Filament\Resources\YarnLotResource\Pages\EditYarnLot;
use App\Filament\Resources\YarnLotResource\Pages\ListYarnLots;
use App\Modules\Spinning\Models\YarnLot;
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

class YarnLotResource extends Resource
{
    protected static ?string $model = YarnLot::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Spinning';

    protected static ?string $moduleKey = 'spinning';

    protected static ?string $permission = 'spinning.yarnLot';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'spinning')
            && $user->permits((static::$permission ?? 'spinning.yarnLot').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('lot_no')->label('Lot No')->required()->maxLength(255),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('count_ne')->label('Count Ne')->numeric(),
            TextInput::make('ply')->label('Ply')->numeric(),
            TextInput::make('tpi')->label('Tpi')->numeric(),
            Select::make('grade')->label('Grade')->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'reject' => 'reject'])->native(false),
            TextInput::make('qty_kg')->label('Qty Kg')->numeric(),
            Select::make('cotton_mixing_id')->label('Cotton Mixing Id')->relationship('cottonMixing', 'number')->searchable()->preload(),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('lot_no')->label('Lot No')->searchable()->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('count_ne')->label('Count Ne')->searchable()->sortable()->toggleable(),
            TextColumn::make('ply')->label('Ply')->searchable()->sortable()->toggleable(),
            TextColumn::make('tpi')->label('Tpi')->searchable()->sortable()->toggleable(),
            TextColumn::make('grade')->label('Grade')->searchable()->sortable()->toggleable(),
            TextColumn::make('qty_kg')->label('Qty Kg')->searchable()->sortable()->toggleable(),
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
            'index' => ListYarnLots::route('/'),
            'create' => CreateYarnLot::route('/create'),
            'edit' => EditYarnLot::route('/{record}/edit'),
        ];
    }
}
