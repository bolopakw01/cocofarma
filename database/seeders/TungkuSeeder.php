<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tungku;
use Carbon\Carbon;

class TungkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tungkus = [
            [
                'nama_tungku' => 'Tungku Gas Utama',
                'kode_tungku' => 'TK-GAS-001',
                'tipe_tungku' => 'gas',
                'kapasitas_max' => 500.00, // kg
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
                'kapasitas_max' => 300.00, // kg
                'status' => 'aktif',
                'biaya_operasional_per_jam' => 25000.00,
                'spesifikasi' => 'Tungku listrik 220V dengan kapasitas 300kg',
                'tanggal_pembelian' => Carbon::now()->subMonths(3),
                'catatan' => 'Tungku cadangan untuk keadaan darurat',
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_tungku' => 'Tungku Minyak Lama',
                'kode_tungku' => 'TK-MINYAK-001',
                'tipe_tungku' => 'minyak',
                'kapasitas_max' => 400.00, // kg
                'status' => 'maintenance',
                'biaya_operasional_per_jam' => 12000.00,
                'spesifikasi' => 'Tungku minyak tanah kapasitas 400kg - perlu perawatan rutin',
                'tanggal_pembelian' => Carbon::now()->subYears(2),
                'catatan' => 'Sedang dalam perawatan berkala',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($tungkus as $tungku) {
            Tungku::create($tungku);
        }

        $this->command->info('Tungku seeder berhasil dijalankan!');
    }
}