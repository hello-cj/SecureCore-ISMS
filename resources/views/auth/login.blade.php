<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — SecureCore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        :root {
            --primary:       #1d4ed8;
            --primary-hover: #1e40af;
            --accent:        #0ea5e9;
            --success:       #0f766e;
            --text-primary:  #0f172a;
            --text-secondary:#475569;
            --text-muted:    #94a3b8;
            --border:        #e2e8f0;
            --bg-card:       #ffffff;
            --bg-input:      #f8fafc;
            --error:         #dc2626;
            --error-bg:      #fef2f2;
            --error-border:  #fecaca;
            --warning:       #b45309;
            --warning-bg:    #fffbeb;
            --warning-border:#fde68a;
            --shadow-card:   0 20px 60px rgba(15,23,42,0.18), 0 4px 20px rgba(15,23,42,0.1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { height: 100%; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-y: auto;
            background: #0a0f1e;
        }

        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
            filter: blur(3px) brightness(0.38) saturate(0.65);
            transform: scale(1.05);
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: linear-gradient(155deg, rgba(29,78,216,0.16) 0%, rgba(15,23,42,0.44) 100%);
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 24px;
            animation: emerge 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
            margin: auto;
        }

        @keyframes emerge {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            background: var(--bg-card);
            border-radius: 22px;
            padding: 48px 44px 44px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(255,255,255,0.9);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 22px 22px 0 0;
        }

        /* Locked state — red accent bar */
        .login-card.is-locked::before {
            background: linear-gradient(90deg, #dc2626 0%, #f97316 100%);
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 30px;
            animation: emerge 0.65s 0.08s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .logo-mark { width: 48px; height: 48px; flex-shrink: 0; }

        .logo-name { display: flex; flex-direction: column; line-height: 1; }

        .logo-name .company {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .logo-name .tagline {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .card-header {
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
            animation: emerge 0.65s 0.12s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .card-header h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 23px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .card-header p { font-size: 14px; color: var(--text-secondary); line-height: 1.5; }

        .field {
            margin-bottom: 18px;
            animation: emerge 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .field:nth-of-type(1) { animation-delay: 0.16s; }
        .field:nth-of-type(2) { animation-delay: 0.21s; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-wrap .icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.18s;
            display: flex;
            align-items: center;
        }

        .field input {
            width: 100%;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 11px;
            padding: 13px 14px 13px 42px;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }

        .field input::placeholder { color: var(--text-muted); font-weight: 300; }

        .field input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
        }

        .field input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f1f5f9;
        }

        .input-wrap:focus-within .icon { color: var(--primary); }

        /* ── Lockout banner ── */
        .alert-lockout {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--warning-bg);
            border: 1.5px solid var(--warning-border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
            animation: emerge 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .lockout-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #b45309;
        }

        .lockout-body { flex: 1; }

        .lockout-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 3px;
        }

        .lockout-msg {
            font-size: 13px;
            color: var(--warning);
            line-height: 1.4;
        }

        .lockout-countdown {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 7px;
            padding: 4px 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #92400e;
        }

        /* ── Error banner ── */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 13.5px;
            color: var(--error);
            animation: emerge 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .alert-error svg { flex-shrink: 0; margin-top: 1px; }

        .field-error {
            font-size: 12.5px;
            color: var(--error);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Attempts remaining pill ── */
        .attempts-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 10px;
        }

        .attempts-pill.warn-high   { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .attempts-pill.warn-medium { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .attempts-pill.warn-low    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Submit button ── */
        .btn-wrap {
            margin-top: 24px;
            animation: emerge 0.65s 0.27s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .btn-signin {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 11px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
            box-shadow: 0 4px 16px rgba(29,78,216,0.32);
        }

        .btn-signin:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(29,78,216,0.38);
        }

        .btn-signin:active:not(:disabled) { transform: translateY(0); }

        .btn-signin:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .field .g-recaptcha { display: flex; justify-content: center; margin-top: 8px; }

        .card-footer-note {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-muted);
            animation: emerge 0.65s 0.34s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .card-footer-note svg { color: var(--success); flex-shrink: 0; }
    </style>
</head>
<body>

<div class="bg-scene" aria-hidden="true"></div>
<div class="bg-overlay" aria-hidden="true"></div>

<div class="login-wrapper" role="main">
    <div class="login-card {{ session('lockout') ? 'is-locked' : '' }}">

        <!-- Logo -->
        <div class="logo-wrap">
            <svg class="logo-mark" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="sg" x1="6" y1="3" x2="40" y2="42" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#1d4ed8"/>
                        <stop offset="100%" stop-color="#0ea5e9"/>
                    </linearGradient>
                    <linearGradient id="ig" x1="10" y1="7" x2="36" y2="38" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.22"/>
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0.06"/>
                    </linearGradient>
                </defs>
                <path d="M23 3L6 9.5V21C6 30.5 13.5 39.2 23 42C32.5 39.2 40 30.5 40 21V9.5L23 3Z" fill="url(#sg)"/>
                <path d="M23 7.5L10 13V21C10 28.8 15.8 36 23 38.2C30.2 36 36 28.8 36 21V13L23 7.5Z" fill="url(#ig)"/>
                <line x1="13" y1="21" x2="33" y2="21" stroke="rgba(255,255,255,0.18)" stroke-width="0.75"/>
                <rect x="15" y="21" width="16" height="11" rx="2.5" fill="white" opacity="0.92"/>
                <path d="M18.5 21V17.5A4.5 4.5 0 0127.5 17.5V21" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none" opacity="0.92"/>
                <circle cx="23" cy="26.5" r="1.6" fill="url(#sg)"/>
            </svg>
            <div class="logo-name">
                <span class="company">SecureCore</span>
                <span class="tagline">Information Security</span>
            </div>
        </div>

        <!-- Heading -->
        <div class="card-header">
            <h2>Employee Sign In</h2>
            <p>Access your secure workspace using your company credentials.</p>
        </div>

        {{-- Lockout banner --}}
        @if(session('lockout'))
            <div class="alert-lockout" role="alert">
                <div class="lockout-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="lockout-body">
                    <div class="lockout-title">Account Temporarily Locked</div>
                    <div class="lockout-msg">Too many failed login attempts. Please wait before trying again.</div>
                    @if(session('lockout_seconds'))
                        <div class="lockout-countdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Unlocks in <span id="countdown">{{ session('lockout_seconds') }}</span>s
                        </div>
                    @endif
                </div>
            </div>
        @elseif($errors->any() && !session('lockout'))
            {{-- Regular error banner --}}
            <div class="alert-error" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="yourname@company.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        {{ session('lockout') ? 'disabled' : '' }}
                    >
                </div>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••••"
                        required
                        autocomplete="current-password"
                        {{ session('lockout') ? 'disabled' : '' }}
                    >
                </div>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                @error('g-recaptcha-response')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="btn-wrap">
                <button type="submit" class="btn-signin" {{ session('lockout') ? 'disabled' : '' }}>
                    {{ session('lockout') ? 'Account Locked' : 'Sign In to Workspace' }}
                </button>
            </div>
        </form>

        <div class="card-footer-note">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            Encrypted connection &bull; Account locks after 5 failed attempts
        </div>

    </div>
</div>

@if(session('lockout') && session('lockout_seconds'))
<script>
    let seconds = {{ session('lockout_seconds') }};
    const el    = document.getElementById('countdown');
    const btn   = document.querySelector('.btn-signin');
    const inputs = document.querySelectorAll('input');

    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            el.closest('.lockout-countdown').innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Unlocked — you may try again';
            btn.disabled = false;
            btn.textContent = 'Sign In to Workspace';
            inputs.forEach(i => i.disabled = false);
            document.querySelector('.login-card').classList.remove('is-locked');
        } else {
            el.textContent = seconds + 's';
        }
    }, 1000);
</script>
@endif

</body>
</html>