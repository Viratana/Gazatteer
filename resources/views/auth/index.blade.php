<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login / Register</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root {
            --primary: #075d63;
            --border: #d9d9df;
            --text: #111827;
            --muted: #4b5563;
            --card: #f7f7f8;
            --bg: rgba(17, 24, 39, 0.32);
        }

        body {
            margin: 0;
            font-family: Poppins, ui-sans-serif, system-ui, sans-serif;
            background: var(--bg), linear-gradient(180deg, #eceff5 0%, #d9e0ed 100%);
            color: var(--text);
        }

        .auth-wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 12px;
        }

        .auth-card {
            width: 100%;
            max-width: 770px;
            background: var(--card);
            border-radius: 22px;
            padding: 26px 74px 38px;
            box-sizing: border-box;
            position: relative;
            box-shadow: 0 10px 36px rgba(15, 23, 42, 0.2);
            border: 1px solid #e5e7eb;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 16px;
            width: 32px;
            height: 32px;
            border: 0;
            background: transparent;
            color: #777;
            font-size: 36px;
            line-height: 30px;
            cursor: default;
        }

        .tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1px solid var(--primary);
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .tab-btn {
            border: 0;
            background: transparent;
            padding: 12px 16px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .tab-btn + .tab-btn {
            border-left: 1px solid var(--primary);
        }

        .tab-btn.active {
            background: var(--primary);
            color: #fff;
        }

        .tab-btn:not(.active) {
            background: transparent;
            color: var(--primary);
        }

        .title {
            margin: 4px 0 8px 0;
            text-align: center;
            font-size: 48px;
            font-weight: 700;
            line-height: 1.05;
        }

        .subtitle {
            text-align: center;
            margin: 0 0 20px 0;
            color: var(--muted);
            font-size: 20px;
        }

        .subtitle a {
            color: var(--primary);
            text-decoration: none;
        }

        .field {
            margin-bottom: 15px;
            position: relative;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            color: #4b5563;
            font-size: 16px;
        }

        .req {
            color: #dc2626;
        }

        .field input {
            width: 100%;
            border: 1px solid #e3e5eb;
            background: #efeff2;
            border-radius: 10px;
            padding: 15px 48px 15px 20px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .field input::placeholder {
            color: #7b8190;
        }

        .field.has-eye .eye {
            position: absolute;
            right: 16px;
            bottom: 15px;
            border: 0;
            background: transparent;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #252525;
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        .inline-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 6px;
            margin-bottom: 16px;
            color: #374151;
            font-size: 14px;
        }

        .inline-row a {
            color: #6b7280;
            text-decoration: none;
        }

        .terms {
            margin: 8px 0 16px;
            font-size: 14px;
            color: #374151;
        }

        .terms a {
            color: var(--primary);
            text-decoration: none;
        }

        .submit {
            width: 100%;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
            color: #fff;
            padding: 14px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.5px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #6b7280;
            margin: 22px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .google {
            display: block;
            width: 100%;
            max-width: 290px;
            margin: 0 auto;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: #111827;
            text-decoration: none;
            background: #fff;
            font-size: 15px;
        }

        .panel {
            display: none;
            opacity: 0;
            transform: translateY(6px);
        }

        .panel.active {
            display: block;
            animation: panel-in 0.22s ease forwards;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .submit.is-loading {
            opacity: 0.9;
            pointer-events: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            animation: spin 0.75s linear infinite;
        }

        .submit.is-loading .spinner {
            display: inline-block;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes panel-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 18px 16px 24px;
                border-radius: 16px;
            }

            .title {
                font-size: 34px;
            }

            .subtitle {
                font-size: 18px;
            }

            .tab-btn {
                font-size: 18px;
            }

            .field label,
            .field input {
                font-size: 15px;
            }

            .inline-row,
            .terms {
                font-size: 14px;
            }

            .submit {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="auth-wrap">
    @php
        $activeTab = old('form', 'login');
    @endphp
    <div class="auth-card" data-auth-root data-active-tab="{{ $activeTab }}">
        <button class="close-btn" type="button" aria-label="Close">&times;</button>

        <div class="tabs">
            <button type="button" class="tab-btn" data-tab-btn="login">Login</button>
            <button type="button" class="tab-btn" data-tab-btn="register">Sign Up</button>
        </div>

        <section class="panel" data-panel="login">
            <h1 class="title">Welcome Back!</h1>
            <p class="subtitle">Still don't have an account? <a href="#" data-open-tab="register">Sign Up</a></p>

            @if ($errors->any() && old('form', 'login') === 'login')
                <div class="errors">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <input type="hidden" name="form" value="login">

                <div class="field">
                    <label for="login_email">Contact Number / Email<span class="req">*</span></label>
                    <input id="login_email" type="text" name="email" placeholder="Enter number / Email address" value="{{ old('email') }}" required>
                </div>

                <div class="field has-eye">
                    <label for="login_password">Password<span class="req">*</span></label>
                    <input id="login_password" type="password" name="password" placeholder="Enter Password" required>
                    <button class="eye" type="button" aria-label="Show password">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M4 20L20 4"/>
                        </svg>
                    </button>
                </div>

                <div class="inline-row">
                    <label><input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Keep me logged in</label>
                    <a href="{{ route('password.request') }}">Forget Password?</a>
                </div>

                <button class="submit" type="submit">
                    <span class="spinner"></span>
                    <span class="label">LOGIN</span>
                </button>
            </form>
        </section>

        <section class="panel" data-panel="register">
            <h1 class="title">Register</h1>
            <p class="subtitle">Already have an account? <a href="#" data-open-tab="login">Login</a></p>

            @if ($errors->any() && old('form', 'login') === 'register')
                <div class="errors">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="hidden" name="form" value="register">

                <div class="field">
                    <label for="register_name">Name<span class="req">*</span></label>
                    <input id="register_name" type="text" name="name" placeholder="Enter Full Name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="register_email">Contact Number / Email<span class="req">*</span></label>
                    <input id="register_email" type="text" name="email" placeholder="Enter Number / Email" value="{{ old('email') }}" required>
                </div>

                <div class="field has-eye">
                    <label for="register_password">Password<span class="req">*</span></label>
                    <input id="register_password" type="password" name="password" placeholder="Enter Password" required>
                    <button class="eye" type="button" aria-label="Show password">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M4 20L20 4"/>
                        </svg>
                    </button>
                </div>

                <div class="field has-eye">
                    <label for="register_password_confirmation">Confirm Password<span class="req">*</span></label>
                    <input id="register_password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter Password" required>
                    <button class="eye" type="button" aria-label="Show password">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M4 20L20 4"/>
                        </svg>
                    </button>
                </div>

                <div class="terms">
                    <label>
                        <input type="checkbox" name="accept_terms" value="1" required>
                        By hitting the Register button, you agree to the <a href="#">Terms &amp; Conditions</a> &amp; <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button class="submit" type="submit">
                    <span class="spinner"></span>
                    <span class="label">SIGN UP</span>
                </button>
            </form>
        </section>

    </div>
</div>

<script>
    (function () {
        const root = document.querySelector('[data-auth-root]');
        if (!root) return;

        const panels = root.querySelectorAll('[data-panel]');
        const buttons = root.querySelectorAll('[data-tab-btn]');
        const links = root.querySelectorAll('[data-open-tab]');
        const forms = root.querySelectorAll('form');
        const eyeButtons = root.querySelectorAll('.field.has-eye .eye');
        let active = root.dataset.activeTab || 'login';

        const setTab = (tab) => {
            active = tab;
            panels.forEach((panel) => {
                panel.classList.toggle('active', panel.dataset.panel === tab);
            });
            buttons.forEach((btn) => {
                btn.classList.toggle('active', btn.dataset.tabBtn === tab);
            });
        };

        buttons.forEach((btn) => {
            btn.addEventListener('click', () => setTab(btn.dataset.tabBtn));
        });

        links.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                setTab(link.dataset.openTab);
            });
        });

        forms.forEach((form) => {
            form.addEventListener('submit', () => {
                const submit = form.querySelector('.submit');
                if (!submit) return;
                submit.classList.add('is-loading');
                submit.setAttribute('disabled', 'disabled');
                const label = submit.querySelector('.label');
                if (label) {
                    label.textContent = 'Loading...';
                }
            });
        });

        eyeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const field = button.closest('.field');
                if (!field) return;

                const input = field.querySelector('input');
                if (!input) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

                const slash = button.querySelector('svg path:last-child');
                if (slash) {
                    slash.style.display = isHidden ? 'none' : '';
                }
            });
        });

        setTab(active);
    })();
</script>
</body>
</html>
