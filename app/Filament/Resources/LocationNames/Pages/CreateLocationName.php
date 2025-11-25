<?php

namespace App\Filament\Resources\LocationNames\Pages;

use App\Filament\Resources\LocationNames\LocationNameResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationName extends CreateRecord
{
    protected static string $resource = LocationNameResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->name;

        return $data;
    }
}
