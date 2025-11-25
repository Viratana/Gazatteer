<?php

namespace App\Filament\Resources\LocationCodes\Pages;

use App\Filament\Resources\LocationCodes\LocationCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLocationCode extends EditRecord
{
    protected static string $resource = LocationCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
