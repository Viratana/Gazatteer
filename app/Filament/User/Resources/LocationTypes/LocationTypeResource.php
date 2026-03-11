<?php

namespace App\Filament\User\Resources\LocationTypes;

use App\Filament\User\Resources\LocationTypes\Pages\CreateLocationType;
use App\Filament\User\Resources\LocationTypes\Pages\EditLocationType;
use App\Filament\User\Resources\LocationTypes\Pages\ListLocationTypes;
use App\Filament\User\Resources\LocationTypes\Schemas\LocationTypeForm;
use App\Filament\User\Resources\LocationTypes\Tables\LocationTypesTable;
use App\Models\LocationType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LocationTypeResource extends Resource
{
    protected static ?string $model = LocationType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;

    protected static string | UnitEnum | null $navigationGroup = 'Location';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LocationTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocationTypes::route('/'),
            // 'create' => CreateLocationType::route('/create'),
            // 'edit' => EditLocationType::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        return false; 
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
