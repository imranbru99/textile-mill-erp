<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ExportRealizationResource\Pages\CreateExportRealization;
use App\Filament\Resources\ExportRealizationResource\Pages\EditExportRealization;
use App\Filament\Resources\ExportRealizationResource\Pages\ListExportRealizations;
use App\Modules\Finance\Models\ExportRealization;
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

class ExportRealizationResource extends Resource
{
    protected static ?string $model = ExportRealization::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $moduleKey = 'finance';

    protected static ?string $permission = 'finance.exportRealization';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'finance')
            && $user->permits((static::$permission ?? 'finance.exportRealization').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('sales_invoice_id')->label('Sales Invoice Id')->relationship('salesInvoice', 'number')->searchable()->preload(),
            Select::make('bank_id')->label('Bank Id')->relationship('bank', 'name')->searchable()->preload(),
            TextInput::make('realized_amount')->label('Realized Amount')->numeric(),
            TextInput::make('brc_no')->label('Brc No')->maxLength(255),
            DatePicker::make('realized_on')->label('Realized On'),
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
            TextColumn::make('sales_invoice_id')->label('Sales Invoice Id')->sortable()->toggleable(),
            TextColumn::make('bank_id')->label('Bank Id')->sortable()->toggleable(),
            TextColumn::make('realized_amount')->label('Realized Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('brc_no')->label('Brc No')->searchable()->sortable()->toggleable(),
            TextColumn::make('realized_on')->label('Realized On')->searchable()->sortable()->toggleable(),
            TextColumn::make('number')->label('Number')->searchable()->sortable()->toggleable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable()->toggleable(),
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
            'index' => ListExportRealizations::route('/'),
            'create' => CreateExportRealization::route('/create'),
            'edit' => EditExportRealization::route('/{record}/edit'),
        ];
    }
}
