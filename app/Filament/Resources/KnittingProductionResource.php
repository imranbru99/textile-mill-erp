<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\KnittingProductionResource\Pages\CreateKnittingProduction;
use App\Filament\Resources\KnittingProductionResource\Pages\EditKnittingProduction;
use App\Filament\Resources\KnittingProductionResource\Pages\ListKnittingProductions;
use App\Modules\Knitting\Models\KnittingProduction;
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

class KnittingProductionResource extends Resource
{
    protected static ?string $model = KnittingProduction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Knitting';

    protected static ?string $moduleKey = 'knitting';

    protected static ?string $permission = 'knitting.knittingProduction';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'knitting')
            && $user->permits((static::$permission ?? 'knitting.knittingProduction').'.view');
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
            DatePicker::make('production_date')->label('Production Date'),
            TextInput::make('gsm')->label('Gsm')->numeric(),
            TextInput::make('stitch_length')->label('Stitch Length')->numeric(),
            TextInput::make('kg')->label('Kg')->numeric(),
            TextInput::make('rolls')->label('Rolls')->numeric(),
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
            TextColumn::make('production_date')->label('Production Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('gsm')->label('Gsm')->searchable()->sortable()->toggleable(),
            TextColumn::make('stitch_length')->label('Stitch Length')->searchable()->sortable()->toggleable(),
            TextColumn::make('kg')->label('Kg')->searchable()->sortable()->toggleable(),
            TextColumn::make('rolls')->label('Rolls')->searchable()->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
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
            'index' => ListKnittingProductions::route('/'),
            'create' => CreateKnittingProduction::route('/create'),
            'edit' => EditKnittingProduction::route('/{record}/edit'),
        ];
    }
}
