<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@securecore.com')],
            [
                'name'                   => 'System Administrator',
                'email'                  => env('ADMIN_EMAIL', 'admin@gmail.com'),
                'password'               => Hash::make(env('ADMIN_PASSWORD')),
                'role'                   => 'admin',
                'department_id'          => null,
                'failed_login_attempts'  => 0,
                'locked_until'           => null,
            ]
        );
    }
}
