<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBahanBaku;

class MasterBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterBahanBaku::factory(15)->create();
    }
}