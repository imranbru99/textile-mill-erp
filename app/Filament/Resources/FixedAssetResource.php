<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FixedAssetResource\Pages\CreateFixedAsset;
use App\Filament\Resources\FixedAssetResource\Pages\EditFixedAsset;
use App\Filament\Resources\FixedAssetResource\Pages\ListFixedAssets;
use App\Modules\Finance\Models\FixedAsset;
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

class FixedAssetResource extends Resource
{
    protected static ?string $model = FixedAsset::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.fixedAsset';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.fixedAsset').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            DatePicker::make('acquired_on')->label('Acquired On'),
            TextInput::make('cost')->label('Cost')->numeric(),
            TextInput::make('salvage')->label('Salvage')->numeric(),
            TextInput::make('life_months')->label('Life Months')->numeric(),
            TextInput::make('accumulated')->label('Accumulated')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('acquired_on')->label('Acquired On')->searchable()->sortable()->toggleable(),
            TextColumn::make('cost')->label('Cost')->searchable()->sortable()->toggleable(),
            TextColumn::make('salvage')->label('Salvage')->searchable()->sortable()->toggleable(),
            TextColumn::make('life_months')->label('Life Months')->searchable()->sortable()->toggleable(),
            TextColumn::make('accumulated')->label('Accumulated')->searchable()->sortable()->toggleable(),
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
            'index' => ListFixedAssets::route('/'),
            'create' => CreateFixedAsset::route('/create'),
            'edit' => EditFixedAsset::route('/{record}/edit'),
        ];
    }
}
