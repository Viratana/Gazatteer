<?php

use App\Http\Controllers\Auth\WebAuthController;
use App\Models\Location;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// --- CUSTOM USER AUTH ROUTES ---
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});



Route::get('/map', fn () => view('map'));

// ✅ This MUST return JSON
Route::get('/map/locations', function () {
    return response()->json(
        Location::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('id', 'name_kh', 'name_en', 'location_type_id', 'parent_id', 'code', 'lat', 'lng')
            ->get()
    );
});

// ✅ Focus one location (JSON)
Route::get('/map/location/{id}', function ($id) {
    $loc = Location::with('parent.parent.parent')->findOrFail($id);

    $hierarchy = [];
    $cur = $loc;
    while ($cur) {
        $hierarchy[] = [
            'id' => $cur->id,
            'code' => $cur->code,
            'name_kh' => $cur->name_kh,
            'name_en' => $cur->name_en,
            'type' => $cur->location_type_id,
            'lat' => $cur->lat,
            'lng' => $cur->lng,
        ];
        $cur = $cur->parent;
    }

    return response()->json([
        'location' => [
            'id' => $loc->id,
            'code' => $loc->code,
            'name_kh' => $loc->name_kh,
            'name_en' => $loc->name_en,
            'location_type_id' => $loc->location_type_id,
            'lat' => $loc->lat,
            'lng' => $loc->lng,
        ],
        'hierarchy' => array_reverse($hierarchy),
    ]);
});