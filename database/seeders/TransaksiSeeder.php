<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least one user exists
        $user = DB::table('users')->first();
        if (! $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Seeder User',
                'email' => 'seeder@example.test',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $userId = $user->id;
        }

        // Ensure there are some products
        $produkCount = DB::table('produks')->count();
        if ($produkCount < 3) {
            $produkIds = [];
            for ($i = 1; $i <= 5; $i++) {
                $produkIds[] = DB::table('produks')->insertGetId([
                    'kode_produk' => 'DUMMY' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama_produk' => 'Produk Dummy ' . $i,
                    'kategori' => 'Dummy',
                    'satuan' => 'pcs',
                    'harga_jual' => 10000 + ($i * 1000),
                    'stok' => 50 + ($i * 10),
                    'minimum_stok' => 5,
                    'foto' => null,
                    'deskripsi' => 'Produk dummy untuk testing',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } else {
            $produkIds = DB::table('produks')->limit(5)->pluck('id')->toArray();
        }

        // Create several transaksi (mix penjualan and pembelian_bahan)
        $now = Carbon::now();

        for ($t = 1; $t <= 5; $t++) {
            // use 'penjualan' or 'pembelian' to match current schema
            $tipe = $t % 2 === 0 ? 'pembelian' : 'penjualan';
            $kode = 'TRX' . $now->copy()->subDays($t)->format('Ymd') . Str::upper(Str::random(4));

            $totalAmount = 0;

            $transaksiId = DB::table('transaksis')->insertGetId([
                'kode_transaksi' => $kode,
                'jenis_transaksi' => $tipe,
                'tanggal_transaksi' => $now->copy()->subDays($t)->toDateString(),
                'total' => 0, // fill later
                'keterangan' => 'Dummy transaksi #' . $t,
                'status' => 'selesai',
                'created_at' => $now->copy()->subDays($t),
                'updated_at' => $now->copy()->subDays($t),
            ]);

            // create between 1 and 3 items
            $itemsCount = rand(1, 3);
            for ($i = 0; $i < $itemsCount; $i++) {
                $produkId = $produkIds[array_rand($produkIds)];
                $jumlah = rand(1, 10);
                $harga = DB::table('produks')->where('id', $produkId)->value('harga_jual') ?: 10000;
                $subtotal = $jumlah * $harga;

                DB::table('transaksi_items')->insert([
                    'transaksi_id' => $transaksiId,
                    'produk_id' => $produkId,
                    'bahan_baku_id' => null,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                    'created_at' => $now->copy()->subDays($t),
                    'updated_at' => $now->copy()->subDays($t),
                ]);

                $totalAmount += $subtotal;
            }

            // update transaksi totals (use `total` column used by schema)
            DB::table('transaksis')->where('id', $transaksiId)->update([
                'total' => $totalAmount,
            ]);
        }
    }
}
