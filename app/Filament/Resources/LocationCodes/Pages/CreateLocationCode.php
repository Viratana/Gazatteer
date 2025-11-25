<?php

namespace App\Filament\Resources\LocationCodes\Pages;

use App\Filament\Resources\LocationCodes\LocationCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationCode extends CreateRecord
{
    protected static string $resource = LocationCodeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->name;

        return $data;
    }
}
