<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DailySummaryResource\Pages\CreateDailySummary;
use App\Filament\Resources\DailySummaryResource\Pages\EditDailySummary;
use App\Filament\Resources\DailySummaryResource\Pages\ListDailySummarys;
use App\Modules\Reports\Models\DailySummary;
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

class DailySummaryResource extends Resource
{
    protected static ?string $model = DailySummary::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $moduleKey = 'reports';

    protected static ?string $permission = 'reports.dailySummary';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'reports')
            && $user->permits((static::$permission ?? 'reports.dailySummary').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            DatePicker::make('summary_date')->label('Summary Date'),
            Select::make('section')->label('Section')->options(['blowroom' => 'blowroom', 'carding' => 'carding', 'drawing' => 'drawing', 'combing' => 'combing', 'roving' => 'roving', 'ring' => 'ring', 'winding' => 'winding', 'packing' => 'packing', 'warping' => 'warping', 'sizing' => 'sizing', 'loom' => 'loom', 'knitting' => 'knitting', 'dyeing' => 'dyeing', 'finishing' => 'finishing', 'utility' => 'utility'])->native(false),
            TextInput::make('output_qty')->label('Output Qty')->numeric(),
            TextInput::make('efficiency')->label('Efficiency')->numeric(),
            TextInput::make('waste_percent')->label('Waste Percent')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('summary_date')->label('Summary Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('section')->label('Section')->searchable()->sortable()->toggleable(),
            TextColumn::make('output_qty')->label('Output Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('efficiency')->label('Efficiency')->searchable()->sortable()->toggleable(),
            TextColumn::make('waste_percent')->label('Waste Percent')->searchable()->sortable()->toggleable(),
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
            'index' => ListDailySummarys::route('/'),
            'create' => CreateDailySummary::route('/create'),
            'edit' => EditDailySummary::route('/{record}/edit'),
        ];
    }
}
