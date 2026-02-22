<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($validated['email']);
        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $validated['password']]
            : ['contact' => $login, 'password' => $validated['password']];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('user');
    }

    public function register(Request $request): RedirectResponse
    {
        if (! $request->filled('password_confirmation') && $request->filled('passwordConfirmation')) {
            $request->merge([
                'password_confirmation' => $request->input('passwordConfirmation'),
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ]);

        $login = trim($validated['email']);
        $isEmail = (bool) filter_var($login, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $request->validate([
                'email' => ['unique:users,email'],
            ]);
        } else {
            $request->validate([
                'email' => ['unique:users,contact'],
            ]);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $isEmail ? $login : null,
            'contact' => $isEmail ? null : $login,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user');
    }
}
