<?php

namespace App\Filament\Resources\LocationNames\Pages;

use App\Filament\Resources\LocationNames\LocationNameResource;
use App\Models\LocationName;
use App\Models\LocationType;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ListLocationNames extends ListRecords
{
    protected static string $resource = LocationNameResource::class;

    /**
     * Header actions (New record)
     */
    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tabstab::make(__('All'))
                ->badge(fn () => LocationName::query()->count()),
        ];
        $types = LocationType::query()
            ->when(
                Schema::hasColumn('location_types', 'sort_order'),
                fn ($q) => $q->orderBy('sort_order'),
                fn ($q) => $q->orderBy('id')
            )
            ->get();
        foreach ($types as $type) {
            $key = (string) ($type->slug ?? $type->id);
            $label = $type->display_name
                ?? $type->name
                ?? $type->title
                ?? "Type {$type->id}";
            $tabs[$key] = Tabstab::make($label)
                ->modifyQueryUsing(function (Builder $query) use ($type): void {
                    $query->whereHas(
                        'location',
                        fn (Builder $q) => $q->where('location_type_id', $type->getKey())
                    );
                })
                ->badge(fn () => LocationName::query()
                    ->whereHas(
                        'location',
                        fn (Builder $q) => $q->where('location_type_id', $type->getKey())
                    )
                    ->count()
                );
        }

        return $tabs;
    }
}
