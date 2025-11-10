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
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            ProdukSeeder::class,
            MasterBahanBakuSeeder::class,
            // Add other seeders as needed
        ]);
    }
}