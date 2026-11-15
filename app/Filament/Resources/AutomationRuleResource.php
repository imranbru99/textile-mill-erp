<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AutomationRuleResource\Pages\CreateAutomationRule;
use App\Filament\Resources\AutomationRuleResource\Pages\EditAutomationRule;
use App\Filament\Resources\AutomationRuleResource\Pages\ListAutomationRules;
use App\Modules\Issues\Models\AutomationRule;
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

class AutomationRuleResource extends Resource
{
    protected static ?string $model = AutomationRule::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Issues & Automation';

    protected static ?string $moduleKey = 'issues';

    protected static ?string $permission = 'issues.automationRule';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'issues')
            && $user->permits((static::$permission ?? 'issues.automationRule').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('module_key')->label('Module Key')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('trigger')->label('Trigger')->required()->maxLength(255),
            TextInput::make('threshold')->label('Threshold')->numeric(),
            Toggle::make('is_active')->label('Is Active'),
            Toggle::make('auto_execute')->label('Auto Execute'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('module_key')->label('Module Key')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('trigger')->label('Trigger')->searchable()->sortable()->toggleable(),
            TextColumn::make('threshold')->label('Threshold')->searchable()->sortable()->toggleable(),
            TextColumn::make('is_active')->label('Is Active')->searchable()->sortable()->toggleable(),
            TextColumn::make('auto_execute')->label('Auto Execute')->searchable()->sortable()->toggleable(),
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
            'index' => ListAutomationRules::route('/'),
            'create' => CreateAutomationRule::route('/create'),
            'edit' => EditAutomationRule::route('/{record}/edit'),
        ];
    }
}
