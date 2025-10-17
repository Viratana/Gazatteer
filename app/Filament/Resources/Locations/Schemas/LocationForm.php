<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                ->schema([
                        Select::make('location_type_id')
                            ->relationship('locationType', 'name', modifyQueryUsing: fn(Builder $query) => $query->orderBy('id', 'asc'))
                            ->required()
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('Parent Location')
                            ->relationship('parent', 'name_kh', function (Builder $query, Get $get) {
                                $locationTypeId = $get('location_type_id');
                                    // If creating a District (location_type_id = 2), show Provinces as parents (location_type_id = 1)
                                    if ($locationTypeId == 2) {
                                        $query->where('location_type_id', 1);
                                    }
                                    // If creating a Commune (location_type_id = 3), show Districts as parents (location_type_id = 2)
                                    if ($locationTypeId == 3) {
                                        $query->where('location_type_id', 2);
                                    }
                                    // If creating a Commune (location_type_id = ), show Districts as parents (location_type_id = 3)
                                    if ($locationTypeId == 4) {
                                        $query->where('location_type_id', 3);
                                    }
                                })
                            ->searchable()
                            ->nullable()
                            ->preload(),
                        Grid::make(3)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('code')
                                    ->required(),
                                TextInput::make('postal_code')
                                    ->unique(),
                                TextInput::make('coordination')
                                    ->unique(),
                            ]),
                    TextInput::make('name_kh')
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('name_en')
                        ->unique(ignoreRecord: true),
                    Textarea::make('reference'),
                    Textarea::make('note'),
                ]),
            ]);
    }
}
