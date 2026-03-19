<?php

namespace App\Filament\User\Resources\Locations\Tables;

use App\Models\Location;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
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

                TextColumn::make('parent.name_kh')
                    ->label('Parent Name')
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
                Filter::make('location_hierarchy')
                    ->label('') // ✅ no big title
                    ->columnSpanFull()
                    ->form([
                        Group::make([
                            Select::make('province_id')
                                ->label('ខេត្ត / ក្រុង')
                                ->placeholder('ជ្រើសរើសខេត្ត/ក្រុង')
                                ->searchable()
                                ->preload()
                                ->options(fn () => Location::query()
                                    ->where('location_type_id', 1)
                                    ->pluck('name_kh', 'id'))
                                ->live()
                                ->afterStateUpdated(function ($set) {
                                    $set('district_id', null);
                                    $set('commune_id', null);
                                    $set('village_id', null);
                                }),

                            Select::make('district_id')
                                ->label('ស្រុក / ខណ្ឌ')
                                ->placeholder('ជ្រើសរើសស្រុក/ខណ្ឌ')
                                ->searchable()
                                ->preload()
                                ->options(function ($get) {
                                    $provinceId = $get('province_id');
                                    if (! $provinceId) return [];

                                    return Location::query()
                                        ->where('location_type_id', 2)
                                        ->where('parent_id', $provinceId)
                                        ->pluck('name_kh', 'id');
                                })
                                ->disabled(fn ($get) => ! $get('province_id'))
                                ->live()
                                ->afterStateUpdated(function ($set) {
                                    $set('commune_id', null);
                                    $set('village_id', null);
                                }),

                            Select::make('commune_id')
                                ->label('ឃុំ / សង្កាត់')
                                ->placeholder('ជ្រើសរើសឃុំ/សង្កាត់')
                                ->searchable()
                                ->preload()
                                ->options(function ($get) {
                                    $districtId = $get('district_id');
                                    if (! $districtId) return [];

                                    return Location::query()
                                        ->where('location_type_id', 3)
                                        ->where('parent_id', $districtId)
                                        ->pluck('name_kh', 'id');
                                })
                                ->disabled(fn ($get) => ! $get('district_id'))
                                ->live()
                                ->afterStateUpdated(function ($set) {
                                    $set('village_id', null);
                                }),

                            Select::make('village_id')
                                ->label('ភូមិ')
                                ->placeholder('ជ្រើសរើសភូមិ')
                                ->searchable()
                                ->preload()
                                ->options(function ($get) {
                                    $communeId = $get('commune_id');
                                    if (! $communeId) return [];

                                    return Location::query()
                                        ->where('location_type_id', 4)
                                        ->where('parent_id', $communeId)
                                        ->pluck('name_kh', 'id');
                                })
                                ->disabled(fn ($get) => ! $get('commune_id')),
                        ])->columns(4), // ✅ ONE ROW (Province | District | Commune | Village)
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->where(function (Builder $query) use ($data) {
                            $provinceId = $data['province_id'] ?? null;
                            $districtId = $data['district_id'] ?? null;
                            $communeId  = $data['commune_id'] ?? null;
                            $villageId  = $data['village_id'] ?? null;

                            if ($villageId) {
                                $query->where('id', $villageId);
                                return;
                            }

                            if ($communeId) {
                                $query->where('id', $communeId)
                                    ->orWhere('parent_id', $communeId);
                                return;
                            }

                            if ($districtId) {
                                $query->where('id', $districtId)
                                    ->orWhere('parent_id', $districtId)
                                    ->orWhereHas('parent', fn ($p) => $p->where('parent_id', $districtId));
                                return;
                            }

                            if ($provinceId) {
                                $query->where('id', $provinceId)
                                    ->orWhere('parent_id', $provinceId)
                                    ->orWhereHas('parent', fn ($p) => $p->where('parent_id', $provinceId))
                                    ->orWhereHas('parent.parent', fn ($pp) => $pp->where('parent_id', $provinceId));
                            }
                        });
                    }),

                TrashedFilter::make()
                    ->columnSpanFull(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                ActionGroup::make([
                    Action::make('leaflet_map')
                        ->label('Leaflet Map')
                        ->icon('heroicon-o-map')
                        ->color('info')
                        ->url(fn ($record) => url('/map?focus=' . $record->id), true),
                ]),
            ]);
            // ->deferFilters(false);
    }
}