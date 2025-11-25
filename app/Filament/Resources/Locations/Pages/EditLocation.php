<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Resources\Locations\LocationResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-s-trash'),
            Action::make('back')
                ->label('Back')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('gray')
                ->outlined()
                ->url(fn () => LocationResource::getUrl('index')),
            ForceDeleteAction::make()
                ->icon('heroicon-s-trash')
                ->color('danger')
                ->requiresConfirmation(),
            RestoreAction::make()
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['parent_id'])) {
            $parent = \App\Models\Location::find($data['parent_id']);

            if ($parent) {
                $data['code'] = $parent->code . $data['code'];
            }
        }

        return $data;
    }

}
