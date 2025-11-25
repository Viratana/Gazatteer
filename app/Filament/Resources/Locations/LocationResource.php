<?php

namespace App\Filament\Resources\Locations;

use App\Filament\Resources\Locations\Pages\CreateLocation;
use App\Filament\Resources\Locations\Pages\EditLocation;
use App\Filament\Resources\Locations\Pages\ListLocations;
use App\Filament\Resources\Locations\Schemas\LocationForm;
use App\Filament\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;
    
    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Location';

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
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
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected function getTabs(): array
    {
        return [
            'all' => TabsTab::make(__('All'))
                ->badge(fn () => Location::count()),
            'province' => TabsTab::make('ខេត្ត')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 1)
                )
                ->badge(fn () => Location::where('location_type_id', 1)->count()),
            'district' => TabsTab::make('ស្រុក')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 2)
                )
                ->badge(fn () => Location::where('location_type_id', 2)->count()),
            'commune' => TabsTab::make('ឃុំ')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 3)
                )
                ->badge(fn () => Location::where('location_type_id', 3)->count()),
            'village' => TabsTab::make('ភូមិ')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 4)
                )
                ->badge(fn () => Location::where('location_type_id', 4)->count()),
            'city' => TabsTab::make('ក្រុង')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 5)
                )
                ->badge(fn () => Location::where('location_type_id', 5)->count()),
            'sangkat' => TabsTab::make('សង្កាត់')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 6)
                )
                ->badge(fn () => Location::where('location_type_id', 6)->count()),
            'capital' => TabsTab::make('រាជធានី')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 7)
                )
                ->badge(fn () => Location::where('location_type_id', 7)->count()),
            'khan' => TabsTab::make('ខណ្ឌ')
                ->modifyQueryUsing(fn ($query) => 
                    $query->where('location_type_id', 8)
                )
                ->badge(fn () => Location::where('location_type_id', 8)->count()),
        ];
    }
}
