<?php

namespace App\Filament\User\Resources\Locations\Pages;

use App\Filament\Exports\LocationExporter;
use App\Filament\User\Resources\Locations\LocationResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            ExportAction::make()
                ->exporter(LocationExporter::class)
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Export')
                ->color('danger'),
        ];
    }
}
