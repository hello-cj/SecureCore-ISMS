<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\EmployeePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => EmployeePolicy::class, // map User to EmployeePolicy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // ✅ this is required!

        // Optional: define a Gate as well (works with can:manage)
        Gate::define('manage', function(User $user) {
            return $user->role === 'admin';
        });

        // ── File Integrity Module Gates ──────────────────────────
        Gate::define('uploadDocument', function (User $user) {
            return in_array($user->role, ['employee', 'manager', 'admin']);
        });

        Gate::define('approveDocument', function (User $user) {
            return in_array($user->role, ['manager', 'admin']);
        });

        Gate::define('verifyIntegrity', function (User $user) {
            return in_array($user->role, ['manager', 'admin']);
        });

        Gate::define('viewAllReports', function (User $user) {
            return in_array($user->role, ['manager', 'admin']);
        });
    }
}