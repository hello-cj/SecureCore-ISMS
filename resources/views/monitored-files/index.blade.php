@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Monitored Documents</h1>
        <a href="{{ route('monitored-files.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Upload Document
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-left">Filename</th>
                <th class="border p-2 text-left">Type</th>
                <th class="border p-2 text-left">Uploaded By</th>
                <th class="border p-2 text-left">Status</th>
                <th class="border p-2 text-left">Integrity</th>
                <th class="border p-2 text-left">Uploaded At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($files as $file)
                <tr>
                    <td class="border p-2">
                        <a href="{{ route('monitored-files.show', $file) }}" class="text-blue-600 underline">
                            {{ $file->original_filename }}
                        </a>
                    </td>
                    <td class="border p-2">{{ $file->document_type }}</td>
                    <td class="border p-2">{{ $file->uploader->name ?? 'N/A' }}</td>
                    <td class="border p-2">{{ ucfirst($file->status) }}</td>
                    <td class="border p-2">
                        @if ($file->latestIntegrityCheck)
                            <span class="px-2 py-1 rounded text-white text-sm
                                @if($file->latestIntegrityCheck->status === 'intact') bg-green-600
                                @elseif($file->latestIntegrityCheck->status === 'modified') bg-red-600
                                @elseif($file->latestIntegrityCheck->status === 'missing') bg-gray-600
                                @else bg-yellow-500
                                @endif">
                                {{ ucfirst($file->latestIntegrityCheck->status) }}
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">Not yet verified</span>
                        @endif
                    </td>
                    <td class="border p-2">{{ $file->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border p-2 text-center text-gray-400">No documents uploaded yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
</div>
@endsection