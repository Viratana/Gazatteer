<?php

namespace App\Filament\User\Resources\LocationCodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LocationCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('location_type_id')
                    ->relationship('locationType', 'name')
                    ->default(null),
                Select::make('location_id')
                    ->relationship('location', 'id')
                    ->required(),
                Select::make('parent_id')
                    ->relationship('parent', 'id')
                    ->default(null),
                TextInput::make('code')
                    ->required(),
                TextInput::make('name_kh')
                    ->required(),
                TextInput::make('name_en')
                    ->default(null),
                TextInput::make('postal_code')
                    ->default(null),
                Textarea::make('reference')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('coordination')
                    ->default(null),
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
