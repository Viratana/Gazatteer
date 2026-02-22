<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(140deg, #e8edf8 0%, #f3f6fb 45%, #dce5f7 100%);
            display: grid;
            place-items: center;
            padding: 20px;
            color: #131722;
        }
        .card {
            width: min(540px, 100%);
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(20, 32, 74, 0.15);
            padding: 24px;
        }
        h1 { margin: 0 0 8px; font-size: 32px; }
        p { margin: 0 0 16px; color: #586078; }
        label { display: block; margin-bottom: 8px; color: #6c7381; font-size: 14px; }
        input {
            width: 100%;
            border: 1px solid #dde2ea;
            background: #f4f6fa;
            border-radius: 10px;
            min-height: 50px;
            padding: 12px 14px;
            font-size: 16px;
            outline: none;
            margin-bottom: 12px;
        }
        input:focus { border-color: #8ca0d6; background: #fff; }
        .btn {
            margin-top: 4px;
            width: 100%;
            border: 0;
            border-radius: 10px;
            min-height: 48px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            background: #23429b;
            cursor: pointer;
        }
        .flash {
            margin-bottom: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            background: #fdeeee;
            color: #8d1f1f;
            border: 1px solid #f0c3c3;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Reset Password</h1>
        <p>Choose a new password for your account.</p>

        @if ($errors->any())
            <div class="flash">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>New Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>

            <button class="btn" type="submit">Reset Password</button>
        </form>
    </main>
</body>
</html>
