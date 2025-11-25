<?php

namespace App\Filament\Resources\LocationCodes\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LocationCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('locationType.name')
                    ->label('Location Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('parent.code')
                    ->label('Parent Code')
                    ->alignment('center'),
                    // ->default(function(){
                    //     return 'Hello';
                    // }),
                TextColumn::make('parent.name_kh')
                    ->label('Parent Name')
                    // ->default('—')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('code')
                    ->label('Code')
                    ->alignment('center')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name_kh')
                    ->label('NameKH')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name_en')
                    ->label('NameEN')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postal_code')
                    ->label('Postal Code')
                    ->searchable(),
                TextColumn::make('coordination')
                    ->label('Coordination'),
                TextColumn::make('reference')
                    ->label('Reference'),
                TextColumn::make('note')
                    ->label('Note'),
                TextColumn::make('note_by_checker')
                    ->label('Note By Checker'),
                TextColumn::make('created_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
