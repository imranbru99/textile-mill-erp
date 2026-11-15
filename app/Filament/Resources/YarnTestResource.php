<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\YarnTestResource\Pages\CreateYarnTest;
use App\Filament\Resources\YarnTestResource\Pages\EditYarnTest;
use App\Filament\Resources\YarnTestResource\Pages\ListYarnTests;
use App\Modules\Quality\Models\YarnTest;
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

class YarnTestResource extends Resource
{
    protected static ?string $model = YarnTest::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

    protected static ?string $permission = 'quality.yarnTest';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'quality')
            && $user->permits((static::$permission ?? 'quality.yarnTest').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('yarn_lot_id')->label('Yarn Lot Id')->relationship('yarnLot', 'lot_no')->searchable()->preload(),
            TextInput::make('uster_u')->label('Uster U')->numeric(),
            TextInput::make('ipi')->label('Ipi')->numeric(),
            TextInput::make('hairiness')->label('Hairiness')->numeric(),
            TextInput::make('strength')->label('Strength')->numeric(),
            TextInput::make('cv_percent')->label('Cv Percent')->numeric(),
            Select::make('grade')->label('Grade')->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'reject' => 'reject'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('yarn_lot_id')->label('Yarn Lot Id')->sortable()->toggleable(),
            TextColumn::make('uster_u')->label('Uster U')->searchable()->sortable()->toggleable(),
            TextColumn::make('ipi')->label('Ipi')->searchable()->sortable()->toggleable(),
            TextColumn::make('hairiness')->label('Hairiness')->searchable()->sortable()->toggleable(),
            TextColumn::make('strength')->label('Strength')->searchable()->sortable()->toggleable(),
            TextColumn::make('cv_percent')->label('Cv Percent')->searchable()->sortable()->toggleable(),
            TextColumn::make('grade')->label('Grade')->searchable()->sortable()->toggleable(),
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
            'index' => ListYarnTests::route('/'),
            'create' => CreateYarnTest::route('/create'),
            'edit' => EditYarnTest::route('/{record}/edit'),
        ];
    }
}
