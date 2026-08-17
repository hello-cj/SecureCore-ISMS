@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-xl font-bold mb-4">Upload Document</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('monitored-files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Document Type</label>
            <select name="document_type" class="border rounded w-full p-2" required>
                <option value="">-- Select --</option>
                <option value="leave_request">Leave Request</option>
                <option value="reimbursement">Reimbursement Form</option>
                <option value="incident_report">Incident Report</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">File</label>
            <input type="file" name="document" class="border rounded w-full p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Upload & Generate Baseline Hash
        </button>
    </form>
</div>
@endsection