<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gazatteer - Home</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg-a: #eef3ff;
            --bg-b: #f7fbff;
            --ink: #0f172a;
            --muted: #475569;
            --line: #d9e2f1;
            --card: #ffffff;
            --primary: #075d63;
            --primary-dark: #064c51;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Instrument Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 0% -10%, #dce7ff 0%, transparent 55%),
                radial-gradient(900px 500px at 100% 0%, #e0f1ff 0%, transparent 50%),
                linear-gradient(160deg, var(--bg-a), var(--bg-b));
            min-height: 100vh;
        }

        .container {
            width: min(1100px, 100%);
            margin: 0 auto;
            padding: 24px 20px 40px;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid transparent;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover { background: var(--primary-dark); }

        .btn-outline {
            border-color: #c8d7f0;
            background: #fff;
            color: var(--primary);
        }

        .hero {
            border: 1px solid var(--line);
            background: var(--card);
            border-radius: 20px;
            padding: clamp(24px, 5vw, 46px);
            box-shadow: 0 16px 40px rgba(37, 57, 118, 0.08);
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 12px;
            font-weight: 600;
        }

        h1 {
            margin: 0;
            font-size: clamp(34px, 7vw, 56px);
            line-height: 1.04;
        }

        .subtitle {
            margin: 14px 0 0;
            max-width: 700px;
            color: var(--muted);
            font-size: clamp(16px, 2vw, 19px);
            line-height: 1.5;
        }

        .hero-actions {
            margin-top: 24px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .tile {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            padding: 16px;
        }

        .tile h3 {
            margin: 0 0 6px;
            font-size: 17px;
        }

        .tile p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.45;
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="nav">
            <a class="brand" href="{{ route('welcome') }}">Gazatteer</a>
            <div class="nav-actions">
                @auth
                    <a class="btn btn-outline" href="{{ route('user') }}">User</a>
                    <a class="btn btn-outline" href="{{ url('/admin') }}">Admin</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button class="btn btn-primary" type="submit">Logout</button>
                    </form>
                @else
                    <a class="btn btn-outline" href="{{ route('auth') }}">User Login</a>
                    <a class="btn btn-primary" href="{{ route('auth') }}">Create Account</a>
                @endauth
            </div>
        </header>

        <section class="hero">
            <p class="eyebrow">Home</p>
            <h1>Find, manage, and explore location data in one place.</h1>
            <p class="subtitle">
                This is your public home page. Users can sign in from here, access their user page,
                and continue to admin features based on permissions.
            </p>
            <div class="hero-actions">
                @auth
                    <a class="btn btn-primary" href="{{ route('user') }}">Go to User</a>
                @else
                    <a class="btn btn-primary" href="{{ route('auth') }}">Login / Register</a>
                @endauth
                <a class="btn btn-outline" href="{{ url('/admin') }}">Open Admin</a>
            </div>
        </section>

        <section class="grid">
            <article class="tile">
                <h3>User Access</h3>
                <p>Combined login/register flow for users at <code>/auth</code>.</p>
            </article>
            <article class="tile">
                <h3>User Page</h3>
                <p>Personal account summary and quick actions after sign-in.</p>
            </article>
            <article class="tile">
                <h3>Admin Panel</h3>
                <p>Filament admin interface is available at <code>/admin</code>.</p>
            </article>
        </section>
    </div>
</body>
</html>
