<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrganizationSeeder::class,
            WorkflowTestSeeder::class,
            LoginCredentialsSeeder::class,
            // ActivitySeeder::class, // Commented out - run manually if needed
        ]);
    }
}
