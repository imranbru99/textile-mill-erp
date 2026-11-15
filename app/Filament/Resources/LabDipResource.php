<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LabDipResource\Pages\CreateLabDip;
use App\Filament\Resources\LabDipResource\Pages\EditLabDip;
use App\Filament\Resources\LabDipResource\Pages\ListLabDips;
use App\Modules\Dyeing\Models\LabDip;
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

class LabDipResource extends Resource
{
    protected static ?string $model = LabDip::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Wet Processing';

    protected static ?string $moduleKey = 'dyeing';

    protected static ?string $permission = 'dyeing.labDip';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'dyeing')
            && $user->permits((static::$permission ?? 'dyeing.labDip').'.view');
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
            Select::make('sales_order_id')->label('Sales Order Id')->relationship('salesOrder', 'number')->searchable()->preload(),
            TextInput::make('shade_name')->label('Shade Name')->required()->maxLength(255),
            TextInput::make('lab_l')->label('Lab L')->numeric(),
            TextInput::make('lab_a')->label('Lab A')->numeric(),
            TextInput::make('lab_b')->label('Lab B')->numeric(),
            TextInput::make('delta_e')->label('Delta E')->numeric(),
            Select::make('recipe_id')->label('Recipe Id')->relationship('recipe', 'name')->searchable()->preload(),
            Select::make('buyer_decision')->label('Buyer Decision')->options(['pending' => 'pending', 'approved' => 'approved', 'rejected' => 'rejected'])->native(false),
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
            TextColumn::make('sales_order_id')->label('Sales Order Id')->sortable()->toggleable(),
            TextColumn::make('shade_name')->label('Shade Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('lab_l')->label('Lab L')->searchable()->sortable()->toggleable(),
            TextColumn::make('lab_a')->label('Lab A')->searchable()->sortable()->toggleable(),
            TextColumn::make('lab_b')->label('Lab B')->searchable()->sortable()->toggleable(),
            TextColumn::make('delta_e')->label('Delta E')->searchable()->sortable()->toggleable(),
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
            'index' => ListLabDips::route('/'),
            'create' => CreateLabDip::route('/create'),
            'edit' => EditLabDip::route('/{record}/edit'),
        ];
    }
}
