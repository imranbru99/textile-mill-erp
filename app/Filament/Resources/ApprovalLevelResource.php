<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalLevelResource\Pages\CreateApprovalLevel;
use App\Filament\Resources\ApprovalLevelResource\Pages\EditApprovalLevel;
use App\Filament\Resources\ApprovalLevelResource\Pages\ListApprovalLevels;
use App\Modules\Foundation\Models\ApprovalLevel;
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

class ApprovalLevelResource extends Resource
{
    protected static ?string $model = ApprovalLevel::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.approvalLevel';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.approvalLevel').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('workflow_id')->label('Workflow Id')->relationship('workflow', 'name')->searchable()->preload(),
            TextInput::make('level')->label('Level')->numeric(),
            TextInput::make('role_slug')->label('Role Slug')->required()->maxLength(255),
            TextInput::make('min_amount')->label('Min Amount')->numeric(),
            TextInput::make('max_amount')->label('Max Amount')->numeric(),
            TextInput::make('escalate_hours')->label('Escalate Hours')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('workflow_id')->label('Workflow Id')->sortable()->toggleable(),
            TextColumn::make('level')->label('Level')->searchable()->sortable()->toggleable(),
            TextColumn::make('role_slug')->label('Role Slug')->searchable()->sortable()->toggleable(),
            TextColumn::make('min_amount')->label('Min Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('max_amount')->label('Max Amount')->searchable()->sortable()->toggleable(),
            TextColumn::make('escalate_hours')->label('Escalate Hours')->searchable()->sortable()->toggleable(),
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
            'index' => ListApprovalLevels::route('/'),
            'create' => CreateApprovalLevel::route('/create'),
            'edit' => EditApprovalLevel::route('/{record}/edit'),
        ];
    }
}
