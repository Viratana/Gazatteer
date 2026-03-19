<?php

namespace App\Filament\User\Resources\LocationCodes\Pages;

use App\Filament\Exports\LocationExporter;
use App\Filament\User\Resources\LocationCodes\LocationCodeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationCodes extends ListRecords
{
    protected static string $resource = LocationCodeResource::class;

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
