<?php

namespace App\Filament\Imports;

use App\Models\Location;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class LocationImporter extends Importer
{
    protected static ?string $model = Location::class;

    protected static array $codeToId = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('location_type_id')
                ->label('Location Type')
                ->relationship(
                    'locationType',
                    'name',
                    'modifyQueryUsing',
                    fn (Builder $q) => $q->orderBy('id', 'asc')
                ),

            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:12']),

            ImportColumn::make('name_kh')
                ->label('NameKH')
                ->requiredMapping()
                ->rules(['required', 'max:100']),

            ImportColumn::make('name_en')
                ->label('NameEN')
                ->rules(['max:80']),

            ImportColumn::make('reference'),
            ImportColumn::make('note'),
            ImportColumn::make('note_by_checker')
                ->label('Note By Checker'),
        ];
    }

    public function resolveRecord(): Location
    {
        return new Location([
            'created_by' => $this->import->user?->name,
        ]);
    }

    public function beforeSave(): void
    {
        /** @var Location $rec */
        $rec = $this->record;

        // digits only
        $code = $rec->code ? preg_replace('/\D+/', '', (string) $rec->code) : null;
        $rec->code = $code;

        if (!$code) {
            $rec->parent_id = null;
            return;
        }

        $len = strlen($code);
        $parentCode = null;

        // Province (2 digits) -> no parent
        if ($len <= 2) {
            $rec->parent_id = null;
            return;
        }

        // District: 102 -> Province 01 (first digit, padded)
        if ($len === 3) {
            $provinceDigit = substr($code, 0, 1); // "1"
            $parentCode    = str_pad($provinceDigit, 2, '0', STR_PAD_LEFT); // "01"
        }
        // Commune: 10201 -> District 102
        elseif ($len === 5) {
            $parentCode = substr($code, 0, 3);
        }
        // Village: 1020101 -> Commune 10201
        elseif ($len === 7) {
            $parentCode = substr($code, 0, 5);
        }
        // fallback: any other length -> try prefixes
        else {
            $candidate = substr($code, 0, -1);
            while (strlen($candidate) >= 2) {
                if ($this->findIdByCode($candidate)) {
                    $parentCode = $candidate;
                    break;
                }
                $candidate = substr($candidate, 0, -1);
            }
        }

        if ($parentCode !== null) {
            $parentId = $this->findIdByCode($parentCode);
            $rec->parent_id = $parentId ?: null;
        } else {
            $rec->parent_id = null;
        }
    }

    public function afterSave(): void
    {
        /** @var Location $rec */
        $rec = $this->record;

        if ($rec->id && $rec->code) {
            $code = preg_replace('/\D+/', '', (string) $rec->code);
            self::$codeToId[$code] = (int) $rec->id;
        }
    }

    protected function findIdByCode(string $code): ?int
    {
        $variants = [$code];

        // "01" -> "1"
        $noZero = ltrim($code, '0');
        if ($noZero !== '' && $noZero !== $code) {
            $variants[] = $noZero;
        }

        // 1) cache
        foreach ($variants as $variant) {
            if (isset(self::$codeToId[$variant])) {
                return self::$codeToId[$variant];
            }
        }

        // 2) DB
        $id = null;
        foreach ($variants as $variant) {
            $id = Location::query()
                ->where('code', $variant)
                ->value('id');

            if ($id) {
                foreach ($variants as $v) {
                    self::$codeToId[$v] = (int) $id;
                }
                return (int) $id;
            }
        }

        return null;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your location import has completed and ' .
            Number::format($import->successful_rows) . ' ' .
            str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' .
                str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
