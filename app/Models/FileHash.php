<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileHash extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'algorithm',
        'hash_value',
        'type',
        'size_at_hash',
        'permissions_at_hash',
        'generated_by',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function file()
    {
        return $this->belongsTo(MonitoredFile::class, 'file_id');
    }

    public function generatedByUser()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}