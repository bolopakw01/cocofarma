<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BatchProduksi;
use App\Models\Produk;
use App\Models\Tungku;
use App\Models\User;
use Carbon\Carbon;

class BatchProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada produk dan tungku
        $produk = Produk::first();
        $tungku = Tungku::first();
        $user = User::first();

        if (!$produk || !$tungku || !$user) {
            $this->command->warn('Produk, Tungku, atau User belum ada. Jalankan seeder yang sesuai terlebih dahulu.');
            return;
        }

        $batchProduksis = [
            [
                'nomor_batch' => 'BATCH-2025-001',
                'produk_id' => $produk->id,
                'tungku_id' => $tungku->id,
                'tanggal_produksi' => Carbon::now()->subDays(5),
                'status' => 'selesai',
                'waktu_mulai' => Carbon::now()->subDays(5)->setTime(8, 0, 0),
                'waktu_selesai' => Carbon::now()->subDays(5)->setTime(16, 0, 0),
                'total_biaya_bahan' => 1500000.00,
                'total_biaya_operasional' => 250000.00,
                'catatan' => 'Batch pertama bulan September 2025 - Kualitas baik',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'nomor_batch' => 'BATCH-2025-002',
                'produk_id' => $produk->id,
                'tungku_id' => $tungku->id,
                'tanggal_produksi' => Carbon::now()->subDays(3),
                'status' => 'selesai',
                'waktu_mulai' => Carbon::now()->subDays(3)->setTime(9, 0, 0),
                'waktu_selesai' => Carbon::now()->subDays(3)->setTime(17, 30, 0),
                'total_biaya_bahan' => 1800000.00,
                'total_biaya_operasional' => 280000.00,
                'catatan' => 'Batch kedua - Peningkatan efisiensi produksi',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'nomor_batch' => 'BATCH-2025-003',
                'produk_id' => $produk->id,
                'tungku_id' => $tungku->id,
                'tanggal_produksi' => Carbon::now()->subDays(1),
                'status' => 'proses',
                'waktu_mulai' => Carbon::now()->subDays(1)->setTime(10, 0, 0),
                'waktu_selesai' => null,
                'total_biaya_bahan' => 2000000.00,
                'total_biaya_operasional' => 300000.00,
                'catatan' => 'Batch ketiga - Dalam proses produksi',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now(),
            ],
            [
                'nomor_batch' => 'BATCH-2025-004',
                'produk_id' => $produk->id,
                'tungku_id' => $tungku->id,
                'tanggal_produksi' => Carbon::now()->addDays(2),
                'status' => 'rencana',
                'waktu_mulai' => null,
                'waktu_selesai' => null,
                'total_biaya_bahan' => 0.00,
                'total_biaya_operasional' => 0.00,
                'catatan' => 'Batch keempat - Direncanakan untuk minggu depan',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nomor_batch' => 'BATCH-2025-005',
                'produk_id' => $produk->id,
                'tungku_id' => $tungku->id,
                'tanggal_produksi' => Carbon::now()->subDays(7),
                'status' => 'selesai',
                'waktu_mulai' => Carbon::now()->subDays(7)->setTime(7, 30, 0),
                'waktu_selesai' => Carbon::now()->subDays(7)->setTime(15, 45, 0),
                'total_biaya_bahan' => 1650000.00,
                'total_biaya_operasional' => 265000.00,
                'catatan' => 'Batch awal bulan - Kualitas premium',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
        ];

        foreach ($batchProduksis as $batch) {
            BatchProduksi::create($batch);
        }

        $this->command->info('Batch Produksi seeder berhasil dijalankan!');
    }
}