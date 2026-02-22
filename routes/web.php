<?php

use App\Http\Controllers\UserAuthController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/auth', 'auth.index')->name('auth');
Route::redirect('/login', '/auth')->name('login');
Route::redirect('/register', '/auth')->name('register');

Route::post('/login', [UserAuthController::class, 'login'])->name('login.attempt');

Route::post('/register', [UserAuthController::class, 'register'])->name('register.store');

Route::get('/user', function () {
    return view('dashboard');
})->middleware('auth')->name('user');

Route::redirect('/dashboard', '/user');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('welcome');
})->middleware('auth')->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('welcome')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');
