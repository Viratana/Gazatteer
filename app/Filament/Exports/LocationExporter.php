<?php

namespace App\Filament\Exports;

use App\Models\Location;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class LocationExporter extends Exporter
{
    protected static ?string $model = Location::class;

    /** Use getQuery() in Exporter (not getEloquentQuery) */
    public static function getQuery(): Builder
    {
        return static::$model::query()->with(['locationType', 'parent']);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('locationType.name')
                ->label('Location Type'),
            ExportColumn::make('parent_code')
                ->label('Parent Code')
                ->state(fn (Location $r) => $r->parent?->code ? (string) $r->parent->code : null),
            ExportColumn::make('parent_name')
                ->label('Parent Name')
                ->state(fn (Location $r) => $r->parent?->name_kh ?? $r->parent?->name_en),
            ExportColumn::make('code')
                ->label('Code')
                ->state(fn (Location $r) => (string) $r->code),
            ExportColumn::make('name_kh')
                ->label('NameKH'),
            ExportColumn::make('name_en')
                ->label('NameEN'),
            ExportColumn::make('postal_code')->label('Postal Code')
                ->state(fn (Location $r) => $r->postal_code === null ? null : (string) $r->postal_code),
            ExportColumn::make('coordination')
                ->label('Coordination'),
            ExportColumn::make('reference')
                ->label('Reference'),
            ExportColumn::make('note')
                ->label('Note'),
            ExportColumn::make('created_by')
                ->label('Created by'),
            ExportColumn::make('created_at')
                ->label('Created at')
                ->state(fn (Location $r) => $r->created_at?->format('Y-m-d H:i')),
            ExportColumn::make('updated_at')
                ->label('Updated at')
                ->state(fn (Location $r) => $r->updated_at?->format('Y-m-d H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $ok = Number::format($export->successful_rows);
        $body = "Your location export has completed and {$ok} " . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failed = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failed) . ' ' . str('row')->plural($failed) . ' failed to export.';
        }

        return $body;
    }
}
