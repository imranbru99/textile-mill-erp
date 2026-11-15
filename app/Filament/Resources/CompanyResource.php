<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages\CreateCompany;
use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Filament\Resources\CompanyResource\Pages\ListCompanys;
use App\Modules\Foundation\Models\Company;
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

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Organization';

    protected static ?string $moduleKey = 'foundation';

    protected static ?string $permission = 'foundation.company';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ModuleRegistry::enabled(static::$moduleKey ?? 'foundation')
            && $user->permits((static::$permission ?? 'foundation.company').'.view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Code')->required()->maxLength(255),
            TextInput::make('name')->label('Name')->required()->maxLength(255),
            TextInput::make('legal_name')->label('Legal Name')->maxLength(255),
            TextInput::make('brand_name')->label('Brand Name')->maxLength(255),
            TextInput::make('country')->label('Country')->required()->maxLength(255),
            TextInput::make('currency_code')->label('Currency Code')->required()->maxLength(255),
            TextInput::make('timezone')->label('Timezone')->required()->maxLength(255),
            TextInput::make('fiscal_start_month')->label('Fiscal Start Month')->numeric(),
            TextInput::make('vat_rate')->label('Vat Rate')->numeric(),
            Select::make('valuation_method')->label('Valuation Method')->options(['fifo' => 'fifo', 'weighted_average' => 'weighted average'])->native(false),
            Textarea::make('address')->label('Address')->columnSpanFull(),
            TextInput::make('phone')->label('Phone')->maxLength(255),
            TextInput::make('email')->label('Email')->maxLength(255),
            TextInput::make('website')->label('Website')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('legal_name')->label('Legal Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('brand_name')->label('Brand Name')->searchable()->sortable()->toggleable(),
            TextColumn::make('country')->label('Country')->searchable()->sortable()->toggleable(),
            TextColumn::make('currency_code')->label('Currency Code')->searchable()->sortable()->toggleable(),
            TextColumn::make('timezone')->label('Timezone')->searchable()->sortable()->toggleable(),
            TextColumn::make('fiscal_start_month')->label('Fiscal Start Month')->searchable()->sortable()->toggleable(),
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
            'index' => ListCompanys::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
