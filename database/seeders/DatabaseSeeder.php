<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        
        // Call the DepartmentSeeder
        $this->call(\Database\Seeders\DepartmentSeeder::class);
        // Call the AdminSeeder
        $this->call(AdminSeeder::class);
        
    
    }
}
