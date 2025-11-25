<?php

namespace App\Filament\Resources\LocationNames\Pages;

use App\Filament\Resources\LocationNames\LocationNameResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLocationName extends ViewRecord
{
    protected static string $resource = LocationNameResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Back')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('gray')
                ->outlined()
                ->url(fn () => LocationNameResource::getUrl('index')),
        ];
    }
    
}
