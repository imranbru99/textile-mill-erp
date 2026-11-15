<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CapaResource\Pages\CreateCapa;
use App\Filament\Resources\CapaResource\Pages\EditCapa;
use App\Filament\Resources\CapaResource\Pages\ListCapas;
use App\Modules\Quality\Models\Capa;
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

class CapaResource extends Resource
{
    protected static ?string $model = Capa::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

    protected static ?string $permission = 'quality.capa';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'quality')
            && $user->permits((static::$permission ?? 'quality.capa').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('ncr_id')->label('Ncr Id')->relationship('ncr', 'number')->searchable()->preload(),
            Select::make('action')->label('Action')->options(['approve' => 'approve', 'reject' => 'reject', 'delegate' => 'delegate', 'notify' => 'notify', 'create_pr' => 'create pr', 'hold_lot' => 'hold lot', 'block' => 'block'])->native(false),
            Select::make('owner_id')->label('Owner Id')->relationship('owner', 'id')->searchable()->preload(),
            DatePicker::make('due_date')->label('Due Date'),
            DateTimePicker::make('verified_at')->label('Verified At'),
            Select::make('status')->label('Status')->options(['draft' => 'draft', 'submitted' => 'submitted', 'approved' => 'approved', 'rejected' => 'rejected', 'posted' => 'posted', 'cancelled' => 'cancelled', 'closed' => 'closed', 'released' => 'released', 'hold' => 'hold'])->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('ncr_id')->label('Ncr Id')->sortable()->toggleable(),
            TextColumn::make('owner_id')->label('Owner Id')->sortable()->toggleable(),
            TextColumn::make('due_date')->label('Due Date')->searchable()->sortable()->toggleable(),
            TextColumn::make('verified_at')->label('Verified At')->searchable()->sortable()->toggleable(),
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
            'index' => ListCapas::route('/'),
            'create' => CreateCapa::route('/create'),
            'edit' => EditCapa::route('/{record}/edit'),
        ];
    }
}
