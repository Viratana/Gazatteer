<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg-a: #f1f5ff;
            --bg-b: #e5edff;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #55637d;
            --line: #dce3f3;
            --primary: #075d63;
            --chip: #eef3ff;
            --ok-bg: #eaf8ee;
            --ok-text: #22663b;
            --ok-line: #bde3c8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Instrument Sans', sans-serif;
            background:
                radial-gradient(1200px 700px at 20% -5%, #d5e3ff 0%, transparent 55%),
                radial-gradient(900px 600px at 105% 10%, #d7efff 0%, transparent 45%),
                linear-gradient(140deg, var(--bg-a) 0%, var(--bg-b) 100%);
            color: var(--text);
            padding: 28px;
        }

        .wrap {
            width: min(1100px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.08);
        }

        .hero {
            padding: 26px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .eyebrow {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        h1 {
            margin: 6px 0 8px;
            font-size: clamp(30px, 6vw, 42px);
            line-height: 1.08;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-size: 17px;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-ghost {
            background: var(--chip);
            color: var(--primary);
            border: 1px solid #d6e0fb;
        }

        .flash {
            margin-top: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            background: var(--ok-bg);
            color: var(--ok-text);
            border: 1px solid var(--ok-line);
        }

        .grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 18px;
        }

        .panel { padding: 22px; }

        .panel h2 {
            margin: 0 0 14px;
            font-size: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            background: #fbfcff;
        }

        .info-label {
            margin: 0 0 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted);
        }

        .info-value {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            word-break: break-word;
        }

        .list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .list li {
            border: 1px solid var(--line);
            background: #fbfcff;
            border-radius: 10px;
            padding: 11px 12px;
            font-size: 14px;
            color: #28344f;
        }

        form { margin: 0; }

        @media (max-width: 900px) {
            body { padding: 16px; }
            .hero { padding: 18px; }
            .grid { grid-template-columns: 1fr; }
            .panel { padding: 18px; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $loginIdentity = $user->email ?: ($user->contact ?: 'Not set');
    @endphp

    <main class="wrap">
        <section class="card hero">
            <div>
                <p class="eyebrow">User</p>
                <h1>Welcome, {{ $user->name }}</h1>
                <p class="subtitle">You are logged in and your account is ready.</p>
                @if (session('status'))
                    <div class="flash">{{ session('status') }}</div>
                @endif
            </div>

            <div class="actions">
                <a class="btn btn-ghost" href="{{ route('welcome') }}">Back to Home</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            </div>
        </section>

        <section class="grid">
            <article class="card panel">
                <h2>Account Overview</h2>
                <div class="info-grid">
                    <div class="info">
                        <p class="info-label">Full Name</p>
                        <p class="info-value">{{ $user->name }}</p>
                    </div>
                    <div class="info">
                        <p class="info-label">Login Identity</p>
                        <p class="info-value">{{ $loginIdentity }}</p>
                    </div>
                    <div class="info">
                        <p class="info-label">Joined</p>
                        <p class="info-value">{{ optional($user->created_at)->format('M d, Y') }}</p>
                    </div>
                    <div class="info">
                        <p class="info-label">Verification</p>
                        <p class="info-value">{{ $user->email_verified_at ? 'Verified' : 'Pending' }}</p>
                    </div>
                </div>
            </article>

            <aside class="card panel">
                <h2>Quick Actions</h2>
                <ul class="list">
                    <li>Review your account details and contact information.</li>
                    <li>Use admin panel for system data at <code>/admin</code> if permitted.</li>
                    <li>Sign out securely when you finish.</li>
                </ul>
            </aside>
        </section>
    </main>
</body>
</html>
