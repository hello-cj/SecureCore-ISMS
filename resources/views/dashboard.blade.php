{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
  .emp-dash {
    padding: 40px 48px 64px;
    max-width: 860px;
    animation: emp-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  @keyframes emp-in {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Page header ── */
  .emp-eyebrow {
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

  .emp-eyebrow::before {
    content: '';
    display: inline-block;
    width: 18px;
    height: 2px;
    background: var(--primary);
    border-radius: 2px;
  }

  .emp-title {
    font-family: var(--font-display);
    font-size: 27px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.5px;
    line-height: 1.1;
  }

  .emp-subtitle {
    margin-top: 7px;
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 36px;
  }

  /* ── Profile card ── */
  .profile-hero {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;              /* keeps banner corners clipped */
    box-shadow: 0 4px 24px rgba(15,23,42,0.07);
    margin-bottom: 20px;
    animation: emp-in 0.45s 0.08s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  /* Banner — taller so avatar fits fully inside */
  .profile-banner {
    height: 140px;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #0ea5e9 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    padding: 0 32px 20px;
    gap: 16px;
  }

  /* Decorative circles */
  .profile-banner::before {
    content: '';
    position: absolute;
    right: -30px; top: -30px;
    width: 150px; height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    pointer-events: none;
  }

  .profile-banner::after {
    content: '';
    position: absolute;
    left: 35%; bottom: -45px;
    width: 110px; height: 110px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    pointer-events: none;
  }

  /* Avatar — fully inside the banner */
  .profile-avatar {
    width: 68px;
    height: 68px;
    border-radius: 16px;
    background: rgba(255,255,255,0.92);
    border: 3px solid rgba(255,255,255,0.6);
    box-shadow: 0 4px 16px rgba(15,23,42,0.2);
    color: var(--primary);
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    letter-spacing: -0.5px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }

  /* Name + role inside banner, next to avatar */
  .banner-identity {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding-bottom: 2px;
    position: relative;
    z-index: 1;
    flex: 1;
  }

  .banner-name {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.3px;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .banner-email {
    font-size: 12.5px;
    color: rgba(255,255,255,0.7);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Role badge inside banner */
  .banner-role {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.28);
    font-family: var(--font-display);
    font-size: 11.5px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.03em;
    white-space: nowrap;
    position: relative;
    z-index: 1;
    align-self: flex-end;
    margin-bottom: 2px;
  }

  .banner-role::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: rgba(255,255,255,0.8);
  }

  /* Card body with info grid */
  .profile-body {
    padding: 26px 32px 30px;
  }

  /* ── Info grid ── */
  .info-divider {
    height: 1px;
    background: var(--border);
    margin: 0 0 22px;
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
  }

  .info-label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--text-muted);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .info-label svg { color: var(--primary); opacity: 0.7; }

  .info-value {
    font-size: 14.5px;
    font-weight: 500;
    color: var(--text-primary);
  }

  .info-value.muted {
    color: var(--text-muted);
    font-style: italic;
    font-weight: 400;
  }

  /* ── Security notice ── */
  .notice-card {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 14px;
    padding: 18px 22px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    animation: emp-in 0.45s 0.16s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  .notice-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: #dbeafe;
    color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .notice-title {
    font-family: var(--font-display);
    font-size: 13.5px;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 3px;
  }

  .notice-text p {
    font-size: 13px;
    color: #3b82f6;
    line-height: 1.5;
  }

  @media (max-width: 640px) {
    .emp-dash { padding: 24px 18px 48px; }
    .info-grid { grid-template-columns: 1fr; }
    .profile-body { padding: 20px 20px 24px; }
    .profile-banner { padding: 0 20px 16px; height: 130px; }
    .banner-role { display: none; }
  }
</style>

<div class="emp-dash">

  {{-- Header --}}
  <div class="emp-eyebrow">My Workspace</div>
  <h1 class="emp-title">Welcome back, {{ $employee->name }}!</h1>
  <p class="emp-subtitle">Here's your account information and access details.</p>

  {{-- Profile card --}}
  <div class="profile-hero">

    {{-- Banner with avatar + identity fully inside --}}
    <div class="profile-banner">
      <div class="profile-avatar">
        {{ strtoupper(substr($employee->name, 0, 2)) }}
      </div>

      <div class="banner-identity">
        <div class="banner-name">{{ $employee->name }}</div>
        <div class="banner-email">{{ $employee->email }}</div>
      </div>

      @php
        $role = strtolower($employee->role ?? '');
      @endphp
      <div class="banner-role">{{ ucfirst($employee->role ?? 'Employee') }}</div>
    </div>

    {{-- Info grid --}}
    <div class="profile-body">
      <div class="info-divider"></div>
      <div class="info-grid">

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      Full Name
    </div>
    <div class="info-value">{{ $employee->name }}</div>
  </div>

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
      </svg>
      Email Address
    </div>
    <div class="info-value">{{ $employee->email }}</div>
  </div>

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
      </svg>
      Contact Number
    </div>
    @if($employee->contact_number)
      <div class="info-value">{{ $employee->contact_number }}</div>
    @else
      <div class="info-value muted">Not provided</div>
    @endif
  </div>

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
      </svg>
      Access Role
    </div>
    <div class="info-value">{{ ucfirst($employee->role ?? 'Employee') }}</div>
  </div>

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
      </svg>
      Department
    </div>
    @if($employee->department)
      <div class="info-value">{{ $employee->department->name }}</div>
    @else
      <div class="info-value muted">Not assigned</div>
    @endif
  </div>

  <div>
    <div class="info-label">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
      </svg>
      Address
    </div>
    @if($employee->address)
      <div class="info-value">{{ $employee->address }}</div>
    @else
      <div class="info-value muted">Not provided</div>
    @endif
  </div>

</div>
    </div>
  </div>

  {{-- Security notice --}}
  <div class="notice-card">
    <div class="notice-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
      </svg>
    </div>
    <div class="notice-text">
      <div class="notice-title">Secure Session Active</div>
      <p>Your session is protected by end-to-end encryption. You will be automatically signed out after a period of inactivity.</p>
    </div>
  </div>

</div>

@endsection