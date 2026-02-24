<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Register</title>
    <style>
        :root {
            --panel: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #d1d5db;
            --accent: #28b8bf;
            --accent-dark: #15959b;
            --error: #b91c1c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background: #ffffff;
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .register-shell {
            position: relative;
            z-index: 1;
            width: min(92vw, 460px);
        }

        .register-card {
            background: var(--panel);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.08);
            padding: 28px 28px 24px;
        }

        .logo {
            width: 190px;
            height: 64px;
            border-radius: 8px;
            margin: 0 auto 14px;
            background: transparent;
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .logo-image {
            width: 50%;
            height: 50%;
            object-fit: contain;
            display: block;
        }

        .logo-text {
            display: none;
            color: #111827;
            font-weight: 700;
            letter-spacing: 0.4px;
            font-size: 10px;
        }

        .logo.is-fallback .logo-text {
            display: inline;
        }

        h1 {
            margin: 0 0 20px;
            text-align: center;
            font-size: 29px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .field {
            margin-bottom: 14px;
        }

        .label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            color: var(--muted);
        }

        .input {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid var(--line);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }

        .input:focus {
            outline: none;
            border-color: #8cced2;
            box-shadow: 0 0 0 3px rgba(40, 184, 191, 0.18);
        }

        .error {
            margin-top: 6px;
            font-size: 12px;
            color: var(--error);
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            background: var(--accent);
            color: #fff;
            transition: background .2s ease, transform .1s ease;
        }

        .btn:hover {
            background: var(--accent-dark);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .hint {
            margin-top: 14px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .hint a {
            color: var(--accent-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .hint a:hover {
            text-decoration: underline;
        }

        .required {
            color: #dc2626;
        }


        @media (max-width: 900px) {
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <main class="register-shell">
        <section class="register-card">
            <div class="logo">
                <img
                    class="logo-image"
                    src="{{ asset('images/images (1).png') }}"
                    alt="Logo"
                    onerror="this.remove(); this.parentElement.classList.add('is-fallback');"
                >
                <span class="logo-text">G</span>
            </div>
            <h1>Create Account</h1>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">Full name <span class="required">*</span></label>
                    <input class="input" type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="email">Email <span class="required">*</span></label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">Password (minimum 8 characters) <span
                            class="required">*</span></label>
                    <input class="input" type="password" id="password" name="password" required>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password_confirmation">Confirm password <span
                            class="required">*</span></label>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                        required>
                </div>

                <button class="btn" type="submit">Create account</button>
            </form>

            <p class="hint">
                Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
            </p>
        </section>
    </main>
</body>

</html>
