<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SubcontractOrderResource\Pages\CreateSubcontractOrder;
use App\Filament\Resources\SubcontractOrderResource\Pages\EditSubcontractOrder;
use App\Filament\Resources\SubcontractOrderResource\Pages\ListSubcontractOrders;
use App\Modules\Planning\Models\SubcontractOrder;
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

class SubcontractOrderResource extends Resource
{
    protected static ?string $model = SubcontractOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Planning';

    protected static ?string $moduleKey = 'planning';

    protected static ?string $permission = 'planning.subcontractOrder';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'planning')
            && $user->permits((static::$permission ?? 'planning.subcontractOrder').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('party_id')->label('Party Id')->relationship('party', 'name')->searchable()->preload(),
            Select::make('process')->label('Process')->options(['singeing' => 'singeing', 'desizing' => 'desizing', 'scouring' => 'scouring', 'bleaching' => 'bleaching', 'mercerizing' => 'mercerizing', 'dyeing' => 'dyeing', 'printing' => 'printing', 'stenter' => 'stenter', 'compacting' => 'compacting', 'calender' => 'calender', 'sanfor' => 'sanfor', 'peach' => 'peach'])->native(false),
            TextInput::make('qty')->label('Qty')->numeric(),
            TextInput::make('rate')->label('Rate')->numeric(),
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
            TextColumn::make('party_id')->label('Party Id')->sortable()->toggleable(),
            TextColumn::make('process')->label('Process')->searchable()->sortable()->toggleable(),
            TextColumn::make('qty')->label('Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('rate')->label('Rate')->searchable()->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable()->toggleable(),
            TextColumn::make('document_date')->label('Document Date')->searchable()->sortable()->toggleable(),
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
            'index' => ListSubcontractOrders::route('/'),
            'create' => CreateSubcontractOrder::route('/create'),
            'edit' => EditSubcontractOrder::route('/{record}/edit'),
        ];
    }
}
