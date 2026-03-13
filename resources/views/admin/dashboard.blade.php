@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h2>Admin Dashboard</h2>

@can('manage', App\Models\User::class)
<a href="{{ route('employees.create') }}" class="btn">Add Employee</a>
@endcan

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->role }}</td>
            <td>{{ $employee->department->name ?? 'Not Assigned' }}</td>
            <td>
                <a href="{{ route('employees.edit',$employee->id) }}" class="btn">Edit</a>

                <form method="POST" action="{{ route('employees.destroy',$employee->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection