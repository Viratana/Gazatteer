<?php

namespace App\Filament\Resources\Locations\Tables;

use App\Filament\Exports\LocationExporter;
use App\Filament\Imports\LocationImporter;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LocationsTable
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
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->alignment('center')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("CAST(code AS UNSIGNED) {$direction}");
                    })
                    ->searchable(),
                TextColumn::make('name_kh')
                    ->label('NameKH')
                    ->searchable(),
                TextColumn::make('name_en')
                    ->label('NameEN')
                    ->searchable(),
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
                    ->label('Note By Checker')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ActionGroup::make([
                    EditAction::make(),
                    ForceDeleteAction::make(),
                    DeleteAction::make(),
                ])         
            ])
            // ->headerActions([
            //     ImportAction::make()
            //         ->importer(LocationImporter::class)
            //         ->icon('heroicon-o-arrow-up-tray')
            //         ->label('Import')
            //         ->color('danger'),
            //     ExportAction::make()
            //         ->exporter(LocationExporter::class)
            //         ->icon('heroicon-o-arrow-down-tray')
            //         ->label('Export')
            //         ->color('danger'),  
            // ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make()
                    ]),
                ExportBulkAction::make()
                    ->exporter(LocationExporter::class)
                    ->label('Export Selected')
                    ->color('danger'),
            ]);
    }
}
