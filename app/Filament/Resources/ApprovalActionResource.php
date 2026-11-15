<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalActionResource\Pages\CreateApprovalAction;
use App\Filament\Resources\ApprovalActionResource\Pages\EditApprovalAction;
use App\Filament\Resources\ApprovalActionResource\Pages\ListApprovalActions;
use App\Modules\Foundation\Models\ApprovalAction;
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

class ApprovalActionResource extends Resource
{
    protected static ?string $model = ApprovalAction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.approvalAction';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.approvalAction').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('request_id')->label('Request Id')->relationship('request', 'number')->searchable()->preload(),
            TextInput::make('level')->label('Level')->numeric(),
            Select::make('user_id')->label('User Id')->relationship('user', 'id')->searchable()->preload(),
            Select::make('action')->label('Action')->options(['approve' => 'approve', 'reject' => 'reject', 'delegate' => 'delegate', 'notify' => 'notify', 'create_pr' => 'create pr', 'hold_lot' => 'hold lot', 'block' => 'block'])->native(false),
            Textarea::make('comment')->label('Comment')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('request_id')->label('Request Id')->sortable()->toggleable(),
            TextColumn::make('level')->label('Level')->searchable()->sortable()->toggleable(),
            TextColumn::make('user_id')->label('User Id')->sortable()->toggleable(),
            TextColumn::make('action')->label('Action')->searchable()->sortable()->toggleable(),
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
            'index' => ListApprovalActions::route('/'),
            'create' => CreateApprovalAction::route('/create'),
            'edit' => EditApprovalAction::route('/{record}/edit'),
        ];
    }
}
