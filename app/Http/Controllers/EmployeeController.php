<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List employees — admin sees all, manager sees their department only
    public function index()
{
    $this->authorize('viewAny', User::class);

    $user = auth()->user();

    if ($user->role === 'admin') {
        $employees = User::with('department')->get();
    } else {
        $employees = User::with('department')
            ->where('department_id', $user->department_id)
            ->get();
    }

    $authRole = strtolower($user->role); // 👈 add this

    return view('employees.index', compact('employees', 'authRole')); // 👈 add authRole
}

    // Show create form — admin only
    public function create()
    {
        $this->authorize('create', User::class);
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    // Store new employee — admin only
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8',
            'role'           => 'required|in:admin,manager,employee',
            'department_id'  => 'required|exists:departments,id',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
        ], [
            'password.min'      => 'Password must be at least 12 characters.',
            'password.max'      => 'Password must not exceed 16 characters.',
            'password.regex'    => 'Password must include uppercase, lowercase, a number, and a symbol.',
            'password.confirmed'=> 'Passwords do not match.',
        ]);

        $employee = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'department_id'  => $request->department_id,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
        ]);

        Log::channel('security')->info('Employee created', [
            'admin_id'    => auth()->id(),
            'employee_id' => $employee->id,
            'email'       => $employee->email,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    // Show edit form — admin or manager (department-scoped)
    public function edit(User $employee)
    {
        $this->authorize('update', $employee);
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    // Update employee — admin or manager (department-scoped)
    public function update(Request $request, User $employee)
    {
        $this->authorize('update', $employee);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $employee->id,
            'role'           => 'required|in:admin,manager,employee',
            'department_id'  => 'required|exists:departments,id',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        $updateData = [
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
        ];

        // Only admin can change role and department
        if ($user->role === 'admin') {
            $updateData['role']          = $request->role;
            $updateData['department_id'] = $request->department_id;
        }

        $employee->update($updateData);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $employee->update(['password' => Hash::make($request->password)]);
        }

        Log::channel('security')->info('Employee updated', [
            'updated_by'  => auth()->id(),
            'employee_id' => $employee->id,
            'email'       => $employee->email,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    // Delete — admin only
    public function destroy(User $employee)
    {
        $this->authorize('delete', $employee);

        Log::channel('security')->info('Employee deleted', [
            'admin_id'    => auth()->id(),
            'employee_id' => $employee->id,
            'email'       => $employee->email,
        ]);

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}