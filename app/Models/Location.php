<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'location_type_id',
        'parent_id',
        'code',
        'postal_code',
        'coordination',
        'name_kh',
        'name_en',
        'reference',
        'note',
        'note_by_checker',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'code'             => 'string',
            'parent_id'        => 'integer',
            'id'               => 'integer',
            'status'           => 'boolean',
            'location_type_id' => 'integer',
        ];
    }

    /** Normalize code to digits only whenever it is set */
    // public function setCodeAttribute($value): void
    // {
    //     $this->attributes['code'] = $value === null
    //         ? null
    //         : preg_replace('/\D+/', '', (string) $value);
    // }

    protected static function booted(): void
    {
        static::saving(function (self $location) {

            // 0) digits only
            $raw = $location->code === null
                ? null
                : preg_replace('/\D+/', '', (string) $location->code);

            if ($raw === null || $raw === '') {
                $location->code      = null;
                $location->parent_id = null;
                return;
            }

            // ✅ IMPORTANT: DO NOT remove leading zeros (Cambodia codes need them)
            $typeId = (int) ($location->location_type_id ?? 0);

            // expected lengths by type
            $targetLen = match ($typeId) {
                1 => 2, // province
                2 => 4, // district
                3 => 6, // commune
                4 => 8, // village
                default => strlen($raw),
            };

            // normalize length for known types
            $raw = self::padLeft($raw, $targetLen);

            // ========= CASE 1: PARENT SELECTED =========
            if (! empty($location->parent_id)) {
                $parent = self::query()->select('id', 'code', 'lat', 'lng')->find($location->parent_id);

                if ($parent && $parent->code) {
                    $parentCode = preg_replace('/\D+/', '', (string) $parent->code);

                    // normalize parent code length too (based on parent type inferred from child type)
                    // Province->2, District->4, Commune->6
                    $parentLen = match ($typeId) {
                        2 => 2, // district parent is province
                        3 => 4, // commune parent is district
                        4 => 6, // village parent is commune
                        default => strlen($parentCode),
                    };
                    $parentCode = self::padLeft($parentCode, $parentLen);

                    // If user typed full hierarchical code, accept it (but normalized already)
                    if (str_starts_with($raw, $parentCode) && strlen($raw) === $targetLen) {
                        $location->code = $raw;
                    } else {
                        // user typed only last 2 digits -> build full code
                        $childSeq = substr($raw, -2); // last 2 digits
                        $location->code = $parentCode . $childSeq;
                        // make sure length exactly right
                        $location->code = self::padLeft($location->code, $targetLen);
                    }
                } else {
                    $location->code = $raw;
                }

                // ✅ AUTO LAT/LNG (if missing)
                self::fillLatLngIfMissing($location, $parent);

                return;
            }

            // ========= CASE 2: NO PARENT =========
            $location->code = $raw;

            // province has no parent
            if ($typeId === 1) {
                $location->parent_id = null;
                self::fillLatLngIfMissing($location, null);
                return;
            }

            // infer parent by removing 2 digits at a time (because hierarchy is 2-digit segments)
            $candidate = $raw;
            $parentId = null;

            // Example for village 01020101 -> try 010201 -> 0102 -> 01
            while (strlen($candidate) > 2) {
                $candidate = substr($candidate, 0, -2);
                $parentId = self::query()->where('code', $candidate)->value('id');
                if ($parentId) break;
            }

            $location->parent_id = $parentId ?: null;

            // ✅ AUTO LAT/LNG (if missing) based on inferred parent
            $parent = null;
            if ($location->parent_id) {
                $parent = self::query()->select('id', 'lat', 'lng')->find($location->parent_id);
            }
            self::fillLatLngIfMissing($location, $parent);
        });

        // AFTER create: keep shadow tables in sync
        static::created(function (self $location) {
            LocationName::updateOrCreate(
                ['location_id' => $location->id],
                [
                    'location_type_id' => $location->location_type_id ?? null,
                    'parent_id'        => $location->parent_id ?? null,
                    'code'             => $location->code ?? null,
                    'name_kh'          => $location->name_kh ?? null,
                    'name_en'          => $location->name_en ?? null,
                    'postal_code'      => $location->postal_code ?? null,
                    'reference'        => $location->reference ?? null,
                    'coordination'     => $location->coordination ?? null,
                    'note'             => $location->note ?? null,
                    'note_by_checker'  => $location->note_by_checker ?? null,
                    'created_by'       => $location->created_by ?? null,
                ]
            );

            LocationCode::updateOrCreate(
                ['location_id' => $location->id],
                [
                    'location_type_id' => $location->location_type_id ?? null,
                    'parent_id'        => $location->parent_id ?? null,
                    'code'             => $location->code ?? null,
                    'postal_code'      => $location->postal_code ?? null,
                    'name_kh'          => $location->name_kh ?? null,
                    'name_en'          => $location->name_en ?? null,
                    'reference'        => $location->reference ?? null,
                    'note'             => $location->note ?? null,
                    'note_by_checker'  => $location->note_by_checker ?? null,
                    'created_by'       => $location->created_by ?? null,
                ]
            );
        });

        // AFTER update: keep shadow tables in sync
        static::updated(function (self $location) {
            $locationName = LocationName::firstOrNew(['location_id' => $location->id]);
            $locationName->fill([
                'location_type_id' => $location->location_type_id ?? null,
                'parent_id'        => $location->parent_id ?? null,
                'code'             => $location->code ?? null,
                'name_kh'          => $location->name_kh ?? null,
                'name_en'          => $location->name_en ?? null,
                'postal_code'      => $location->postal_code ?? null,
                'reference'        => $location->reference ?? null,
                'coordination'     => $location->coordination ?? null,
                'note'             => $location->note ?? null,
                'note_by_checker'  => $location->note_by_checker ?? null,
                'created_by'       => $location->created_by ?? null,
            ])->save();

            $locationCode = LocationCode::firstOrNew(['location_id' => $location->id]);
            $locationCode->fill([
                'location_type_id' => $location->location_type_id ?? null,
                'parent_id'        => $location->parent_id ?? null,
                'code'             => $location->code ?? null,
                'postal_code'      => $location->postal_code ?? null,
                'name_kh'          => $location->name_kh ?? null,
                'name_en'          => $location->name_en ?? null,
                'reference'        => $location->reference ?? null,
                'note'             => $location->note ?? null,
                'note_by_checker'  => $location->note_by_checker ?? null,
                'created_by'       => $location->created_by ?? null,
            ])->save();
        });
    }

    /** Pad left with zeros to fixed length */
    private static function padLeft(string $value, int $length): string
    {
        if ($length <= 0) return $value;
        return str_pad($value, $length, '0', STR_PAD_LEFT);
    }

    /** Fill lat/lng if missing (province random, others near parent) */
    private static function fillLatLngIfMissing(self $location, ?self $parent): void
    {
        if (!empty($location->lat) && !empty($location->lng)) {
            return;
        }

        $typeId = (int) ($location->location_type_id ?? 0);

        // Province -> random Cambodia point
        if ($typeId === 1) {
            [$lat, $lng] = self::randomCambodiaPoint();
            $location->lat = $lat;
            $location->lng = $lng;
            return;
        }

        // If parent has coords -> near parent
        if ($parent && $parent->lat && $parent->lng) {
            [$lat, $lng] = self::nearParentPoint((float) $parent->lat, (float) $parent->lng, $typeId);
            $location->lat = $lat;
            $location->lng = $lng;
            return;
        }

        // fallback random
        [$lat, $lng] = self::randomCambodiaPoint();
        $location->lat = $lat;
        $location->lng = $lng;
    }

    private static function randomCambodiaPoint(): array
    {
        // Cambodia approx bounding box
        $lat = 10.3 + (mt_rand() / mt_getrandmax()) * (14.7 - 10.3);
        $lng = 102.3 + (mt_rand() / mt_getrandmax()) * (107.7 - 102.3);
        return [round($lat, 7), round($lng, 7)];
    }

    private static function nearParentPoint(float $plat, float $plng, int $typeId): array
    {
        $spread = match ($typeId) {
            2 => 0.6,   // district
            3 => 0.3,   // commune
            4 => 0.15,  // village
            default => 0.5,
        };

        $lat = $plat + ((mt_rand() / mt_getrandmax()) - 0.5) * $spread;
        $lng = $plng + ((mt_rand() / mt_getrandmax()) - 0.5) * $spread;

        return [round($lat, 7), round($lng, 7)];
    }

    public function locationType(): BelongsTo
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    public function codes(): HasMany
    {
        return $this->hasMany(LocationCode::class);
    }

    public function locationNames()
    {
        return $this->hasMany(LocationName::class, 'location_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }


}
