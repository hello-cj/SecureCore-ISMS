@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('warning') }}</div>
    @endif

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-xl font-bold">{{ $monitoredFile->original_filename }}</h1>
            <p class="text-gray-500 text-sm">
                {{ ucfirst($monitoredFile->document_type) }} ·
                Uploaded by {{ $monitoredFile->uploader->name }} on {{ $monitoredFile->created_at->format('Y-m-d H:i') }}
            </p>
        </div>
        <span class="px-3 py-1 rounded text-white text-sm
            @if($monitoredFile->status === 'approved') bg-green-600
            @elseif($monitoredFile->status === 'rejected') bg-red-600
            @else bg-yellow-500
            @endif">
            {{ ucfirst($monitoredFile->status) }}
        </span>
    </div>

    {{-- Approval Actions --}}
    @can('approveDocument', App\Models\User::class)
        @if ($monitoredFile->isPending())
            <div class="mb-6 flex gap-2">
                <form action="{{ route('monitored-files.approve', $monitoredFile) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
                </form>
                <form action="{{ route('monitored-files.reject', $monitoredFile) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded">Reject</button>
                </form>
            </div>
        @endif
    @endcan

    {{-- Verify Action --}}
    @can('verifyIntegrity', App\Models\User::class)
        <div class="mb-6">
            <form action="{{ route('monitored-files.verify', $monitoredFile) }}" method="POST">
                @csrf
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Run Integrity Verification</button>
            </form>
        </div>
    @endcan

    {{-- Integrity Check History --}}
    <h2 class="font-semibold mb-2">Integrity Check History</h2>
    <table class="w-full border-collapse border mb-8">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-left">Checked At</th>
                <th class="border p-2 text-left">Status</th>
                <th class="border p-2 text-left">Checked By</th>
                <th class="border p-2 text-left">Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($monitoredFile->integrityChecks->sortByDesc('checked_at') as $check)
                <tr>
                    <td class="border p-2">{{ $check->checked_at->format('Y-m-d H:i:s') }}</td>
                    <td class="border p-2">
                        <span class="px-2 py-1 rounded text-white text-sm
                            @if($check->status === 'intact') bg-green-600
                            @elseif($check->status === 'modified') bg-red-600
                            @elseif($check->status === 'missing') bg-gray-600
                            @else bg-yellow-500
                            @endif">
                            {{ ucfirst($check->status) }}
                        </span>
                    </td>
                    <td class="border p-2">{{ $check->checkedByUser->name ?? 'System' }}</td>
                    <td class="border p-2">{{ $check->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border p-2 text-center text-gray-400">No verification checks run yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Hash Log --}}
    <h2 class="font-semibold mb-2">Hash Log</h2>
    <table class="w-full border-collapse border text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-left">Type</th>
                <th class="border p-2 text-left">Algorithm</th>
                <th class="border p-2 text-left">Hash Value</th>
                <th class="border p-2 text-left">Generated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitoredFile->hashes->sortByDesc('generated_at') as $hash)
                <tr>
                    <td class="border p-2">{{ ucfirst($hash->type) }}</td>
                    <td class="border p-2">{{ strtoupper($hash->algorithm) }}</td>
                    <td class="border p-2 font-mono break-all">{{ $hash->hash_value }}</td>
                    <td class="border p-2">{{ $hash->generated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection