<?php

namespace App\Policies;

use App\Models\User;

class EmployeePolicy
{
    // Admin: full access
    // Manager: view all, edit only same department
    // Employee: view own profile only

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function view(User $user, User $employee): bool
    {
        return $user->role === 'admin'
            || $user->id === $employee->id
            || ($user->role === 'manager' && $user->department_id === $employee->department_id);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $employee): bool
    {
        // Admin can update anyone
        // Manager can only update employees in their own department
        // Manager cannot update another manager or admin
        if ($user->role === 'admin') return true;

        if ($user->role === 'manager') {
            return $user->department_id === $employee->department_id
                && $employee->role === 'employee';
        }

        return false;
    }

    public function delete(User $user, User $employee): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, User $employee): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, User $employee): bool
    {
        return $user->role === 'admin';
    }

    // Used by @can('manage', User::class) — admin-only actions
    public function manage(User $user): bool
    {
        return $user->role === 'admin';
    }

    // Used by @can('manageOrManager', User::class) — admin + manager
    public function manageOrManager(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}