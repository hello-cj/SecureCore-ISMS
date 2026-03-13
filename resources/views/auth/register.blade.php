@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="form-container">
    <div class="card">
        <h2>Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Department</label>
            <input type="text" name="department">

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</div>
@endsection