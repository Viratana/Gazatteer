<?php

namespace App\Filament\Resources\LocationCodes\Pages;

use App\Filament\Resources\LocationCodes\LocationCodeResource;
use App\Models\LocationCode;   // <-- base model for the resource
use App\Models\LocationType;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ListLocationCodes extends ListRecords
{
    protected static string $resource = LocationCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => TabsTab::make(__('All'))
                ->badge(fn () => LocationCode::query()->count()),
        ];
        $types = LocationType::query()
            ->when(
                Schema::hasColumn('location_types', 'sort_order'),
                fn ($q) => $q->orderBy('sort_order'),
                fn ($q) => $q->orderBy('id')
            )
            ->get();
        foreach ($types as $type) {
            $key   = (string) ($type->slug ?? $type->id);
            $label = method_exists($type, 'getDisplayNameAttribute')
                ? $type->display_name
                : ($type->name ?? $type->title ?? "Type {$type->id}");
            $tabs[$key] = TabsTab::make($label)
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('location', fn (Builder $q) =>
                        $q->where('location_type_id', $type->getKey())
                    )
                )
                ->badge(fn () => LocationCode::query()
                    ->whereHas('location', fn (Builder $q) =>
                        $q->where('location_type_id', $type->getKey())
                    )
                    ->count()
                );
        }
        return $tabs;
    }
}
