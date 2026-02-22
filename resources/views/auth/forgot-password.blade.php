<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
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
            width: min(520px, 100%);
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
        }
        input:focus { border-color: #8ca0d6; background: #fff; }
        .btn {
            margin-top: 14px;
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
        .muted { display: inline-block; margin-top: 12px; color: #3a4f8d; text-decoration: none; }
        .flash {
            margin-bottom: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
        }
        .flash-success { background: #eaf8ee; color: #22663b; border: 1px solid #bde3c8; }
        .flash-error { background: #fdeeee; color: #8d1f1f; border: 1px solid #f0c3c3; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Forgot Password</h1>
        <p>Enter your email and we will send you a reset link.</p>

        @if (session('status'))
            <div class="flash flash-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            <button class="btn" type="submit">Send Reset Link</button>
        </form>

        <a class="muted" href="{{ route('welcome') }}">Back to Login</a>
    </main>
</body>
</html>
