<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert dummy produk if not exists
        if (DB::table('produks')->count() == 0) {
            DB::table('produks')->insert([
                [
                    'kode_produk' => 'PRD001',
                    'nama_produk' => 'Arang Batok Kelapa Premium 1kg',
                    'deskripsi' => 'Arang batok kelapa berkualitas tinggi untuk BBQ dan industri',
                    'kategori' => 'Arang Batok',
                    'harga_jual' => 25000.00,
                    'stok' => 150,
                    'minimum_stok' => 50,
                    'satuan' => 'kg',
                    'status' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kode_produk' => 'PRD002',
                    'nama_produk' => 'Asap Cair Batok Kelapa 500ml',
                    'deskripsi' => 'Asap cair alami dari batok kelapa untuk pengawet makanan',
                    'kategori' => 'Asap Cair',
                    'harga_jual' => 35000.00,
                    'stok' => 80,
                    'minimum_stok' => 25,
                    'satuan' => 'botol',
                    'status' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }

        // Insert dummy tungku if not exists
        if (DB::table('tungkus')->count() == 0) {
            DB::table('tungkus')->insert([
                [
                    'nama_tungku' => 'Tungku Gas Utama',
                    'kode_tungku' => 'TK-GAS-001',
                    'tipe_tungku' => 'gas',
                    'kapasitas_max' => 500.00,
                    'status' => 'aktif',
                    'biaya_operasional_per_jam' => 15000.00,
                    'spesifikasi' => 'Tungku gas LPG berkapasitas 500kg dengan kontrol suhu otomatis',
                    'tanggal_pembelian' => Carbon::now()->subMonths(6),
                    'catatan' => 'Tungku utama untuk produksi arang batok kelapa',
                    'created_at' => Carbon::now()->subMonths(6),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'nama_tungku' => 'Tungku Listrik Cadangan',
                    'kode_tungku' => 'TK-LISTRIK-001',
                    'tipe_tungku' => 'listrik',
                    'kapasitas_max' => 300.00,
                    'status' => 'aktif',
                    'biaya_operasional_per_jam' => 25000.00,
                    'spesifikasi' => 'Tungku listrik 220V dengan kapasitas 300kg',
                    'tanggal_pembelian' => Carbon::now()->subMonths(3),
                    'catatan' => 'Tungku cadangan untuk keadaan darurat',
                    'created_at' => Carbon::now()->subMonths(3),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }

        // Insert dummy batch produksi if not exists
        if (DB::table('batch_produksis')->count() == 0) {
            $produkId = DB::table('produks')->first()->id ?? 1;
            $tungkuId = DB::table('tungkus')->first()->id ?? 1;
            $userId = DB::table('users')->first()->id ?? 1;

            DB::table('batch_produksis')->insert([
                [
                    'nomor_batch' => 'BATCH-2025-001',
                    'produk_id' => $produkId,
                    'tungku_id' => $tungkuId,
                    'tanggal_produksi' => Carbon::now()->subDays(5),
                    'status' => 'selesai',
                    'waktu_mulai' => Carbon::now()->subDays(5)->setTime(8, 0, 0),
                    'waktu_selesai' => Carbon::now()->subDays(5)->setTime(16, 0, 0),
                    'total_biaya_bahan' => 1500000.00,
                    'total_biaya_operasional' => 250000.00,
                    'catatan' => 'Batch pertama bulan September 2025 - Kualitas baik',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(5),
                    'updated_at' => Carbon::now()->subDays(5),
                ],
                [
                    'nomor_batch' => 'BATCH-2025-002',
                    'produk_id' => $produkId,
                    'tungku_id' => $tungkuId,
                    'tanggal_produksi' => Carbon::now()->subDays(3),
                    'status' => 'selesai',
                    'waktu_mulai' => Carbon::now()->subDays(3)->setTime(9, 0, 0),
                    'waktu_selesai' => Carbon::now()->subDays(3)->setTime(17, 30, 0),
                    'total_biaya_bahan' => 1800000.00,
                    'total_biaya_operasional' => 280000.00,
                    'catatan' => 'Batch kedua - Peningkatan efisiensi produksi',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(3),
                    'updated_at' => Carbon::now()->subDays(3),
                ],
                [
                    'nomor_batch' => 'BATCH-2025-003',
                    'produk_id' => $produkId,
                    'tungku_id' => $tungkuId,
                    'tanggal_produksi' => Carbon::now()->subDays(1),
                    'status' => 'proses',
                    'waktu_mulai' => Carbon::now()->subDays(1)->setTime(10, 0, 0),
                    'waktu_selesai' => null,
                    'total_biaya_bahan' => 2000000.00,
                    'total_biaya_operasional' => 300000.00,
                    'catatan' => 'Batch ketiga - Dalam proses produksi',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(1),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }

        // Insert dummy produksi if not exists
        if (DB::table('produksis')->count() == 0) {
            $produkId = DB::table('produks')->first()->id ?? 1;
            $userId = DB::table('users')->first()->id ?? 1;

            DB::table('produksis')->insert([
                [
                    'nomor_produksi' => 'PROD-2025-001',
                    'produk_id' => $produkId,
                    'tanggal_produksi' => Carbon::now()->subDays(5),
                    'jumlah_target' => 1000,
                    'jumlah_hasil' => 950,
                    'status' => 'selesai',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(5),
                    'updated_at' => Carbon::now()->subDays(5),
                ],
                [
                    'nomor_produksi' => 'PROD-2025-002',
                    'produk_id' => $produkId,
                    'tanggal_produksi' => Carbon::now()->subDays(3),
                    'jumlah_target' => 1200,
                    'jumlah_hasil' => 1150,
                    'status' => 'selesai',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(3),
                    'updated_at' => Carbon::now()->subDays(3),
                ],
                [
                    'nomor_produksi' => 'PROD-2025-003',
                    'produk_id' => $produkId,
                    'tanggal_produksi' => Carbon::now()->subDays(1),
                    'jumlah_target' => 800,
                    'jumlah_hasil' => 720,
                    'status' => 'proses',
                    'user_id' => $userId,
                    'created_at' => Carbon::now()->subDays(1),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'nomor_produksi' => 'PROD-2025-004',
                    'produk_id' => $produkId,
                    'tanggal_produksi' => Carbon::now()->addDays(2),
                    'jumlah_target' => 1500,
                    'jumlah_hasil' => 0,
                    'status' => 'rencana',
                    'user_id' => $userId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }

        $this->command->info('Dummy data berhasil dibuat!');
        $this->command->info('Produk: ' . DB::table('produks')->count());
        $this->command->info('Tungku: ' . DB::table('tungkus')->count());
        $this->command->info('Batch Produksi: ' . DB::table('batch_produksis')->count());
        $this->command->info('Produksi: ' . DB::table('produksis')->count());
    }
}