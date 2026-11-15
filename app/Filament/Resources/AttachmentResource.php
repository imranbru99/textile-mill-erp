<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AttachmentResource\Pages\CreateAttachment;
use App\Filament\Resources\AttachmentResource\Pages\EditAttachment;
use App\Filament\Resources\AttachmentResource\Pages\ListAttachments;
use App\Modules\Foundation\Models\Attachment;
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

class AttachmentResource extends Resource
{
    protected static ?string $model = Attachment::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.attachment';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.attachment').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->label('Branch Id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('attachable_type')->label('Attachable Type')->required()->maxLength(255),
            TextInput::make('attachable_id')->label('Attachable Id')->numeric(),
            TextInput::make('disk')->label('Disk')->required()->maxLength(255),
            TextInput::make('path')->label('Path')->required()->maxLength(255),
            TextInput::make('original_name')->label('Original Name')->required()->maxLength(255),
            TextInput::make('mime')->label('Mime')->maxLength(255),
            TextInput::make('size')->label('Size')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('branch_id')->label('Branch Id')->sortable()->toggleable(),
            TextColumn::make('attachable_type')->label('Attachable Type')->searchable()->sortable()->toggleable(),
            TextColumn::make('attachable_id')->label('Attachable Id')->searchable()->sortable()->toggleable(),
            TextColumn::make('disk')->label('Disk')->searchable()->sortable()->toggleable(),
            TextColumn::make('path')->label('Path')->searchable()->sortable()->toggleable(),
            TextColumn::make('original_name')->label('Original Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('mime')->label('Mime')->searchable()->sortable()->toggleable(),
            TextColumn::make('size')->label('Size')->searchable()->sortable()->toggleable(),
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
            'index' => ListAttachments::route('/'),
            'create' => CreateAttachment::route('/create'),
            'edit' => EditAttachment::route('/{record}/edit'),
        ];
    }
}
