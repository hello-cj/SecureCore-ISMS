{{-- resources/views/employees/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
  .dash {
    padding: 44px 52px 72px 52px;
    width: 100%;
    animation: dash-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  @keyframes dash-in {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .dash-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 36px;
    flex-wrap: wrap;
  }

  .dash-eyebrow {
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

  .dash-eyebrow::before {
    content: '';
    display: inline-block;
    width: 18px;
    height: 2px;
    background: var(--primary);
    border-radius: 2px;
  }

  .dash-title {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.6px;
    line-height: 1;
  }

  .dash-subtitle {
    margin-top: 7px;
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 400;
  }

  .btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
    box-shadow: 0 4px 14px rgba(29,78,216,0.3);
    white-space: nowrap;
    flex-shrink: 0;
  }

  .btn-add:hover {
    background: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(29,78,216,0.36);
  }

  .btn-add:active { transform: translateY(0); }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 36px;
    width: 100%;
  }

  .stat-card {
    border-radius: 16px;
    padding: 24px 26px;
    position: relative;
    overflow: hidden;
    animation: dash-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    transition: transform 0.18s, box-shadow 0.18s;
    cursor: default;
  }

  .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(15,23,42,0.15); }
  .stat-card:nth-child(1) { animation-delay: 0.07s; }
  .stat-card:nth-child(2) { animation-delay: 0.13s; }
  .stat-card:nth-child(3) { animation-delay: 0.19s; }

  .stat-card.blue  { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 60%, #3b82f6 100%); box-shadow: 0 8px 24px rgba(29,78,216,0.28); }
  .stat-card.teal  { background: linear-gradient(135deg, #0f766e 0%, #0d9488 60%, #14b8a6 100%); box-shadow: 0 8px 24px rgba(15,118,110,0.28); }
  .stat-card.slate { background: linear-gradient(135deg, #334155 0%, #475569 60%, #64748b 100%); box-shadow: 0 8px 24px rgba(51,65,85,0.28); }

  .stat-card::after  { content: ''; position: absolute; right: -24px; top: -24px; width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.08); }
  .stat-card::before { content: ''; position: absolute; right: 16px; bottom: -30px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.05); }

  .stat-icon-wrap { width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; position: relative; z-index: 1; }
  .stat-icon-wrap svg { color: #fff; }
  .stat-num { font-family: var(--font-display); font-size: 32px; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 5px; position: relative; z-index: 1; }
  .stat-lbl { font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.72); position: relative; z-index: 1; letter-spacing: 0.01em; }

  .table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(15,23,42,0.07);
    overflow: hidden;
    animation: dash-in 0.45s 0.24s cubic-bezier(0.22, 1, 0.36, 1) both;
    width: 100%;
  }

  .table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 26px;
    border-bottom: 1px solid var(--border);
    gap: 16px;
    flex-wrap: wrap;
  }

  .table-title h2 {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.2px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .table-title p { font-size: 12.5px; color: var(--text-muted); margin-top: 3px; }

  .count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 2px 9px;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    font-family: var(--font-display);
  }

  .search-field { position: relative; flex-shrink: 0; }
  .search-field svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
  .search-field input {
    width: 220px;
    background: var(--bg-input);
    border: 1.5px solid var(--border);
    border-radius: 9px;
    padding: 8px 12px 8px 34px;
    font-family: var(--font-body);
    font-size: 13px;
    color: var(--text-primary);
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s, width 0.2s;
    margin: 0;
  }
  .search-field input::placeholder { color: var(--text-muted); }
  .search-field input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(29,78,216,0.09); width: 260px; }

  .emp-table { width: 100%; border-collapse: collapse; }
  .emp-table thead tr { background: #f8fafc; border-bottom: 1px solid var(--border); }
  .emp-table thead th { padding: 12px 20px; font-family: var(--font-display); font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; text-align: left; white-space: nowrap; }
  .emp-table thead th:last-child { text-align: right; }
  .emp-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
  .emp-table tbody tr:last-child { border-bottom: none; }
  .emp-table tbody tr:hover { background: #f0f7ff; }
  .emp-table tbody td { padding: 15px 20px; font-size: 13.5px; color: var(--text-primary); vertical-align: middle; }
  .emp-table tbody td:last-child { text-align: right; }

  .cell-id { font-family: var(--font-display); font-size: 11.5px; font-weight: 600; color: var(--text-muted); background: var(--bg-input); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--border); }

  .cell-name { display: flex; align-items: center; gap: 11px; }
  .avatar { width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, var(--primary-light), #dbeafe); color: var(--primary); font-family: var(--font-display); font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; text-transform: uppercase; border: 1.5px solid #bfdbfe; }
  .name-text { font-weight: 600; color: var(--text-primary); font-size: 13.5px; }
  .cell-email { font-size: 13px; }

  /* Masked value styling */
  .masked {
    font-family: var(--font-display);
    font-size: 12.5px;
    color: var(--text-muted);
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 2px 8px;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  .masked svg { opacity: 0.5; flex-shrink: 0; }

  .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 600; font-family: var(--font-display); letter-spacing: 0.02em; white-space: nowrap; }
  .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; opacity: 0.6; }
  .badge-admin    { background: #ede9fe; color: #5b21b6; }
  .badge-manager  { background: var(--primary-light); color: #1e40af; }
  .badge-employee { background: #f0fdf4; color: #166534; }
  .badge-default  { background: var(--bg-input); color: var(--text-secondary); }

  .cell-dept      { font-size: 13px; color: var(--text-secondary); }
  .cell-dept.none { color: var(--text-muted); font-style: italic; font-size: 12.5px; }

  .action-group { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
  .act-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 13px; border-radius: 8px; font-family: var(--font-body); font-size: 12.5px; font-weight: 500; cursor: pointer; border: 1.5px solid transparent; text-decoration: none; transition: background 0.14s, border-color 0.14s, transform 0.1s, box-shadow 0.14s; white-space: nowrap; background: none; }
  .act-edit   { background: var(--primary-light); color: var(--primary); border-color: #bfdbfe; }
  .act-edit:hover   { background: #dbeafe; border-color: #93c5fd; transform: translateY(-1px); box-shadow: 0 3px 10px rgba(29,78,216,0.12); }
  .act-delete { background: var(--danger-light); color: var(--danger); border-color: #fecaca; }
  .act-delete:hover { background: #fee2e2; border-color: #fca5a5; transform: translateY(-1px); box-shadow: 0 3px 10px rgba(220,38,38,0.12); }
  .delete-form { display: inline; margin: 0; padding: 0; }

  .empty-row td { border: none !important; }
  .empty-box { padding: 60px 24px; text-align: center; }
  .empty-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--bg-input); border: 1.5px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--text-muted); }
  .empty-box p { font-size: 14px; color: var(--text-muted); }

  @media (max-width: 900px) {
    .dash { padding: 32px 28px 48px; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .stats-grid .stat-card:last-child { grid-column: span 2; }
  }

  @media (max-width: 640px) {
    .dash { padding: 24px 18px 48px; }
    .stats-grid { grid-template-columns: 1fr; }
    .stats-grid .stat-card:last-child { grid-column: span 1; }
    .emp-table thead th:nth-child(1), .emp-table tbody td:nth-child(1),
    .emp-table thead th:nth-child(6), .emp-table tbody td:nth-child(6) { display: none; }
    .search-field input, .search-field input:focus { width: 100%; }
    .table-toolbar { flex-direction: column; align-items: flex-start; }
    .search-field { width: 100%; }
  }
</style>

<div class="dash">

  {{-- Page Header --}}
  <div class="dash-header">
    <div>
      <div class="dash-eyebrow">Admin Panel</div>
      <h1 class="dash-title">Employee Dashboard</h1>
      <p class="dash-subtitle">Manage your team, roles, and departments from one place.</p>
    </div>

    @can('manage', App\Models\User::class)
      <a href="{{ route('employees.create') }}" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Add Employee
      </a>
    @endcan
  </div>

  {{-- Stats --}}
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.75 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
      </div>
      <div class="stat-num">{{ $employees->count() }}</div>
      <div class="stat-lbl">Total Employees</div>
    </div>

    <div class="stat-card teal">
      <div class="stat-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
        </svg>
      </div>
      <div class="stat-num">{{ $employees->pluck('department_id')->filter()->unique()->count() }}</div>
      <div class="stat-lbl">Departments</div>
    </div>

    <div class="stat-card slate">
      <div class="stat-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
        </svg>
      </div>
      <div class="stat-num">{{ $employees->whereIn('role', ['admin','Admin'])->count() }}</div>
      <div class="stat-lbl">Admin Accounts</div>
    </div>
  </div>

  {{-- Employee Table --}}
  <div class="table-card">
    <div class="table-toolbar">
      <div class="table-title">
        <h2>Employee Directory <span class="count-badge">{{ $employees->count() }}</span></h2>
        <p>All registered employee accounts and their access roles</p>
      </div>
      <div class="search-field">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        <input type="text" id="tableSearch" placeholder="Search name, email, role…">
      </div>
    </div>

    <div style="overflow-x: auto;">
      <table class="emp-table" id="empTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Role</th>
            <th>Department</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($employees as $employee)
          @php
            $isAdmin = $authRole === 'admin';

            // Email: mask everything before @ for non-admins
            // e.g. john.doe@email.com → j*******@email.com
            if ($isAdmin) {
              $displayEmail = $employee->email;
            } else {
              $parts    = explode('@', $employee->email);
              $local    = $parts[0] ?? '';
              $domain   = isset($parts[1]) ? '@' . $parts[1] : '';
              $displayEmail = (strlen($local) > 1 ? substr($local, 0, 1) . str_repeat('*', strlen($local) - 1) : $local) . $domain;
            }

            // Contact: mask middle digits for non-admins
            // e.g. 09171234567 → 0917***4567
            $contact = $employee->contact_number ?? null;
            if ($contact && !$isAdmin) {
              $len = strlen($contact);
              $displayContact = substr($contact, 0, 4) . str_repeat('*', max(0, $len - 8)) . substr($contact, -4);
            } else {
              $displayContact = $contact;
            }
          @endphp
          <tr>
            <td><span class="cell-id">#{{ $employee->id }}</span></td>
            <td>
              <div class="cell-name">
                <div class="avatar">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
                <span class="name-text">{{ $employee->name }}</span>
              </div>
            </td>
            <td>
              @if($isAdmin)
                <span class="cell-email" style="color:var(--text-secondary);">{{ $displayEmail }}</span>
              @else
                <span class="masked">
                  <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                  </svg>
                  {{ $displayEmail }}
                </span>
              @endif
            </td>
            <td>
              @if($contact)
                @if($isAdmin)
                  <span class="cell-email" style="color:var(--text-secondary);">{{ $displayContact }}</span>
                @else
                  <span class="masked">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    {{ $displayContact }}
                  </span>
                @endif
              @else
                <span class="cell-dept none">Not provided</span>
              @endif
            </td>
            <td>
              @php
                $role = strtolower($employee->role ?? '');
                $badgeClass = match($role) {
                  'admin'    => 'badge-admin',
                  'manager'  => 'badge-manager',
                  'employee' => 'badge-employee',
                  default    => 'badge-default',
                };
              @endphp
              <span class="badge {{ $badgeClass }}">{{ ucfirst($employee->role ?? 'N/A') }}</span>
            </td>
            <td>
              @if($employee->department)
                <span class="cell-dept">{{ $employee->department->name }}</span>
              @else
                <span class="cell-dept none">Not assigned</span>
              @endif
            </td>
            <td>
              <div class="action-group">
                <a href="{{ route('employees.edit', $employee->id) }}" class="act-btn act-edit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                  </svg>
                  Edit
                </a>
                <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                      class="delete-form"
                      onsubmit="return confirm('Remove {{ addslashes($employee->name) }} from the system? This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="act-btn act-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr class="empty-row">
            <td colspan="7">
              <div class="empty-box">
                <div class="empty-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                  </svg>
                </div>
                <p>No employees yet. Use <strong>Add Employee</strong> to get started.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
  const searchInput = document.getElementById('tableSearch');
  const rows = document.querySelectorAll('#empTable tbody tr:not(.empty-row)');
  searchInput.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    rows.forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
</script>

@endsection