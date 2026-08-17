<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'contact_number',
        'address',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'      => 'datetime',
        'locked_until'           => 'datetime',  // ← lets Carbon handle it automatically
        'password'               => 'hashed',
    ];

    // ── Role helpers ──────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    // ── Lockout helpers ───────────────────────────────────────
    public function isLocked(): bool
    {
        return $this->locked_until && now()->lt($this->locked_until);
    }

    public function lockoutSecondsRemaining(): int
    {
        if (!$this->locked_until) return 0;
        return (int) now()->diffInSeconds($this->locked_until, false);
    }

    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        $this->refresh();

        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(5)]);
        }
    }

    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ]);
    }

    // ── Relationships ─────────────────────────────────────────
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}