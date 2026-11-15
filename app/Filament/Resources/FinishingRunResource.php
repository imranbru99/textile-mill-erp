<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FinishingRunResource\Pages\CreateFinishingRun;
use App\Filament\Resources\FinishingRunResource\Pages\EditFinishingRun;
use App\Filament\Resources\FinishingRunResource\Pages\ListFinishingRuns;
use App\Modules\Dyeing\Models\FinishingRun;
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

class FinishingRunResource extends Resource
{
    protected static ?string $model = FinishingRun::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Wet Processing';

    protected static ?string $moduleKey = 'dyeing';

    protected static ?string $permission = 'dyeing.finishingRun';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'dyeing')
            && $user->permits((static::$permission ?? 'dyeing.finishingRun').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('machine_id')->label('Machine Id')->relationship('machine', 'name')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            Select::make('process')->label('Process')->options(['singeing' => 'singeing', 'desizing' => 'desizing', 'scouring' => 'scouring', 'bleaching' => 'bleaching', 'mercerizing' => 'mercerizing', 'dyeing' => 'dyeing', 'printing' => 'printing', 'stenter' => 'stenter', 'compacting' => 'compacting', 'calender' => 'calender', 'sanfor' => 'sanfor', 'peach' => 'peach'])->native(false),
            TextInput::make('temperature_c')->label('Temperature C')->numeric(),
            TextInput::make('speed')->label('Speed')->numeric(),
            TextInput::make('overfeed')->label('Overfeed')->numeric(),
            TextInput::make('width_inch')->label('Width Inch')->numeric(),
            TextInput::make('gsm')->label('Gsm')->numeric(),
            TextInput::make('shrinkage_warp')->label('Shrinkage Warp')->numeric(),
            TextInput::make('shrinkage_weft')->label('Shrinkage Weft')->numeric(),
            Toggle::make('released')->label('Released'),
            TextInput::make('number')->label('Number')->required()->maxLength(255),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            DatePicker::make('document_date')->label('Document Date'),
            TextInput::make('currency_code')->label('Currency Code')->required()->maxLength(255),
            TextInput::make('exchange_rate')->label('Exchange Rate')->numeric(),
            Textarea::make('notes')->label('Notes')->columnSpanFull(),
            TextInput::make('total_amount')->label('Total Amount')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('machine_id')->label('Machine Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('process')->label('Process')->searchable()->sortable()->toggleable(),
            TextColumn::make('temperature_c')->label('Temperature C')->searchable()->sortable()->toggleable(),
            TextColumn::make('speed')->label('Speed')->searchable()->sortable()->toggleable(),
            TextColumn::make('overfeed')->label('Overfeed')->searchable()->sortable()->toggleable(),
            TextColumn::make('width_inch')->label('Width Inch')->searchable()->sortable()->toggleable(),
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
            'index' => ListFinishingRuns::route('/'),
            'create' => CreateFinishingRun::route('/create'),
            'edit' => EditFinishingRun::route('/{record}/edit'),
        ];
    }
}
