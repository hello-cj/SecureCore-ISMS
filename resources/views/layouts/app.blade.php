<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — SecureCore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════
           DESIGN TOKENS
        ══════════════════════════════════════════ */
        :root {
            --primary:        #1d4ed8;
            --primary-hover:  #1e40af;
            --primary-light:  #eff6ff;
            --accent:         #0ea5e9;
            --success:        #0f766e;
            --success-light:  #f0fdf4;
            --danger:         #dc2626;
            --danger-hover:   #b91c1c;
            --danger-light:   #fef2f2;
            --warning:        #d97706;
            --warning-light:  #fffbeb;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --border:         #e2e8f0;
            --border-hover:   #cbd5e1;
            --bg-page:        #f1f5f9;
            --bg-card:        #ffffff;
            --bg-input:       #f8fafc;
            --sidebar-bg:     #0f172a;
            --sidebar-width:  240px;
            --navbar-h:       60px;
            --shadow-sm:      0 1px 3px rgba(15,23,42,0.08), 0 1px 2px rgba(15,23,42,0.04);
            --shadow-md:      0 4px 16px rgba(15,23,42,0.08), 0 2px 6px rgba(15,23,42,0.04);
            --shadow-lg:      0 12px 40px rgba(15,23,42,0.10), 0 4px 12px rgba(15,23,42,0.05);
            --font-display:   'Plus Jakarta Sans', sans-serif;
            --font-body:      'Outfit', sans-serif;
        }

        /* ══════════════════════════════════════════
           RESET & BASE
        ══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: var(--font-body);
            background: var(--bg-page);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        /* ══════════════════════════════════════════
           SHELL
        ══════════════════════════════════════════ */
        .app-shell {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--navbar-h);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            gap: 16px;
        }

        /* Logo area */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .navbar-logo {
            width: 34px;
            height: 34px;
        }

        .navbar-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .navbar-brand-text .brand-name {
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .navbar-brand-text .brand-sub {
            font-size: 10px;
            font-weight: 500;
            color: var(--primary);
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* Right side */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .user-info .user-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1;
        }

        .user-info .user-role {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .navbar-divider {
            width: 1px;
            height: 22px;
            background: var(--border);
            margin: 0 4px;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            background: transparent;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }

        .btn-logout:hover {
            background: var(--danger-light);
            border-color: #fecaca;
            color: var(--danger);
        }

        /* ══════════════════════════════════════════
           BODY LAYOUT (sidebar + content)
        ══════════════════════════════════════════ */
        .app-body {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        /* ══════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            padding: 24px 0 32px;
            border-right: 1px solid rgba(255,255,255,0.05);
            box-shadow: 4px 0 24px rgba(15,23,42,0.12);
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 0 20px;
            margin-bottom: 8px;
            margin-top: 24px;
        }

        .sidebar-section-label:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            font-weight: 500;
            border-radius: 0;
            transition: background 0.15s, color 0.15s;
            position: relative;
            text-decoration: none;
        }

        .sidebar-link svg {
            flex-shrink: 0;
            opacity: 0.7;
            transition: opacity 0.15s;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-link:hover svg { opacity: 1; }

        .sidebar-link.active {
            background: rgba(29,78,216,0.25);
            color: #93c5fd;
            border-right: 3px solid var(--primary);
        }

        .sidebar-link.active svg { opacity: 1; color: #93c5fd; }

        /* Sidebar bottom: version tag */
        .sidebar-footer {
            margin-top: auto;
            padding: 0 20px;
        }

        .sidebar-version {
            font-size: 11px;
            color: rgba(255,255,255,0.18);
            letter-spacing: 0.03em;
        }

        /* ══════════════════════════════════════════
           MAIN CONTENT AREA
        ══════════════════════════════════════════ */
        .main-content {
            flex: 1;
            min-width: 0;
            overflow-y: auto;
        }

        /* ══════════════════════════════════════════
           SHARED COMPONENT STYLES
           (available to all child views)
        ══════════════════════════════════════════ */

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 30px;
            box-shadow: var(--shadow-sm);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-family: var(--font-display);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
            box-shadow: 0 3px 10px rgba(29,78,216,0.26);
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(29,78,216,0.32);
        }

        .btn:active { transform: translateY(0); }

        .btn-danger {
            background: var(--danger);
            box-shadow: 0 3px 10px rgba(220,38,38,0.22);
        }

        .btn-danger:hover {
            background: var(--danger-hover);
            box-shadow: 0 5px 16px rgba(220,38,38,0.3);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-secondary);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background: var(--bg-input);
            border-color: var(--border-hover);
            color: var(--text-primary);
            box-shadow: var(--shadow-md);
        }

        /* Forms */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 7px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 11px 14px;
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 14.5px;
            outline: none;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
            margin: 0;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
        }

        input::placeholder,
        textarea::placeholder { color: var(--text-muted); }

        /* Alerts */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: var(--danger-light);
            border: 1px solid #fecaca;
            color: var(--danger);
        }

        .alert-success {
            background: var(--success-light);
            border: 1px solid #bbf7d0;
            color: var(--success);
        }

        .alert-warning {
            background: var(--warning-light);
            border: 1px solid #fde68a;
            color: var(--warning);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            padding: 11px 16px;
            font-family: var(--font-display);
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            text-align: left;
            background: var(--bg-input);
            border-bottom: 1px solid var(--border);
        }

        table td {
            padding: 13px 16px;
            font-size: 14px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        table tbody tr:last-child td { border-bottom: none; }
        table tbody tr:hover { background: #f8fafc; }

        /* Form container (for login / standalone forms) */
        .form-container {
            max-width: 440px;
            margin: 80px auto;
            padding: 0 16px;
        }

        /* Page fade-in */
        .main-content { animation: content-in 0.4s ease both; }

        @keyframes content-in {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .navbar { padding: 0 16px; }
            .user-info { display: none; }
        }
    </style>
</head>

<body>
<div class="app-shell">

    {{-- ── NAVBAR ── --}}
    @if(auth()->check())
    <nav class="navbar">
        {{-- Brand / Logo --}}
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <svg class="navbar-logo" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="SecureCore">
                <defs>
                    <linearGradient id="nsg" x1="6" y1="3" x2="40" y2="42" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#1d4ed8"/>
                        <stop offset="100%" stop-color="#0ea5e9"/>
                    </linearGradient>
                    <linearGradient id="nig" x1="10" y1="7" x2="36" y2="38" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.22"/>
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0.06"/>
                    </linearGradient>
                </defs>
                <path d="M23 3L6 9.5V21C6 30.5 13.5 39.2 23 42C32.5 39.2 40 30.5 40 21V9.5L23 3Z" fill="url(#nsg)"/>
                <path d="M23 7.5L10 13V21C10 28.8 15.8 36 23 38.2C30.2 36 36 28.8 36 21V13L23 7.5Z" fill="url(#nig)"/>
                <line x1="13" y1="21" x2="33" y2="21" stroke="rgba(255,255,255,0.18)" stroke-width="0.75"/>
                <rect x="15" y="21" width="16" height="11" rx="2.5" fill="white" opacity="0.92"/>
                <path d="M18.5 21V17.5A4.5 4.5 0 0127.5 17.5V21" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none" opacity="0.92"/>
                <circle cx="23" cy="26.5" r="1.6" fill="url(#nsg)"/>
            </svg>
            <div class="navbar-brand-text">
                <span class="brand-name">SecureCore</span>
                <span class="brand-sub">Information Security</span>
            </div>
        </a>

        {{-- Right: user info + logout --}}
        <div class="navbar-right">
            <div class="navbar-user">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'Employee') }}</div>
                </div>
            </div>

            <div class="navbar-divider"></div>

            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </nav>
    @endif

    {{-- ── BODY (sidebar + content) ── --}}
    <div class="app-body">

        {{-- Sidebar — admin and manager --}}
        @auth
        @if(in_array(auth()->user()->role, ['admin', 'manager']))
        <aside class="sidebar">
            <div class="sidebar-section-label">Navigation</div>

            <a href="{{ route('employees.index') }}"
               class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            {{-- Logs — admin only --}}
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.logs') }}"
               class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                Logs
            </a>
            @endif

            <div class="sidebar-footer">
                <span class="sidebar-version">SecureCore v1.0</span>
            </div>
        </aside>
        @endif
        @endauth

        {{-- Page content --}}
        <main class="main-content">
            @yield('content')
        </main>

    </div>{{-- /.app-body --}}

</div>{{-- /.app-shell --}}
</body>
</html>