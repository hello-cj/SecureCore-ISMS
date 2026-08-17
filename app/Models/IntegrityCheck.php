<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'baseline_hash_id',
        'verification_hash_id',
        'status',
        'checked_by',
        'checked_at',
        'notes',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function file()
    {
        return $this->belongsTo(MonitoredFile::class, 'file_id');
    }

    public function baselineHash()
    {
        return $this->belongsTo(FileHash::class, 'baseline_hash_id');
    }

    public function verificationHash()
    {
        return $this->belongsTo(FileHash::class, 'verification_hash_id');
    }

    public function checkedByUser()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    // ── Status helpers ─────────────────────────────────────────
    public function isIntact(): bool
    {
        return $this->status === 'intact';
    }

    public function isModified(): bool
    {
        return $this->status === 'modified';
    }

    public function isMissing(): bool
    {
        return $this->status === 'missing';
    }
}