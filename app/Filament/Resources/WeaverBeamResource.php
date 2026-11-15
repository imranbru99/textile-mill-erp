<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WeaverBeamResource\Pages\CreateWeaverBeam;
use App\Filament\Resources\WeaverBeamResource\Pages\EditWeaverBeam;
use App\Filament\Resources\WeaverBeamResource\Pages\ListWeaverBeams;
use App\Modules\Weaving\Models\WeaverBeam;
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

class WeaverBeamResource extends Resource
{
    protected static ?string $model = WeaverBeam::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|\UnitEnum|null $navigationGroup = 'Weaving';

    protected static ?string $moduleKey = 'weaving';

    protected static ?string $permission = 'weaving.weaverBeam';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'weaving')
            && $user->permits((static::$permission ?? 'weaving.weaverBeam').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('beam_no')->label('Beam No')->required()->maxLength(255),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('ends')->label('Ends')->numeric(),
            TextInput::make('length_meter')->label('Length Meter')->numeric(),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
            TextInput::make('size_pickup')->label('Size Pickup')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('beam_no')->label('Beam No')->searchable()->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('ends')->label('Ends')->searchable()->sortable()->toggleable(),
            TextColumn::make('length_meter')->label('Length Meter')->searchable()->sortable()->toggleable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable()->toggleable(),
            TextColumn::make('size_pickup')->label('Size Pickup')->searchable()->sortable()->toggleable(),
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
            'index' => ListWeaverBeams::route('/'),
            'create' => CreateWeaverBeam::route('/create'),
            'edit' => EditWeaverBeam::route('/{record}/edit'),
        ];
    }
}
