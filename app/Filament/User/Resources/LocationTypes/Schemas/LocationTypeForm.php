<?php

namespace App\Filament\User\Resources\LocationTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LocationTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('created_by')
                    ->default(null),
            ]);
    }
}
