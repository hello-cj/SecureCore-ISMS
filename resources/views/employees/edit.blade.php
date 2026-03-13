{{-- resources/views/employees/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')

<style>
  .form-page {
    padding: 44px 52px 72px 52px;
    width: 100%;
    animation: form-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
  }

  @keyframes form-in {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .form-eyebrow {
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

  .form-eyebrow::before {
    content: '';
    display: inline-block;
    width: 18px;
    height: 2px;
    background: var(--primary);
    border-radius: 2px;
  }

  .form-title {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.6px;
    line-height: 1;
    margin-bottom: 6px;
  }

  .form-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 32px;
  }

  .form-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(15,23,42,0.07);
    overflow: hidden;
    max-width: 680px;
  }

  .form-card-header {
    padding: 20px 28px;
    border-bottom: 1px solid var(--border);
    background: #f8fafc;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .form-card-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .form-card-header-text h3 {
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
  }

  .form-card-header-text p {
    font-size: 12.5px;
    color: var(--text-muted);
    margin-top: 2px;
  }

  .form-body { padding: 28px; }

  .form-section-label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--text-muted);
    margin-bottom: 16px;
    margin-top: 28px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .form-section-label:first-child { margin-top: 0; }
  .form-section-label svg { color: var(--primary); opacity: 0.7; }

  .field-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }

  .field-wrap { display: flex; flex-direction: column; gap: 6px; }
  .field-wrap.span-2 { grid-column: span 2; }

  .field-label {
    font-family: var(--font-display);
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .field-label .req { color: #ef4444; font-size: 13px; }

  .field-wrap input,
  .field-wrap select,
  .field-wrap textarea {
    width: 100%;
    background: var(--bg-input);
    border: 1.5px solid var(--border);
    border-radius: 9px;
    padding: 10px 14px;
    font-family: var(--font-body);
    font-size: 13.5px;
    color: var(--text-primary);
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
    margin: 0;
    box-sizing: border-box;
  }

  .field-wrap input::placeholder,
  .field-wrap textarea::placeholder { color: var(--text-muted); }

  .field-wrap input:focus,
  .field-wrap select:focus,
  .field-wrap textarea:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(29,78,216,0.09);
  }

  .field-wrap input:disabled,
  .field-wrap select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #f1f5f9;
  }

  .field-wrap textarea { resize: vertical; min-height: 80px; line-height: 1.5; }
  .field-wrap select { cursor: pointer; }

  .input-icon-wrap { position: relative; }
  .input-icon-wrap svg {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
  }
  .input-icon-wrap input { padding-left: 36px !important; }

  .field-wrap input.is-error,
  .field-wrap select.is-error { border-color: #ef4444; background: #fff5f5; }

  .field-error {
    font-size: 12px;
    color: #dc2626;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  /* Read-only role display for managers */
  .role-readonly {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    background: var(--bg-input);
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 13.5px;
    color: var(--text-secondary);
    width: 100%;
  }

  .role-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .role-note {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  /* Validation errors */
  .error-banner {
    background: #fef2f2;
    border: 1.5px solid #fecaca;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .error-banner-icon { color: #dc2626; flex-shrink: 0; margin-top: 1px; }
  .error-banner ul { margin: 0; padding: 0; list-style: none; }
  .error-banner ul li { font-size: 13px; color: #991b1b; padding: 2px 0; }

  /* Optional password hint */
  .field-hint {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 3px;
  }

  .form-footer {
    padding: 20px 28px;
    border-top: 1px solid var(--border);
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
  }

  .btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: 9px;
    font-family: var(--font-display);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    background: var(--bg-input);
    color: var(--text-secondary);
    border: 1.5px solid var(--border);
    transition: all 0.15s;
  }

  .btn-cancel:hover { background: #e2e8f0; border-color: #cbd5e1; color: var(--text-primary); }

  .btn-save {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 24px;
    border-radius: 9px;
    font-family: var(--font-display);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    background: var(--primary);
    color: #fff;
    box-shadow: 0 4px 14px rgba(29,78,216,0.3);
    transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
  }

  .btn-save:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(29,78,216,0.36); }
  .btn-save:active { transform: translateY(0); }

  @media (max-width: 640px) {
    .form-page { padding: 24px 18px 48px; }
    .field-grid { grid-template-columns: 1fr; }
    .field-wrap.span-2 { grid-column: span 1; }
    .form-body { padding: 20px; }
    .form-footer { padding: 16px 20px; }
  }
</style>

<div class="form-page">

  {{-- Page header --}}
  <div class="form-eyebrow">Admin Panel</div>
  <h1 class="form-title">Edit Employee</h1>
  <p class="form-subtitle">Update the details for <strong>{{ $employee->name }}</strong>.</p>

  {{-- Validation errors --}}
  @if($errors->any())
    <div class="error-banner" style="max-width:680px;">
      <svg class="error-banner-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="form-card">

    <div class="form-card-header">
      <div class="form-card-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
        </svg>
      </div>
      <div class="form-card-header-text">
        <h3>Employee Information</h3>
        <p>Fields marked with <span style="color:#ef4444">*</span> are required</p>
      </div>
    </div>

    <form method="POST" action="{{ route('employees.update', $employee->id) }}">
      @csrf
      @method('PUT')

      <div class="form-body">

        {{-- Personal Info --}}
        <div class="form-section-label">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
          Personal Information
        </div>

        <div class="field-grid">

          <div class="field-wrap span-2">
            <label class="field-label">Full Name <span class="req">*</span></label>
            <div class="input-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
              </svg>
              <input type="text" name="name" placeholder="e.g. Juan dela Cruz"
                     value="{{ old('name', $employee->name) }}"
                     class="{{ $errors->has('name') ? 'is-error' : '' }}" required>
            </div>
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="field-wrap span-2">
            <label class="field-label">Email Address <span class="req">*</span></label>
            <div class="input-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
              <input type="email" name="email" placeholder="e.g. juan@company.com"
                     value="{{ old('email', $employee->email) }}"
                     class="{{ $errors->has('email') ? 'is-error' : '' }}" required>
            </div>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="field-wrap">
            <label class="field-label">Contact Number</label>
            <div class="input-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
              <input type="text" name="contact_number" placeholder="e.g. 09xx-xxx-xxxx"
                     value="{{ old('contact_number', $employee->contact_number) }}"
                     class="{{ $errors->has('contact_number') ? 'is-error' : '' }}">
            </div>
            @error('contact_number') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="field-wrap">
            <label class="field-label">Department <span class="req">*</span></label>
            {{-- Admin can change department, manager cannot --}}
            @if(auth()->user()->role === 'admin')
              <select name="department_id" class="{{ $errors->has('department_id') ? 'is-error' : '' }}" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                  <option value="{{ $department->id }}"
                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                  </option>
                @endforeach
              </select>
            @else
              {{-- Manager sees department as read-only --}}
              <input type="hidden" name="department_id" value="{{ $employee->department_id }}">
              <div class="role-readonly">{{ $employee->department->name ?? 'Not assigned' }}</div>
              <span class="role-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Only admins can change departments
              </span>
            @endif
            @error('department_id') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="field-wrap span-2">
            <label class="field-label">Address</label>
            <div class="input-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
              </svg>
              <input type="text" name="address" placeholder="e.g. 123 Main St, Davao City"
                     value="{{ old('address', $employee->address) }}"
                     class="{{ $errors->has('address') ? 'is-error' : '' }}">
            </div>
            @error('address') <span class="field-error">{{ $message }}</span> @enderror
          </div>

        </div>

        {{-- Account Settings --}}
        <div class="form-section-label" style="margin-top: 28px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
          Account Settings
        </div>

        <div class="field-grid">

          <div class="field-wrap">
            <label class="field-label">Role</label>

            @if(auth()->user()->role === 'admin')
              {{-- Admin can change role --}}
              <select name="role" class="{{ $errors->has('role') ? 'is-error' : '' }}" required>
                <option value="employee" {{ old('role', $employee->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                <option value="manager"  {{ old('role', $employee->role) === 'manager'  ? 'selected' : '' }}>Manager</option>
                <option value="admin"    {{ old('role', $employee->role) === 'admin'    ? 'selected' : '' }}>Admin</option>
              </select>
            @else
              {{-- Manager sees role as read-only --}}
              <input type="hidden" name="role" value="{{ $employee->role }}">
              @php
                $roleColor = match($employee->role) {
                  'admin'    => '#5b21b6',
                  'manager'  => '#1e40af',
                  'employee' => '#166534',
                  default    => '#64748b',
                };
              @endphp
              <div class="role-readonly">
                <span class="role-dot" style="background: {{ $roleColor }}"></span>
                {{ ucfirst($employee->role) }}
              </div>
              <span class="role-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Only admins can change roles
              </span>
            @endif
            @error('role') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="field-wrap">
            <label class="field-label">New Password</label>
            <div class="input-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
              </svg>
              <input type="password" name="password" placeholder="Leave blank to keep current"
                     class="{{ $errors->has('password') ? 'is-error' : '' }}">
            </div>
            <span class="field-hint">Leave blank to keep the existing password.</span>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
          </div>

        </div>

      </div>{{-- /.form-body --}}

      <div class="form-footer">
        <a href="{{ route('employees.index') }}" class="btn-cancel">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
          </svg>
          Cancel
        </a>
        <button type="submit" class="btn-save">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          Save Changes
        </button>
      </div>

    </form>
  </div>

</div>

@endsection