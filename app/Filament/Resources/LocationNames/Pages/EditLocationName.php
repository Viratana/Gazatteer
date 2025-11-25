<?php

namespace App\Filament\Resources\LocationNames\Pages;

use App\Filament\Resources\LocationNames\LocationNameResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLocationName extends EditRecord
{
    protected static string $resource = LocationNameResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-m-trash'),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
