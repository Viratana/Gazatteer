<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        :root {
            --bg: #f4f6f8;
            --panel: #ffffff;
            --line: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
            --accent: #28b8bf;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrap {
            max-width: 1000px;
            margin: 0 auto;
            padding: 28px 16px 42px;
        }

        .topbar {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .title {
            margin: 0;
            font-size: 22px;
        }

        .subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .logout-btn {
            border: 0;
            border-radius: 6px;
            padding: 10px 14px;
            background: var(--accent);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 18px;
        }

        .card h2 {
            margin: 0 0 8px;
            font-size: 16px;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.45;
        }

        @media (max-width: 860px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header class="topbar">
            <div>
                <h1 class="title">Welcome, {{ $user->name ?? 'User' }}</h1>
                <p class="subtitle">You are logged in as {{ $user->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </header>

        <section class="cards">
            <article class="card">
                <h2>Account Status</h2>
                <p>Your account session is active and secured by Laravel authentication middleware.</p>
            </article>
            <article class="card">
                <h2>Profile</h2>
                <p>Use this dashboard as the entry page for logged-in users and add your application modules here.</p>
            </article>
            <article class="card">
                <h2>Next Step</h2>
                <p>Connect this dashboard to your real data widgets, tables, and notifications.</p>
            </article>
        </section>
    </div>
</body>
</html>
