<?php

namespace App\Filament\User\Resources\LocationCodes\Pages;

use App\Filament\User\Resources\LocationCodes\LocationCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationCode extends CreateRecord
{
    protected static string $resource = LocationCodeResource::class;
}
