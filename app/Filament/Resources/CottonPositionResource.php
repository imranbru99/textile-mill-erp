<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CottonPositionResource\Pages\CreateCottonPosition;
use App\Filament\Resources\CottonPositionResource\Pages\EditCottonPosition;
use App\Filament\Resources\CottonPositionResource\Pages\ListCottonPositions;
use App\Modules\Costing\Models\CottonPosition;
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

class CottonPositionResource extends Resource
{
    protected static ?string $model = CottonPosition::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string|\UnitEnum|null $navigationGroup = 'Costing';

    protected static ?string $moduleKey = 'costing';

    protected static ?string $permission = 'costing.cottonPosition';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'costing')
            && $user->permits((static::$permission ?? 'costing.cottonPosition').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('position_date')->label('Position Date'),
            TextInput::make('open_purchase_kg')->label('Open Purchase Kg')->numeric(),
            TextInput::make('open_sales_kg')->label('Open Sales Kg')->numeric(),
            TextInput::make('avg_purchase_rate')->label('Avg Purchase Rate')->numeric(),
            TextInput::make('avg_sales_rate')->label('Avg Sales Rate')->numeric(),
            TextInput::make('forward_cover')->label('Forward Cover')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('position_date')->label('Position Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('open_purchase_kg')->label('Open Purchase Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('open_sales_kg')->label('Open Sales Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('avg_purchase_rate')->label('Avg Purchase Rate')->searchable()->sortable()->toggleable(),
            TextColumn::make('avg_sales_rate')->label('Avg Sales Rate')->searchable()->sortable()->toggleable(),
            TextColumn::make('forward_cover')->label('Forward Cover')->searchable()->sortable()->toggleable(),
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
            'index' => ListCottonPositions::route('/'),
            'create' => CreateCottonPosition::route('/create'),
            'edit' => EditCottonPosition::route('/{record}/edit'),
        ];
    }
}
