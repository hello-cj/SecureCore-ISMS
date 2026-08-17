<?php

namespace App\Http\Controllers;

use App\Models\MonitoredFile;
use App\Services\HashingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonitoredFileController extends Controller
{
    protected HashingService $hashingService;

    public function __construct(HashingService $hashingService)
    {
        $this->hashingService = $hashingService;
    }

    /**
     * Show the upload form.
     */
    public function create()
    {
        $this->authorize('uploadDocument', \App\Models\User::class);

        return view('monitored-files.create');
    }

    /**
     * Handle the file upload, store it, and generate the baseline hash.
     */
    public function store(Request $request)
    {
        $this->authorize('uploadDocument', \App\Models\User::class);

        $request->validate([
            'document' => ['required', 'file', 'max:10240'], // 10MB max
            'document_type' => ['required', 'string', 'max:100'],
        ]);

        $uploadedFile = $request->file('document');

        $storedFilename = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('', $storedFilename, 'monitored');

        $monitoredFile = MonitoredFile::create([
            'original_filename' => $uploadedFile->getClientOriginalName(),
            'stored_path'       => $path,
            'file_size'         => $uploadedFile->getSize(),
            'mime_type'         => $uploadedFile->getClientMimeType(),
            'document_type'     => $request->input('document_type'),
            'uploaded_by'       => Auth::id(),
            'status'            => 'pending',
        ]);

        $this->hashingService->createBaseline($monitoredFile);

        return redirect()->route('monitored-files.index')
            ->with('success', 'Document uploaded successfully. Baseline hash generated.');
    }

    /**
     * List files — employees see only their own, managers/admins see all.
     */
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['manager', 'admin'])) {
            $files = MonitoredFile::with(['uploader', 'latestIntegrityCheck'])
                ->latest()
                ->paginate(15);
        } else {
            $files = MonitoredFile::with(['uploader', 'latestIntegrityCheck'])
                ->where('uploaded_by', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('monitored-files.index', compact('files'));
    }

    /**
     * Show a single file's detail (hash history + integrity check log).
     */
    public function show(MonitoredFile $monitoredFile)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['manager', 'admin']) && $monitoredFile->uploaded_by !== $user->id) {
            abort(403);
        }

        $monitoredFile->load(['uploader', 'approver', 'hashes', 'integrityChecks.checkedByUser']);

        return view('monitored-files.show', compact('monitoredFile'));
    }

    /**
     * Approve a pending document — locks in its baseline as official.
     */
    public function approve(MonitoredFile $monitoredFile)
    {
        $this->authorize('approveDocument', \App\Models\User::class);

        $monitoredFile->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('monitored-files.show', $monitoredFile)
            ->with('success', 'Document approved. Baseline hash is now locked as official.');
    }

    /**
     * Reject a pending document.
     */
    public function reject(MonitoredFile $monitoredFile)
    {
        $this->authorize('approveDocument', \App\Models\User::class);

        $monitoredFile->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('monitored-files.show', $monitoredFile)
            ->with('success', 'Document rejected.');
    }

    /**
     * Manually trigger a verification check on this file.
     */
    public function verify(MonitoredFile $monitoredFile)
    {
        $this->authorize('verifyIntegrity', \App\Models\User::class);

        $check = $this->hashingService->verifyFile($monitoredFile);

        $message = match ($check->status) {
            'intact'   => 'Verification complete: file is INTACT — matches baseline.',
            'modified' => 'WARNING: file has been MODIFIED since baseline was recorded.',
            'missing'  => 'WARNING: file is MISSING from storage.',
            default    => 'Verification complete.',
        };

        return redirect()->route('monitored-files.show', $monitoredFile)
            ->with($check->status === 'intact' ? 'success' : 'warning', $message);
    }
}