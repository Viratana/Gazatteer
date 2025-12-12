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

        // keep only digits
        $raw = $location->code === null
            ? null
            : preg_replace('/\D+/', '', (string) $location->code);

        if ($raw === null || $raw === '') {
            $location->code      = null;
            $location->parent_id = null;
            return;
        }

        // normalize: remove leading zeros
        $raw = ltrim($raw, '0');
        if ($raw === '') {
            $raw = '0';
        }

        $len = strlen($raw);

        // ========= CASE 1: PARENT SELECTED (district/commune/village) =========
        if (! empty($location->parent_id)) {
            $parent = self::find($location->parent_id);

            if ($parent && $parent->code !== null) {
                $parentCode = preg_replace('/\D+/', '', (string) $parent->code);
                $parentCode = ltrim($parentCode, '0');

                // If user already typed full hierarchical code (e.g. 104 under parent 1)
                if (str_starts_with($raw, $parentCode)) {
                    $location->code = $raw;
                } else {
                    // User typed only sequence (2, 4, 04, …) → build parent + seq
                    $childSeq       = str_pad($raw, 2, '0', STR_PAD_LEFT); // 4 -> 04
                    $location->code = $parentCode . $childSeq;             // 1 + 04 -> 104
                }
            } else {
                // parent not found -> just store normalized raw
                $location->code = $raw;
            }

            return;
        }

        // ========= CASE 2: NO PARENT & 1–2 DIGITS → PROVINCE =========
        if ($len <= 2) {
            $location->code      = $raw;   // "1", "13", "16", …
            $location->parent_id = null;
            return;
        }

        // ========= CASE 3: NO PARENT & MORE THAN 2 DIGITS → INFER PARENT =========
        $location->code = $raw;

        // Try prefixes: 204 -> 20 -> 2, 10201 -> 1020 -> 102 -> 10 -> 1
        $candidate = substr($raw, 0, -1);
        $parentId  = null;

        while ($candidate !== '') {
            $parentId = self::query()->where('code', $candidate)->value('id');
            if ($parentId) {
                break;
            }
            $candidate = substr($candidate, 0, -1);
        }

        $location->parent_id = $parentId ?: null;
    });



    //     static::saving(function (Location $location) {
    //     // Clean numeric only from what user typed
    //     $raw = $location->code === null
    //         ? null
    //         : preg_replace('/\D+/', '', (string) $location->code);

    //     if ($raw === null || $raw === '') {
    //         $location->code = null;
    //         return;
    //     }

    //     // Province (no parent) → just pad (01, 02, …)
    //     if (empty($location->parent_id)) {
    //         $location->code = str_pad($raw, 2, '0', STR_PAD_LEFT);
    //         return;
    //     }

    //     // District/child → parent code + child code (0102, 0103, …)
    //     $parent = self::find($location->parent_id);

    //     if ($parent && !empty($parent->code)) {
    //         $parentCode = $parent->code;
    //         $childCode  = str_pad($raw, 2, '0', STR_PAD_LEFT);

    //         // Avoid double-prefix if already correct
    //         if (substr($location->code, 0, strlen($parentCode)) !== $parentCode) {
    //             $location->code = $parentCode . $childCode;
    //         }
    //     } else {
    //         // If something wrong with parent, just use child code
    //         $location->code = str_pad($raw, 2, '0', STR_PAD_LEFT);
    //     }
    // });
    //     // 1) BEFORE save: compute parent_id from code if it's empty or code changed
    //     static::saving(function (self $location) {
    //         // only recompute when parent_id is empty OR the code changed
    //         if ($location->isDirty('code') || empty($location->parent_id)) {
    //             $code = $location->code ? preg_replace('/\D+/', '', (string)$location->code) : null;
    //             if (!$code) {
    //                 $location->parent_id = null;
    //             } else {
    //                 // Top-level code in your data is 1 digit (e.g., "3").
    //                 // If your top-level is 2 digits (e.g., "03"), change "<= 1" to "<= 2".
    //                 if (strlen($code) <= 1) {
    //                     $location->parent_id = null;
    //                 } else {
    //                     // Trim 2 digits repeatedly until a parent code exists: 3010101 → 30101 → 301 → 3
    //                     $candidate = substr($code, 0, -2);
    //                     $parentId  = null;
    //                     while (strlen($candidate) >= 1) {
    //                         $parentId = self::query()->where('code', $candidate)->value('id');
    //                         if ($parentId) {
    //                             break;
    //                         }
    //                         $candidate = substr($candidate, 0, -2);
    //                     }
    //                     $location->parent_id = $parentId ?: null;
    //                 }
    //             }
    //         }
        // });
        // 2) AFTER create: keep name/code shadow tables in sync
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
        // 3) AFTER update: keep name/code shadow tables in sync
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
