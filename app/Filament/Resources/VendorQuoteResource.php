<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\VendorQuoteResource\Pages\CreateVendorQuote;
use App\Filament\Resources\VendorQuoteResource\Pages\EditVendorQuote;
use App\Filament\Resources\VendorQuoteResource\Pages\ListVendorQuotes;
use App\Modules\Supply\Models\VendorQuote;
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

class VendorQuoteResource extends Resource
{
    protected static ?string $model = VendorQuote::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|\UnitEnum|null $navigationGroup = 'Supply Chain';

    protected static ?string $moduleKey = 'purchase';

    protected static ?string $permission = 'purchase.vendorQuote';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'purchase')
            && $user->permits((static::$permission ?? 'purchase.vendorQuote').'.view');
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
            Select::make('request_for_quote_id')->label('Request For Quote Id')->relationship('requestForQuote', 'number')->searchable()->preload(),
            TextInput::make('rank')->label('Rank')->numeric(),
            TextInput::make('score')->label('Score')->numeric(),
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
            TextColumn::make('request_for_quote_id')->label('Request For Quote Id')->sortable()->toggleable(),
            TextColumn::make('rank')->label('Rank')->searchable()->sortable()->toggleable(),
            TextColumn::make('score')->label('Score')->searchable()->sortable()->toggleable(),
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
            'index' => ListVendorQuotes::route('/'),
            'create' => CreateVendorQuote::route('/create'),
            'edit' => EditVendorQuote::route('/{record}/edit'),
        ];
    }
}
