<?php

namespace App\Filament\Resources\LocationNames;

use App\Filament\Resources\LocationNames\Pages\CreateLocationName;
use App\Filament\Resources\LocationNames\Pages\EditLocationName;
use App\Filament\Resources\LocationNames\Pages\ListLocationNames;
use App\Filament\Resources\LocationNames\Pages\ViewLocationName;
use App\Filament\Resources\LocationNames\Schemas\LocationNameForm;
use App\Filament\Resources\LocationNames\Tables\LocationNamesTable;
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

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LocationNameForm::infolist($schema);
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
            'create' => CreateLocationName::route('/create'),
            'edit' => EditLocationName::route('/{record}/edit'),
            'view' => ViewLocationName::route('{record}')
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
