<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Resources\Locations\LocationResource;
use App\Models\Location;
use App\Models\LocationType;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Database\Eloquent\Builder;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

      public function getTabs(): array
    {
        $tabs = [
            'all' => TabsTab::make(__('All'))
                ->badge(fn () => Location::query()->count()),
        ];
        LocationType::query()
            ->orderBy('id')
            ->get()
            ->each(function (LocationType $type) use (&$tabs) {
                $key  = (string) ($type->slug ?? $type->id); // use slug if you have it
                $name = $type->name ?? $type->title ?? "Type {$type->id}";

                $tabs[$key] = TabsTab::make($name)
                    ->modifyQueryUsing(
                        fn (Builder $query) =>
                            $query->where('location_type_id', $type->getKey())
                    )
                    ->badge(fn () => Location::where('location_type_id', $type->getKey())->count());
            });

        return $tabs;
    }
}
