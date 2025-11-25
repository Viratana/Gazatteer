<?php

namespace App\Filament\Resources\LocationCodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LocationCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('locationType.name')
                            ->columnSpanFull()
                            ->label('Location Type : '),
                        TextEntry::make('parent.code')
                            ->label('Parent Name : ')
                            ->copyable()
                            ->copyMessage('Copied!'),
                        TextEntry::make('parent.name_kh')
                            ->label('Parent Name : ')
                            ->copyable()
                            ->copyMessage('Copied!'),
                                Grid::make(3)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('code')
                                            ->label('Code : ')
                                            ->copyable()
                                            ->copyMessage('Copied!'),
                                        TextEntry::make('name_kh')
                                            ->label('NameKH : ')
                                            ->copyable()
                                            ->copyMessage('Copied!'),
                                        TextEntry::make('name_en')
                                            ->label('NameEN : ')
                                            ->copyable()
                                            ->copyMessage('Copied!'),
                                    ]),
                        TextEntry::make('postal_code')
                            ->label('Postal Code : ')
                            ->copyable()
                            ->copyMessage('Copied!'),
                        TextEntry::make('coordination')
                            ->label('Coordination : ')
                            ->copyable()
                            ->copyMessage('Copied!'),
                                Grid::make(2)
                                ->columnSpanFull()
                                ->schema([
                                    TextEntry::make('reference')
                                    ->label('Reference : ')
                                    ->copyable()
                                    ->copyMessage('Copied!'),
                                    TextEntry::make('note')
                                        ->label('Note : ')
                                        ->copyable()
                                        ->copyMessage('Copied!'),
                                    TextEntry::make('note_by_checker')
                                        ->label('Note By Checker : ')
                                        ->copyable()
                                        ->copyMessage('Copied!')
                                        ->columnSpanFull(),
                                ]),
                        TextEntry::make('created_by')
                            ->columnSpanFull()
                            ->label('Created By : '),
                        TextEntry::make('created_at')
                            ->label('Created At : ')
                            ->badge()
                            ->dateTime()
                            ->color('success'),
                        TextEntry::make('updated_at')
                            ->label('Updated At : ')
                            ->badge()
                            ->dateTime()
                            ->color('warning'),
                ]),
            ]);
    }
}
