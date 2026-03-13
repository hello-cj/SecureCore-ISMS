@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1>403 - Unauthorized</h1>
    <p>You do not have permission to access this page.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go Back</a>
</div>
@endsection