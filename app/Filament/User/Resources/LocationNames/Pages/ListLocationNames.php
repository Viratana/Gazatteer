<?php

namespace App\Filament\User\Resources\LocationNames\Pages;

use App\Filament\Exports\LocationExporter;
use App\Filament\User\Resources\LocationNames\LocationNameResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationNames extends ListRecords
{
    protected static string $resource = LocationNameResource::class;

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
