<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PpeIssueResource\Pages\CreatePpeIssue;
use App\Filament\Resources\PpeIssueResource\Pages\EditPpeIssue;
use App\Filament\Resources\PpeIssueResource\Pages\ListPpeIssues;
use App\Modules\People\Models\PpeIssue;
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

class PpeIssueResource extends Resource
{
    protected static ?string $model = PpeIssue::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|\UnitEnum|null $navigationGroup = 'HR & Payroll';

    protected static ?string $moduleKey = 'hr';

    protected static ?string $permission = 'hr.ppeIssue';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'hr')
            && $user->permits((static::$permission ?? 'hr.ppeIssue').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')->label('Employee Id')->relationship('employee', 'name')->searchable()->preload(),
            Select::make('item_id')->label('Item Id')->relationship('item', 'name')->searchable()->preload(),
            TextInput::make('qty')->label('Qty')->numeric(),
            DatePicker::make('issued_on')->label('Issued On'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('employee_id')->label('Employee Id')->sortable()->toggleable(),
            TextColumn::make('item_id')->label('Item Id')->sortable()->toggleable(),
            TextColumn::make('qty')->label('Qty')->searchable()->sortable()->toggleable(),
            TextColumn::make('issued_on')->label('Issued On')->searchable()->sortable()->toggleable(),
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
            'index' => ListPpeIssues::route('/'),
            'create' => CreatePpeIssue::route('/create'),
            'edit' => EditPpeIssue::route('/{record}/edit'),
        ];
    }
}
