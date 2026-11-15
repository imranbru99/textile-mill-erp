<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalRequestResource\Pages\CreateApprovalRequest;
use App\Filament\Resources\ApprovalRequestResource\Pages\EditApprovalRequest;
use App\Filament\Resources\ApprovalRequestResource\Pages\ListApprovalRequests;
use App\Modules\Foundation\Models\ApprovalRequest;
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

class ApprovalRequestResource extends Resource
{
    protected static ?string $model = ApprovalRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.approvalRequest';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.approvalRequest').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('document_type')->label('Document Type')->required()->maxLength(255),
            TextInput::make('document_id')->label('Document Id')->numeric(),
            TextInput::make('current_level')->label('Current Level')->numeric(),
            Select::make('requested_by')->label('Requested By')->relationship('requestedBy', 'id')->searchable()->preload(),
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
            TextColumn::make('document_type')->label('Document Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('document_id')->label('Document Id')->searchable()->sortable()->toggleable(),
            TextColumn::make('current_level')->label('Current Level')->searchable()->sortable()->toggleable(),
            TextColumn::make('requested_by')->label('Requested By')->sortable()->toggleable(),
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
            'index' => ListApprovalRequests::route('/'),
            'create' => CreateApprovalRequest::route('/create'),
            'edit' => EditApprovalRequest::route('/{record}/edit'),
        ];
    }
}
