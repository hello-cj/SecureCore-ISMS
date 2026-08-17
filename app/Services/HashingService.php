<?php

namespace App\Services;

use App\Models\MonitoredFile;
use App\Models\FileHash;
use App\Models\IntegrityCheck;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HashingService
{
    /**
     * Generate a hash for a file at a given full filesystem path.
     */
    public function generateHash(string $fullPath, string $algo = 'sha256'): ?string
    {
        if (!file_exists($fullPath)) {
            return null;
        }

        return hash_file($algo, $fullPath);
    }

    /**
     * Create the baseline hash record for a freshly uploaded file.
     */
    public function createBaseline(MonitoredFile $file, string $algo = 'sha256'): FileHash
    {
        $fullPath = Storage::disk('monitored')->path($file->stored_path);
        $hashValue = $this->generateHash($fullPath, $algo);

        return $file->hashes()->create([
            'algorithm'            => $algo,
            'hash_value'           => $hashValue,
            'type'                 => 'baseline',
            'size_at_hash'         => $file->file_size,
            'permissions_at_hash'  => $this->getPermissions($fullPath),
            'generated_by'         => Auth::id(),
            'generated_at'         => now(),
        ]);
    }

    /**
     * Run a verification check on a file: re-hash, compare to baseline, log result.
     */
    public function verifyFile(MonitoredFile $file): IntegrityCheck
    {
        $baseline = $file->baselineHash;
        $fullPath = Storage::disk('monitored')->path($file->stored_path);

        // Case 1: File missing from disk
        if (!file_exists($fullPath)) {
            return $file->integrityChecks()->create([
                'baseline_hash_id'      => $baseline?->id,
                'verification_hash_id'  => null,
                'status'                => 'missing',
                'checked_by'            => Auth::id(),
                'checked_at'            => now(),
                'notes'                 => 'File not found at expected storage path.',
            ]);
        }

        // Generate current hash + a verification record
        $algo = $baseline->algorithm ?? 'sha256';
        $currentHashValue = $this->generateHash($fullPath, $algo);

        $verificationHash = $file->hashes()->create([
            'algorithm'            => $algo,
            'hash_value'           => $currentHashValue,
            'type'                 => 'verification',
            'size_at_hash'         => filesize($fullPath),
            'permissions_at_hash'  => $this->getPermissions($fullPath),
            'generated_by'         => Auth::id(),
            'generated_at'         => now(),
        ]);

        // Case 2: No baseline exists yet (shouldn't normally happen)
        if (!$baseline) {
            return $file->integrityChecks()->create([
                'baseline_hash_id'      => null,
                'verification_hash_id'  => $verificationHash->id,
                'status'                => 'pending',
                'checked_by'            => Auth::id(),
                'checked_at'            => now(),
                'notes'                 => 'No baseline hash found to compare against.',
            ]);
        }

        // Case 3: Compare hashes
        $status = $this->compareHashes($baseline->hash_value, $currentHashValue) ? 'intact' : 'modified';

        return $file->integrityChecks()->create([
            'baseline_hash_id'      => $baseline->id,
            'verification_hash_id'  => $verificationHash->id,
            'status'                => $status,
            'checked_by'            => Auth::id(),
            'checked_at'            => now(),
            'notes'                 => $status === 'modified' ? 'Hash mismatch detected against baseline.' : null,
        ]);
    }

    /**
     * Compare two hash strings.
     */
    public function compareHashes(string $hash1, string $hash2): bool
    {
        return hash_equals($hash1, $hash2);
    }

    /**
     * Get file permissions in a readable format (e.g. "0644").
     */
    protected function getPermissions(string $fullPath): ?string
    {
        if (!file_exists($fullPath)) {
            return null;
        }

        return substr(sprintf('%o', fileperms($fullPath)), -4);
    }
}