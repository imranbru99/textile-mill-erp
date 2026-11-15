<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MillIssueResource\Pages\CreateMillIssue;
use App\Filament\Resources\MillIssueResource\Pages\EditMillIssue;
use App\Filament\Resources\MillIssueResource\Pages\ListMillIssues;
use App\Modules\Issues\Models\MillIssue;
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

class MillIssueResource extends Resource
{
    protected static ?string $model = MillIssue::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Issues & Automation';

    protected static ?string $moduleKey = 'issues';

    protected static ?string $permission = 'issues.millIssue';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'issues')
            && $user->permits((static::$permission ?? 'issues.millIssue').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('issue_type')->label('Issue Type')->required()->maxLength(255),
            Select::make('severity')->label('Severity')->options(['low' => 'low', 'medium' => 'medium', 'high' => 'high', 'critical' => 'critical'])->native(false),
            TextInput::make('source_type')->label('Source Type')->maxLength(255),
            TextInput::make('source_id')->label('Source Id')->numeric(),
            Select::make('owner_id')->label('Owner Id')->relationship('owner', 'id')->searchable()->preload(),
            DateTimePicker::make('due_at')->label('Due At'),
            TextInput::make('sla_hours')->label('Sla Hours')->numeric(),
            Textarea::make('recommendation')->label('Recommendation')->columnSpanFull(),
            Textarea::make('resolution')->label('Resolution')->columnSpanFull(),
            Select::make('knowledge_article_id')->label('Knowledge Article Id')->relationship('knowledgeArticle', 'title')->searchable()->preload(),
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
            TextColumn::make('issue_type')->label('Issue Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('severity')->label('Severity')->searchable()->sortable()->toggleable(),
            TextColumn::make('source_type')->label('Source Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('source_id')->label('Source Id')->searchable()->sortable()->toggleable(),
            TextColumn::make('owner_id')->label('Owner Id')->sortable()->toggleable(),
            TextColumn::make('due_at')->label('Due At')->searchable()->sortable()->toggleable(),
            TextColumn::make('sla_hours')->label('Sla Hours')->searchable()->sortable()->toggleable(),
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
            'index' => ListMillIssues::route('/'),
            'create' => CreateMillIssue::route('/create'),
            'edit' => EditMillIssue::route('/{record}/edit'),
        ];
    }
}
