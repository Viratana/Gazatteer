<?php

namespace App\Filament\User\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('location_type_id')
                    ->relationship('locationType', 'name')
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'id')
                    ->default(null),
                TextInput::make('code')
                    ->required(),
                TextInput::make('postal_code')
                    ->default(null),
                TextInput::make('coordination')
                    ->default(null),
                TextInput::make('name_kh')
                    ->required(),
                TextInput::make('name_en')
                    ->default(null),
                Textarea::make('reference')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('note_by_checker')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->default(null),
            ]);
    }
}
