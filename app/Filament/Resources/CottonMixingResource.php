<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CottonMixingResource\Pages\CreateCottonMixing;
use App\Filament\Resources\CottonMixingResource\Pages\EditCottonMixing;
use App\Filament\Resources\CottonMixingResource\Pages\ListCottonMixings;
use App\Modules\Spinning\Models\CottonMixing;
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

class CottonMixingResource extends Resource
{
    protected static ?string $model = CottonMixing::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Spinning';

    protected static ?string $moduleKey = 'spinning';

    protected static ?string $permission = 'spinning.cottonMixing';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'spinning')
            && $user->permits((static::$permission ?? 'spinning.cottonMixing').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('target_micronaire')->label('Target Micronaire')->numeric(),
            TextInput::make('target_staple')->label('Target Staple')->numeric(),
            TextInput::make('target_strength')->label('Target Strength')->numeric(),
            TextInput::make('laydown_kg')->label('Laydown Kg')->numeric(),
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
            TextColumn::make('target_micronaire')->label('Target Micronaire')->searchable()->sortable()->toggleable(),
            TextColumn::make('target_staple')->label('Target Staple')->searchable()->sortable()->toggleable(),
            TextColumn::make('target_strength')->label('Target Strength')->searchable()->sortable()->toggleable(),
            TextColumn::make('laydown_kg')->label('Laydown Kg')->searchable()->sortable()->toggleable(),
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
            'index' => ListCottonMixings::route('/'),
            'create' => CreateCottonMixing::route('/create'),
            'edit' => EditCottonMixing::route('/{record}/edit'),
        ];
    }
}
