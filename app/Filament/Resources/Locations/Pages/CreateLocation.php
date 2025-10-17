<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Resources\Locations\LocationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->name;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $location = static::getModel()::create($data);

        $location->codes()->create([
            'code'          => $data['code'],
            'postal_code'   => $data['postal_code'],
            'reference'     => $data['reference'],
            'coordination'  => $data['coordination'],
            'note'          => $data['note'],
            'created_by'    => $data['created_by'],
        ]);

        $location->locationNames()->create([
            'name_kh'       => $data['name_kh'],
            'name_en'       => $data['name_en'],
            'reference'     => $data['reference'],
            'coordination'  => $data['coordination'],
            'note'          => $data['note'],
            'created_by'    => $data['created_by'],
        ]);
        return $location;
    }
}


