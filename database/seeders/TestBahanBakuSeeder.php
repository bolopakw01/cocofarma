<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if data already exists
        if (\App\Models\MasterBahanBaku::count() > 0) {
            echo "Data already exists, skipping seeder\n";
            return;
        }

        // Create master bahan baku with different stok_minimum values
        $masters = [
            [
                'kode_bahan' => 'MB001',
                'nama_bahan' => 'Gula Pasir',
                'satuan' => 'kg',
                'harga_per_satuan' => 15000,
                'stok_minimum' => 50,
                'status' => 'aktif'
            ],
            [
                'kode_bahan' => 'MB002',
                'nama_bahan' => 'Tepung Terigu',
                'satuan' => 'kg',
                'harga_per_satuan' => 12000,
                'stok_minimum' => 30,
                'status' => 'aktif'
            ],
            [
                'kode_bahan' => 'MB003',
                'nama_bahan' => 'Telur Ayam',
                'satuan' => 'kg',
                'harga_per_satuan' => 25000,
                'stok_minimum' => 20,
                'status' => 'aktif'
            ],
            [
                'kode_bahan' => 'MB004',
                'nama_bahan' => 'Mentega',
                'satuan' => 'kg',
                'harga_per_satuan' => 45000,
                'stok_minimum' => 10,
                'status' => 'aktif'
            ]
        ];

        foreach ($masters as $master) {
            $masterBaku = \App\Models\MasterBahanBaku::create($master);

            // Create operational bahan baku with different stock levels
            $stokLevels = [80, 25, 60, 5]; // Some above, some below minimum
            $stok = $stokLevels[array_rand($stokLevels)];

            \App\Models\BahanBaku::create([
                'master_bahan_id' => $masterBaku->id,
                'kode_bahan' => $masterBaku->kode_bahan . '-001',
                'nama_bahan' => $masterBaku->nama_bahan,
                'satuan' => $masterBaku->satuan,
                'harga_per_satuan' => $masterBaku->harga_per_satuan,
                'stok' => $stok,
                'tanggal_masuk' => now()->subDays(rand(1, 30)),
                'status' => 'aktif'
            ]);
        }
    }
}
