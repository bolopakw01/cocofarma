<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StokBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\StokBahanBaku::create([
            'bahan_baku_id' => 1,
            'nomor_batch' => 'BATCH-001',
            'supplier' => 'Supplier A',
            'jumlah_masuk' => 12.00,
            'harga_satuan' => 1.00,
            'tanggal_kadaluarsa' => now()->addDays(30),
            'sisa_stok' => 12.00,
            'tanggal' => now(),
            'keterangan' => 'Stok awal untuk testing'
        ]);
    }
}
