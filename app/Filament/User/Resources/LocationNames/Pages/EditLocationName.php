<?php

namespace App\Filament\User\Resources\LocationNames\Pages;

use App\Filament\User\Resources\LocationNames\LocationNameResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLocationName extends EditRecord
{
    protected static string $resource = LocationNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
