<?php

namespace App\Filament\Imports;

use App\Models\LocationType;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class LocationTypeImporter extends Importer
{
    protected static ?string $model = LocationType::class;

      public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Location Type')
                ->requiredMapping()           
                ->rules(['required','string','max:255']),
        ];
    }

    public function resolveRecord(): LocationType
    {
        return new LocationType([
            'created_by' => $this->import->user?->name,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your location type import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
