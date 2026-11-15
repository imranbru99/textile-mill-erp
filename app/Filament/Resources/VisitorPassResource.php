<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorPassResource\Pages\CreateVisitorPass;
use App\Filament\Resources\VisitorPassResource\Pages\EditVisitorPass;
use App\Filament\Resources\VisitorPassResource\Pages\ListVisitorPasss;
use App\Modules\Gate\Models\VisitorPass;
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

class VisitorPassResource extends Resource
{
    protected static ?string $model = VisitorPass::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Gate & Security';

    protected static ?string $moduleKey = 'gate';

    protected static ?string $permission = 'gate.visitorPass';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'gate')
            && $user->permits((static::$permission ?? 'gate.visitorPass').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('visitor_name')->label('Visitor Name')->required()->maxLength(255),
            TextInput::make('host_name')->label('Host Name')->maxLength(255),
            TextInput::make('purpose')->label('Purpose')->maxLength(255),
            DateTimePicker::make('in_at')->label('In At'),
            DateTimePicker::make('out_at')->label('Out At'),
            TextInput::make('vehicle_no')->label('Vehicle No')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('visitor_name')->label('Visitor Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('host_name')->label('Host Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('purpose')->label('Purpose')->searchable()->sortable()->toggleable(),
            TextColumn::make('in_at')->label('In At')->searchable()->sortable()->toggleable(),
            TextColumn::make('out_at')->label('Out At')->searchable()->sortable()->toggleable(),
            TextColumn::make('vehicle_no')->label('Vehicle No')->searchable()->sortable()->toggleable(),
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
            'index' => ListVisitorPasss::route('/'),
            'create' => CreateVisitorPass::route('/create'),
            'edit' => EditVisitorPass::route('/{record}/edit'),
        ];
    }
}
