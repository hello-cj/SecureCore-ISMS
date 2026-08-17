<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoredFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_filename',
        'stored_path',
        'file_size',
        'mime_type',
        'document_type',
        'uploaded_by',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hashes()
    {
        return $this->hasMany(FileHash::class, 'file_id');
    }

    public function baselineHash()
    {
        return $this->hasOne(FileHash::class, 'file_id')
            ->where('type', 'baseline')
            ->latestOfMany();
    }

    public function latestVerificationHash()
    {
        return $this->hasOne(FileHash::class, 'file_id')
            ->where('type', 'verification')
            ->latestOfMany();
    }

    public function integrityChecks()
    {
        return $this->hasMany(IntegrityCheck::class, 'file_id');
    }

    public function latestIntegrityCheck()
    {
        return $this->hasOne(IntegrityCheck::class, 'file_id')->latestOfMany();
    }

    // ── Status helpers ─────────────────────────────────────────
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}