<?php

namespace App\Filament\User\Resources\LocationTypes\Pages;

use App\Filament\Exports\LocationExporter;
use App\Filament\User\Resources\LocationTypes\LocationTypeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationTypes extends ListRecords
{
    protected static string $resource = LocationTypeResource::class;

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
