<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Exports\LocationExporter;
use App\Filament\Exports\LocationTypeExporter;
use App\Filament\Imports\LocationImporter;
use App\Filament\Imports\LocationTypeImporter;
use App\Filament\Resources\Locations\LocationResource;
use App\Models\Location;
use App\Models\LocationType;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Database\Eloquent\Builder;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Location')
                ->icon('heroicon-o-plus')
                ->color('warning'),
            ImportAction::make()
                ->importer(LocationImporter::class)
                ->icon('heroicon-o-arrow-up-tray')
                ->label('Import')
                ->color('danger'),
            ExportAction::make()
                ->exporter(LocationExporter::class)
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Export')
                ->color('danger'),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => TabsTab::make(__('All'))
                ->badge(fn () => Location::query()->count()),
                
        ];
        // foreach (LocationType::orderBy('id')->get() as $type) {
        //     $tabs['type-'.$type->id] = TabsTab::make($type->name)
        //         ->modifyQueryUsing(function (Builder $query) use ($type) {
        //             $query->where('location_type_id', $type->id);
        //         })
        //         ->badge(fn () => Location::where('location_type_id', $type->id)->count());
        // }
        LocationType::query()
            ->orderBy('id')
            ->get()
            ->each(function (LocationType $type) use (&$tabs) {
                $key  = (string) ($type->slug ?? $type->id);
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
