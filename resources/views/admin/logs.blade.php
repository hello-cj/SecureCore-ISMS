@extends('layouts.app')

@section('title', 'Security Logs')

@section('content')

<style>
  .logs-page {
    padding: 44px 52px 72px 52px;
    width: 100%;
    animation: logs-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  @keyframes logs-in {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .logs-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 32px;
    flex-wrap: wrap;
  }

  .logs-eyebrow {
    font-family: var(--font-display);
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--primary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .logs-eyebrow::before {
    content: '';
    display: inline-block;
    width: 18px;
    height: 2px;
    background: var(--primary);
    border-radius: 2px;
  }

  .logs-title {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.6px;
    line-height: 1;
  }

  .logs-subtitle {
    margin-top: 7px;
    font-size: 14px;
    color: var(--text-muted);
  }

  .log-stats {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    animation: logs-in 0.45s 0.06s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  .log-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 10px;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 600;
    border: 1.5px solid transparent;
  }

  .log-chip .chip-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
  }

  .log-chip .chip-count {
    font-size: 15px;
    font-weight: 800;
  }

  .chip-total   { background: #f1f5f9; border-color: var(--border); color: var(--text-secondary); }
  .chip-total   .chip-dot { background: var(--text-muted); }
  .chip-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
  .chip-success .chip-dot { background: #22c55e; }
  .chip-warning { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
  .chip-warning .chip-dot { background: #ef4444; }
  .chip-info    { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
  .chip-info    .chip-dot { background: #3b82f6; }

  .logs-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
    animation: logs-in 0.45s 0.1s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  .filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    border-radius: 8px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: 1.5px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    transition: all 0.15s;
  }

  .filter-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }

  .filter-btn.active-all     { background: #f1f5f9; border-color: #94a3b8; color: var(--text-primary); font-weight: 600; }
  .filter-btn.active-success { background: #f0fdf4; border-color: #86efac; color: #166534; font-weight: 600; }
  .filter-btn.active-failed  { background: #fef2f2; border-color: #fca5a5; color: #991b1b; font-weight: 600; }
  .filter-btn.active-logout  { background: #eff6ff; border-color: #93c5fd; color: #1e40af; font-weight: 600; }

  .filter-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
  }

  .date-filter {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: 4px;
  }

  .date-filter input[type="date"] {
    padding: 7px 10px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13px;
    font-family: var(--font-body);
    color: var(--text-primary);
    background: var(--bg-card);
    outline: none;
    cursor: pointer;
    transition: border-color 0.18s;
  }

  .date-filter input[type="date"]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(29,78,216,0.09);
  }

  .date-filter .date-sep {
    font-size: 12px;
    color: var(--text-muted);
  }

  .date-filter .clear-dates {
    padding: 7px 10px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 12px;
    background: var(--bg-card);
    color: var(--text-muted);
    cursor: pointer;
    font-family: var(--font-body);
    transition: all 0.15s;
  }

  .date-filter .clear-dates:hover {
    border-color: #fca5a5;
    color: #dc2626;
    background: #fef2f2;
  }

  .search-log {
    margin-left: auto;
    position: relative;
  }

  .search-log svg {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
  }

  .search-log input {
    width: 220px;
    background: var(--bg-input);
    border: 1.5px solid var(--border);
    border-radius: 9px;
    padding: 8px 12px 8px 34px;
    font-family: var(--font-body);
    font-size: 13px;
    color: var(--text-primary);
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s, width 0.2s;
    margin: 0;
  }

  .search-log input::placeholder { color: var(--text-muted); }
  .search-log input:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(29,78,216,0.09);
    width: 280px;
  }

  .logs-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(15,23,42,0.07);
    overflow: hidden;
    animation: logs-in 0.45s 0.14s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  .logs-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    background: #f8fafc;
  }

  .logs-card-header span {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
  }

  .log-entry {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--border);
    transition: background 0.12s;
    cursor: default;
  }

  .log-entry:last-child { border-bottom: none; }
  .log-entry:hover { background: #f8fafc; }

  .log-bar {
    width: 3px;
    min-height: 40px;
    border-radius: 3px;
    flex-shrink: 0;
    margin-top: 2px;
    align-self: stretch;
  }

  .bar-success { background: #22c55e; }
  .bar-failed  { background: #ef4444; }
  .bar-logout  { background: #3b82f6; }
  .bar-default { background: var(--border-hover); }

  .log-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .icon-success { background: #f0fdf4; color: #16a34a; }
  .icon-failed  { background: #fef2f2; color: #dc2626; }
  .icon-logout  { background: #eff6ff; color: #2563eb; }
  .icon-default { background: var(--bg-input); color: var(--text-muted); }

  .log-body { flex: 1; min-width: 0; }

  .log-top {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
    flex-wrap: wrap;
  }

  .log-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 9px;
    border-radius: 20px;
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .lbadge-success { background: #dcfce7; color: #166534; }
  .lbadge-failed  { background: #fee2e2; color: #991b1b; }
  .lbadge-logout  { background: #dbeafe; color: #1e40af; }
  .lbadge-default { background: var(--bg-input); color: var(--text-muted); }

  .log-time {
    font-size: 12px;
    color: var(--text-muted);
    font-family: var(--font-display);
    font-weight: 500;
  }

  .log-env {
    font-size: 11px;
    color: var(--text-muted);
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: 5px;
    padding: 1px 7px;
    font-family: var(--font-display);
    font-weight: 600;
    letter-spacing: 0.03em;
  }

  .log-message {
    font-size: 13.5px;
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 6px;
  }

  .log-meta {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 11.5px;
    color: var(--text-secondary);
    font-family: var(--font-body);
  }

  .meta-pill svg { color: var(--text-muted); flex-shrink: 0; }
  .meta-pill strong { color: var(--text-primary); font-weight: 600; }

  .logs-empty {
    padding: 60px 24px;
    text-align: center;
  }

  .logs-empty-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: var(--bg-input);
    border: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
    color: var(--text-muted);
  }

  .logs-empty p { font-size: 14px; color: var(--text-muted); }

  .no-results {
    padding: 40px 24px;
    text-align: center;
    display: none;
  }

  .no-results p { font-size: 14px; color: var(--text-muted); }

  .log-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 24px;
    border-top: 1px solid var(--border);
    font-size: 13px;
    color: var(--text-muted);
    font-family: var(--font-body);
  }

  .log-pagination .page-btns {
    display: flex;
    gap: 6px;
  }

  .log-pagination button {
    padding: 6px 14px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    background: var(--bg-card);
    color: var(--text-secondary);
    font-family: var(--font-body);
    transition: all 0.15s;
  }

  .log-pagination button:hover:not(:disabled) {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
  }

  .log-pagination button:disabled {
    opacity: 0.4;
    cursor: default;
  }

  @media (max-width: 768px) {
    .logs-page { padding: 24px 18px 48px; }
    .log-entry { padding: 12px 16px; }
    .logs-card-header { padding: 14px 16px; }
  }
</style>

<div class="logs-page">

  {{-- Header --}}
  <div class="logs-header">
    <div>
      <div class="logs-eyebrow">Admin Panel</div>
      <h1 class="logs-title">Security Logs</h1>
      <p class="logs-subtitle">Monitor authentication events and user activity across the system.</p>
    </div>
  </div>

  @php
    $parsed = collect($logs)->map(function ($line) {
      $line = trim($line);
      if (empty($line)) return null;

      $type = 'default';
      if (stripos($line, 'successful login') !== false)   $type = 'success';
      elseif (stripos($line, 'failed login') !== false)   $type = 'failed';
      elseif (stripos($line, 'logged out') !== false)     $type = 'logout';

      preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $timeMatch);
      $timestamp = $timeMatch[1] ?? '';

      preg_match('/\]\s*([\w]+)\.(INFO|WARNING|ERROR)/', $line, $envMatch);
      $env   = $envMatch[1] ?? '';
      $level = $envMatch[2] ?? '';

      preg_match('/(?:INFO|WARNING|ERROR):\s*([^{]+)/', $line, $msgMatch);
      $message = trim($msgMatch[1] ?? $line);

      preg_match('/\{.*\}/', $line, $jsonMatch);
      $context = [];
      if (!empty($jsonMatch[0])) {
        $decoded = json_decode($jsonMatch[0], true);
        if (is_array($decoded)) $context = $decoded;
      }

      return compact('type', 'timestamp', 'env', 'level', 'message', 'context', 'line');
    })->filter()->values();

    $totalCount   = $parsed->count();
    $successCount = $parsed->where('type', 'success')->count();
    $failedCount  = $parsed->where('type', 'failed')->count();
    $logoutCount  = $parsed->where('type', 'logout')->count();
  @endphp

  {{-- Summary chips --}}
  <div class="log-stats">
    <div class="log-chip chip-total">
      <span class="chip-dot"></span>
      <span class="chip-count">{{ $totalCount }}</span>
      Total Events
    </div>
    <div class="log-chip chip-success">
      <span class="chip-dot"></span>
      <span class="chip-count">{{ $successCount }}</span>
      Successful Logins
    </div>
    <div class="log-chip chip-warning">
      <span class="chip-dot"></span>
      <span class="chip-count">{{ $failedCount }}</span>
      Failed Attempts
    </div>
    <div class="log-chip chip-info">
      <span class="chip-dot"></span>
      <span class="chip-count">{{ $logoutCount }}</span>
      Logouts
    </div>
  </div>

  {{-- Filter toolbar --}}
  <div class="logs-toolbar">
    <button class="filter-btn active-all"     onclick="filterLogs('all',     this)">All Events</button>
    <button class="filter-btn"                onclick="filterLogs('success', this)">
      <span class="filter-dot" style="background:#22c55e"></span> Successful Logins
    </button>
    <button class="filter-btn"                onclick="filterLogs('failed',  this)">
      <span class="filter-dot" style="background:#ef4444"></span> Failed Attempts
    </button>
    <button class="filter-btn"                onclick="filterLogs('logout',  this)">
      <span class="filter-dot" style="background:#3b82f6"></span> Logouts
    </button>

    {{-- Date range filter --}}
    <div class="date-filter">
      <input type="date" id="dateFrom" value="{{ $dateFrom ?? '' }}" title="From date">
      <span class="date-sep">→</span>
      <input type="date" id="dateTo" value="{{ $dateTo ?? '' }}" title="To date">
      <button class="clear-dates" onclick="clearDates()" title="Clear date filter">✕</button>
    </div>

    <div class="search-log">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
      </svg>
      <input type="text" id="logSearch" placeholder="Search logs…">
    </div>
  </div>

  {{-- Log entries --}}
  <div class="logs-card">
    <div class="logs-card-header">
      <span>Timestamp</span>
      <span id="eventCount">{{ $totalCount }} events recorded</span>
    </div>

    @if($parsed->isEmpty())
      <div class="logs-empty">
        <div class="logs-empty-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
        </div>
        <p>No log entries found.</p>
      </div>
    @else
      <div id="logList">
        @foreach($parsed as $entry)
          @php
            $barClass   = 'bar-'    . $entry['type'];
            $iconClass  = 'icon-'   . $entry['type'];
            $badgeClass = 'lbadge-' . $entry['type'];

            $typeLabel = match($entry['type']) {
              'success' => 'Login Success',
              'failed'  => 'Login Failed',
              'logout'  => 'Logout',
              default   => 'Event',
            };

            $dt            = $entry['timestamp'] ? \Carbon\Carbon::parse($entry['timestamp']) : null;
            $dateFormatted = $dt ? $dt->format('M d, Y') : '';
            $timeFormatted = $dt ? $dt->format('h:i:s A') : '';
            $dateISO       = $dt ? $dt->format('Y-m-d') : '';
          @endphp

          <div class="log-entry"
               data-type="{{ $entry['type'] }}"
               data-raw="{{ strtolower($entry['line']) }}"
               data-date="{{ $dateISO }}">

            <div class="log-bar {{ $barClass }}"></div>

            {{-- Icon --}}
            <div class="log-icon {{ $iconClass }}">
              @if($entry['type'] === 'success')
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              @elseif($entry['type'] === 'failed')
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
              @elseif($entry['type'] === 'logout')
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
              @else
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
              @endif
            </div>

            {{-- Body --}}
            <div class="log-body">
              <div class="log-top">
                <span class="log-type-badge {{ $badgeClass }}">{{ $typeLabel }}</span>
                @if($entry['env'])
                  <span class="log-env">{{ $entry['env'] }}</span>
                @endif
                @if($dateFormatted)
                  <span class="log-time">{{ $dateFormatted }} &bull; {{ $timeFormatted }}</span>
                @endif
              </div>

              <div class="log-message">{{ $entry['message'] }}</div>

              <div class="log-meta">
                @if(!empty($entry['context']['email']))
                  <span class="meta-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <strong>{{ $entry['context']['email'] }}</strong>
                  </span>
                @endif

                @if(!empty($entry['context']['user_id']))
                  <span class="meta-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    User ID <strong>#{{ $entry['context']['user_id'] }}</strong>
                  </span>
                @endif

                @if(!empty($entry['context']['ip_address']))
                  <span class="meta-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253" />
                    </svg>
                    <strong>{{ $entry['context']['ip_address'] }}</strong>
                  </span>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="no-results" id="noResults">
        <p>No log entries match your filters.</p>
      </div>
    @endif
  </div>

</div>

<script>
  let currentFilter = 'all';
  const PAGE_SIZE = 50;
  let currentPage = 1;
  let visibleEntries = [];

  const allEntries = Array.from(document.querySelectorAll('#logList .log-entry'));

  function filterLogs(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.className = 'filter-btn');
    const activeMap = { all: 'active-all', success: 'active-success', failed: 'active-failed', logout: 'active-logout' };
    btn.classList.add(activeMap[type] || 'active-all');
    currentFilter = type;
    currentPage = 1;
    applyFilters();
  }

  function clearDates() {
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    currentPage = 1;
    applyFilters();
  }

  document.getElementById('logSearch').addEventListener('input', () => { currentPage = 1; applyFilters(); });
  document.getElementById('dateFrom').addEventListener('change', () => { currentPage = 1; applyFilters(); });
  document.getElementById('dateTo').addEventListener('change',   () => { currentPage = 1; applyFilters(); });

  function applyFilters() {
    const q        = document.getElementById('logSearch').value.toLowerCase().trim();
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo   = document.getElementById('dateTo').value;

    visibleEntries = allEntries.filter(entry => {
      const typeMatch   = currentFilter === 'all' || entry.dataset.type === currentFilter;
      const searchMatch = !q || entry.dataset.raw.includes(q);
      const entryDate   = entry.dataset.date;
      const fromMatch   = !dateFrom || entryDate >= dateFrom;
      const toMatch     = !dateTo   || entryDate <= dateTo;
      return typeMatch && searchMatch && fromMatch && toMatch;
    });

    allEntries.forEach(e => e.style.display = 'none');

    const countEl = document.getElementById('eventCount');
    if (countEl) countEl.textContent = visibleEntries.length + ' events recorded';

    renderPage();
  }

  function renderPage() {
    const existing = document.getElementById('logPagination');
    if (existing) existing.remove();

    const start = (currentPage - 1) * PAGE_SIZE;
    visibleEntries.slice(start, start + PAGE_SIZE).forEach(e => e.style.display = '');

    document.getElementById('noResults').style.display = visibleEntries.length === 0 ? 'block' : 'none';

    const totalPages = Math.ceil(visibleEntries.length / PAGE_SIZE);
    if (totalPages <= 1) return;

    const nav = document.createElement('div');
    nav.id = 'logPagination';
    nav.className = 'log-pagination';

    const info = document.createElement('span');
    const end = Math.min(currentPage * PAGE_SIZE, visibleEntries.length);
    info.textContent = `Showing ${start + 1}–${end} of ${visibleEntries.length} events`;

    const btns = document.createElement('div');
    btns.className = 'page-btns';

    const prev = document.createElement('button');
    prev.textContent = '← Prev';
    prev.disabled = currentPage === 1;
    prev.onclick = () => {
      allEntries.forEach(e => e.style.display = 'none');
      currentPage--;
      renderPage();
    };

    const next = document.createElement('button');
    next.textContent = 'Next →';
    next.disabled = currentPage === totalPages;
    next.onclick = () => {
      allEntries.forEach(e => e.style.display = 'none');
      currentPage++;
      renderPage();
    };

    btns.append(prev, next);
    nav.append(info, btns);
    document.querySelector('.logs-card').appendChild(nav);
  }

  // Initial render
  applyFilters();
</script>

@endsection