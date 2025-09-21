<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produksi;
use App\Models\BatchProduksi;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;

class ProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada batch produksi, produk, dan user
        $batchProduksis = BatchProduksi::all();
        $produk = Produk::first();
        $user = User::first();

        if ($batchProduksis->isEmpty() || !$produk || !$user) {
            $this->command->warn('Batch Produksi, Produk, atau User belum ada. Jalankan seeder yang sesuai terlebih dahulu.');
            return;
        }

        $produksis = [
            [
                'nomor_produksi' => 'PROD-2025-001',
                'batch_produksi_id' => $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(5),
                'jumlah_target' => 1000,
                'jumlah_hasil' => 950,
                'grade_kualitas' => 'A',
                'biaya_produksi' => 1750000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi arang batok kelapa premium - Hasil memuaskan',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'nomor_produksi' => 'PROD-2025-002',
                'batch_produksi_id' => $batchProduksis->skip(1)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(3),
                'jumlah_target' => 1200,
                'jumlah_hasil' => 1150,
                'grade_kualitas' => 'A',
                'biaya_produksi' => 2080000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi asap cair - Efisiensi tinggi tercapai',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'nomor_produksi' => 'PROD-2025-003',
                'batch_produksi_id' => $batchProduksis->skip(2)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(1),
                'jumlah_target' => 800,
                'jumlah_hasil' => 720,
                'grade_kualitas' => 'B',
                'biaya_produksi' => 2300000.00,
                'status' => 'proses',
                'catatan' => 'Produksi arang aktif - Dalam proses pematangan',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now(),
            ],
            [
                'nomor_produksi' => 'PROD-2025-004',
                'batch_produksi_id' => $batchProduksis->skip(3)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->addDays(2),
                'jumlah_target' => 1500,
                'jumlah_hasil' => 0,
                'grade_kualitas' => null,
                'biaya_produksi' => 0.00,
                'status' => 'rencana',
                'catatan' => 'Rencana produksi besar bulan depan',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nomor_produksi' => 'PROD-2025-005',
                'batch_produksi_id' => $batchProduksis->skip(4)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(7),
                'jumlah_target' => 900,
                'jumlah_hasil' => 880,
                'grade_kualitas' => 'A',
                'biaya_produksi' => 1900000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi awal bulan - Kualitas premium tercapai',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'nomor_produksi' => 'PROD-2025-006',
                'batch_produksi_id' => $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(10),
                'jumlah_target' => 1100,
                'jumlah_hasil' => 1050,
                'grade_kualitas' => 'A',
                'biaya_produksi' => 1950000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi rutin minggu lalu',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'nomor_produksi' => 'PROD-2025-007',
                'batch_produksi_id' => $batchProduksis->skip(1)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(12),
                'jumlah_target' => 1300,
                'jumlah_hasil' => 1200,
                'grade_kualitas' => 'B',
                'biaya_produksi' => 2200000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi dengan sedikit penyesuaian parameter',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'nomor_produksi' => 'PROD-2025-008',
                'batch_produksi_id' => $batchProduksis->skip(2)->first()->id ?? $batchProduksis->first()->id,
                'produk_id' => $produk->id,
                'tanggal_produksi' => Carbon::now()->subDays(15),
                'jumlah_target' => 950,
                'jumlah_hasil' => 920,
                'grade_kualitas' => 'A',
                'biaya_produksi' => 1850000.00,
                'status' => 'selesai',
                'catatan' => 'Produksi akhir bulan lalu - Hasil optimal',
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($produksis as $produksi) {
            Produksi::create($produksi);
        }

        $this->command->info('Produksi seeder berhasil dijalankan!');
    }
}