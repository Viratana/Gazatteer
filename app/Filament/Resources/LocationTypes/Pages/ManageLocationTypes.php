<?php

namespace App\Filament\Resources\LocationTypes\Pages;

use App\Filament\Exports\LocationTypeExporter;
use App\Filament\Imports\LocationTypeImporter;
use App\Filament\Resources\LocationTypes\LocationTypeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLocationTypes extends ManageRecords
{
    protected static string $resource = LocationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Location')
                ->icon('heroicon-o-plus')
                ->color('warning')
                ->mutateDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->user()->name;

                    return $data;
                }),
            ImportAction::make()
                ->importer(LocationTypeImporter::class)
                ->icon('heroicon-o-arrow-up-tray')
                ->label('Import')
                ->color('danger'),
            ExportAction::make()
                ->exporter(LocationTypeExporter::class)
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Export')
                ->color('danger'),
        ];
    }
    
    
    
}
