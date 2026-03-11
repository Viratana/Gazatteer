<?php

namespace App\Filament\User\Resources\LocationNames;

use App\Filament\User\Resources\LocationNames\Pages\CreateLocationName;
use App\Filament\User\Resources\LocationNames\Pages\EditLocationName;
use App\Filament\User\Resources\LocationNames\Pages\ListLocationNames;
use App\Filament\User\Resources\LocationNames\Schemas\LocationNameForm;
use App\Filament\User\Resources\LocationNames\Tables\LocationNamesTable;
use App\Models\LocationName;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LocationNameResource extends Resource
{
    protected static ?string $model = LocationName::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static ?int $navigationSort = 3;

    protected static string | UnitEnum | null $navigationGroup = 'Location';

    public static function form(Schema $schema): Schema
    {
        return LocationNameForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationNamesTable::configure($table);
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
            'index' => ListLocationNames::route('/'),
            // 'create' => CreateLocationName::route('/create'),
            // 'edit' => EditLocationName::route('/{record}/edit'),
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
}
