<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterBahanBaku;
use App\Models\BahanBaku;
use App\Models\StokBahanBaku;
use App\Models\Produk;
use App\Models\Tungku;
use App\Models\BatchProduksi;
use App\Models\Produksi;
use App\Models\ProduksiBahan;
use App\Models\StokProduk;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Mulai melakukan seeding data dummy realistis (termasuk status aktif) untuk Cocofarma (Mulai Jan 2026)...');
        $faker = Faker::create('id_ID');

        // 1. Tungku
        $tungku = Tungku::create([
            'kode_tungku' => 'TNK-01',
            'nama_tungku' => 'Tungku Kondensat 5 Ton',
            'kapasitas_max' => 5000,
            'kapasitas_min' => 1000,
            'satuan' => 'Kg',
            'status' => 'aktif'
        ]);

        $tungku2 = Tungku::create([
            'kode_tungku' => 'TNK-02',
            'nama_tungku' => 'Tungku Kondensat 2 Ton',
            'kapasitas_max' => 2000,
            'kapasitas_min' => 500,
            'satuan' => 'Kg',
            'status' => 'aktif'
        ]);

        // 2. Master Bahan Baku
        $masterBatok = MasterBahanBaku::create([
            'kode_bahan' => 'MB-001',
            'nama_bahan' => 'Tempurung Kelapa (Batok)',
            'satuan' => 'Kg',
            'harga_per_satuan' => 3400,
            'stok_minimum' => 1000,
            'deskripsi' => 'Batok kelapa kering berkualitas tinggi'
        ]);

        // 3. Master Produk
        $produkArang = Produk::create([
            'kode_produk' => 'PRD-ARANG-01',
            'nama_produk' => 'Arang Tempurung Kelapa',
            'kategori' => 'Barang Jadi',
            'satuan' => 'Kg',
            'grade_kualitas' => 'A',
            'harga_jual' => 11000,
            'stok' => 0,
            'minimum_stok' => 500,
            'status' => 'aktif'
        ]);

        $produkAsap = Produk::create([
            'kode_produk' => 'PRD-ASAP-01',
            'nama_produk' => 'Asap Cair (Liquid Smoke)',
            'kategori' => 'Produk Sampingan',
            'satuan' => 'Liter',
            'grade_kualitas' => 'A',
            'harga_jual' => 25000,
            'stok' => 0,
            'minimum_stok' => 100,
            'status' => 'aktif'
        ]);

        // Setup Rentang Waktu (1 Jan 2026 s/d Sekarang)
        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::now();
        
        $currentDate = $startDate->copy();
        
        // Simpan referensi stok global
        $totalStokBatok = 0;
        $totalStokArang = 0;
        $totalStokAsap = 0;

        $batchCounter = 1;
        $orderCounter = 1;
        $trxCounter = 1;
        $pembelianBahanBaku = null;

        // Loop per hari
        while ($currentDate <= $endDate) {
            
            $daysToToday = (int) $currentDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay());

            // 4. Pembelian Bahan Baku (setiap awal bulan atau saat stok menipis < 3000kg)
            if ($totalStokBatok < 3000 || $currentDate->day == 2) {
                $jumlahBeli = rand(10000, 20000); // 10-20 Ton
                $hargaBeli = 3400 + rand(-200, 200); // Fluktuasi harga bahan baku
                
                $pembelianBahanBaku = BahanBaku::create([
                    'master_bahan_id' => $masterBatok->id,
                    'kode_bahan' => 'B-' . $currentDate->format('Ymd') . '-' . rand(10,99),
                    'nama_bahan' => 'Tempurung Kelapa (Batok)',
                    'satuan' => 'Kg',
                    'harga_per_satuan' => $hargaBeli,
                    'stok' => $jumlahBeli,
                    'stok_minimum' => 1000,
                    'tanggal_masuk' => $currentDate->copy()->addHours(8),
                    'status' => 'aktif'
                ]);

                StokBahanBaku::create([
                    'bahan_baku_id' => $pembelianBahanBaku->id,
                    'nomor_batch' => 'IN-' . $currentDate->format('Ymd') . '-01',
                    'jumlah_masuk' => $jumlahBeli,
                    'sisa_stok' => $jumlahBeli,
                    'harga_satuan' => $hargaBeli,
                    'tanggal' => $currentDate->copy()->addHours(8),
                    'keterangan' => 'Restock Rutin'
                ]);
                
                $totalStokBatok += $jumlahBeli;
            }

            // 5. Produksi (Hampir setiap hari untuk memastikan pergerakan metrik harian)
            if (rand(1, 100) <= 90 && $totalStokBatok >= 3000) {
                $tungkuDipilih = rand(1, 100) <= 70 ? $tungku : $tungku2;
                $jumlahBahanDigunakan = $tungkuDipilih->id == 1 ? rand(3000, 4500) : rand(1000, 1800);
                
                // Pastikan stok bahan cukup
                if ($totalStokBatok >= $jumlahBahanDigunakan) {
                    
                    // Efisiensi/Yield berfluktuasi antara 30% - 33% untuk Arang, dan 4% - 6% untuk Asap cair
                    $yieldArang = rand(300, 335) / 1000;
                    $yieldAsap = rand(40, 60) / 1000;
                    
                    $jumlahHasilArang = round($jumlahBahanDigunakan * $yieldArang);
                    $jumlahHasilAsap = round($jumlahBahanDigunakan * $yieldAsap);

                    // Status dinamis: jika diproduksi H-1 atau H-0 dari sekarang, kemungkinan status = rencana/proses
                    $statusBatch = 'selesai';
                    if ($daysToToday === 0) {
                        $statusBatch = 'rencana';
                    } elseif ($daysToToday === 1) {
                        $statusBatch = 'proses';
                    }

                    // Buat Batch
                    $batchProduksi = BatchProduksi::create([
                        'nomor_batch' => 'BATCH-' . $currentDate->format('Ymd') . '-' . $batchCounter,
                        'produk_id' => $produkArang->id,
                        'tungku_id' => $tungkuDipilih->id,
                        'tanggal_produksi' => $currentDate,
                        'status' => $statusBatch,
                        'waktu_mulai' => $statusBatch != 'rencana' ? $currentDate->copy()->addHours(7) : null,
                        'waktu_selesai' => $statusBatch == 'selesai' ? $currentDate->copy()->addHours(15) : null, 
                        'total_biaya_bahan' => $jumlahBahanDigunakan * 3400,
                        'total_biaya_operasional' => rand(300000, 450000), // Fluktuasi biaya operasional
                        'catatan' => 'Produksi harian',
                        'user_id' => 1
                    ]);
                    $batchCounter++;

                    // Kurangi stok bahan FIFO (bahan digunakan sejak awal)
                    $stokTersediaList = StokBahanBaku::where('sisa_stok', '>', 0)->orderBy('tanggal', 'asc')->get();
                    $sisaBahanDipotong = $jumlahBahanDigunakan;
                    $biayaBahanTotal = 0;
                    
                    foreach ($stokTersediaList as $stokBatokDB) {
                        if ($sisaBahanDipotong <= 0) break;
                        
                        $potong = min($stokBatokDB->sisa_stok, $sisaBahanDipotong);
                        $stokBatokDB->sisa_stok -= $potong;
                        $stokBatokDB->save();
                        
                        $biayaBahanTotal += ($potong * $stokBatokDB->harga_satuan);
                        $sisaBahanDipotong -= $potong;
                    }
                    $totalStokBatok -= $jumlahBahanDigunakan;

                    // Buat Produksi Arang
                    $gradeArang = rand(1, 100) > 10 ? 'A' : 'B'; // 90% grade A
                    $produksiArang = Produksi::create([
                        'nomor_produksi' => 'PROD-' . $batchProduksi->nomor_batch . '-1',
                        'batch_produksi_id' => $batchProduksi->id,
                        'produk_id' => $produkArang->id,
                        'tanggal_produksi' => $currentDate,
                        'jumlah_target' => round($jumlahBahanDigunakan * 0.33), 
                        'jumlah_hasil' => $statusBatch == 'selesai' ? $jumlahHasilArang : 0,
                        'grade_kualitas' => $gradeArang,
                        'biaya_produksi' => $biayaBahanTotal + $batchProduksi->total_biaya_operasional,
                        'status' => $statusBatch,
                        'status_transfer' => $statusBatch == 'selesai' ? 'transferred' : 'pending',
                        'tanggal_transfer' => $statusBatch == 'selesai' ? $currentDate->copy()->addHours(16) : null,
                        'user_id' => 1
                    ]);

                    // Pemakaian Bahan
                    ProduksiBahan::create([
                        'produksi_id' => $produksiArang->id,
                        'bahan_baku_id' => $pembelianBahanBaku->id ?? 1,
                        'stok_bahan_baku_id' => $stokTersediaList->first()->id ?? null,
                        'jumlah_digunakan' => $jumlahBahanDigunakan,
                        'harga_satuan' => 3400,
                        'total_biaya' => $biayaBahanTotal
                    ]);

                    // Buat Produksi Asap Cair
                    $produksiAsap = Produksi::create([
                        'nomor_produksi' => 'PROD-' . $batchProduksi->nomor_batch . '-2',
                        'batch_produksi_id' => $batchProduksi->id,
                        'produk_id' => $produkAsap->id,
                        'tanggal_produksi' => $currentDate,
                        'jumlah_target' => round($jumlahBahanDigunakan * 0.05),
                        'jumlah_hasil' => $statusBatch == 'selesai' ? $jumlahHasilAsap : 0,
                        'grade_kualitas' => 'A',
                        'biaya_produksi' => 0, 
                        'status' => $statusBatch,
                        'status_transfer' => $statusBatch == 'selesai' ? 'transferred' : 'pending',
                        'tanggal_transfer' => $statusBatch == 'selesai' ? $currentDate->copy()->addHours(16) : null,
                        'user_id' => 1
                    ]);

                    if ($statusBatch == 'selesai') {
                        // Tambah Stok Produk Arang
                        StokProduk::create([
                            'produk_id' => $produkArang->id,
                            'batch_produksi_id' => $batchProduksi->id,
                            'jumlah_masuk' => $jumlahHasilArang,
                            'jumlah_keluar' => 0,
                            'sisa_stok' => $jumlahHasilArang,
                            'harga_satuan' => $biayaBahanTotal / max($jumlahHasilArang, 1), // HPP
                            'grade_kualitas' => $gradeArang,
                            'tanggal' => $currentDate->copy()->addHours(16),
                            'keterangan' => 'Hasil produksi batch ' . $batchProduksi->nomor_batch
                        ]);

                        // Tambah Stok Produk Asap
                        StokProduk::create([
                            'produk_id' => $produkAsap->id,
                            'batch_produksi_id' => $batchProduksi->id,
                            'jumlah_masuk' => $jumlahHasilAsap,
                            'jumlah_keluar' => 0,
                            'sisa_stok' => $jumlahHasilAsap,
                            'harga_satuan' => 0, // Asumsi by-product
                            'grade_kualitas' => 'A',
                            'tanggal' => $currentDate->copy()->addHours(16),
                            'keterangan' => 'Hasil produksi batch ' . $batchProduksi->nomor_batch
                        ]);

                        $totalStokArang += $jumlahHasilArang;
                        $totalStokAsap += $jumlahHasilAsap;
                    }
                }
            }

            // 6. Pesanan dan Transaksi (Hampir setiap hari untuk memastikan grafik penjualan penuh)
            $jumlahPesananHariIni = (rand(1, 100) <= 95 && $totalStokArang > 100) ? rand(1, 4) : 0;
            for ($p = 0; $p < $jumlahPesananHariIni; $p++) {
                if ($totalStokArang <= 50) break; // Stop jika stok habis
                $pelanggan = [
                    'PT Total Carbon Magelang' => 'B2B',
                    'CV Arang Jaya' => 'B2B',
                    'Kelompok Tani Sleman' => 'B2C',
                    'Toko Pertanian Makmur' => 'B2C',
                    'Bapak Budi (Retail)' => 'B2C'
                ];
                $namaPelanggan = array_rand($pelanggan);
                $tipePelanggan = $pelanggan[$namaPelanggan];

                $beliArang = $tipePelanggan == 'B2B' ? rand(1000, 3000) : rand(100, 500);
                $beliArang = min($beliArang, max(1, $totalStokArang));
                $beliAsap = rand(1, 100) <= 50 ? ($tipePelanggan == 'B2B' ? rand(50, 200) : rand(10, 50)) : 0; // 50% beli asap juga

                // Pastikan stok cukup (untuk pesanan selesai saja)
                if ($totalStokArang >= $beliArang && ($beliAsap == 0 || $totalStokAsap >= $beliAsap)) {
                    
                    $totalHarga = ($beliArang * 11000) + ($beliAsap * 25000);
                    
                    // Jika pesanan dalam 3 hari terakhir, mungkin belum selesai
                    $statusPesanan = 'selesai';
                    if ($daysToToday === 0) {
                        $statusPesanan = 'pending';
                    } elseif ($daysToToday === 1) {
                        $statusPesanan = 'diproses';
                    } elseif ($daysToToday === 2) {
                        $statusPesanan = 'diproses';
                    }

                    $pesanan = Pesanan::create([
                        'kode_pesanan' => 'ORD-' . $currentDate->format('Ymd') . '-' . str_pad($orderCounter, 3, '0', STR_PAD_LEFT),
                        'tanggal_pesanan' => $currentDate->copy()->addHours(9),
                        'nama_pelanggan' => $namaPelanggan,
                        'alamat' => $faker->address,
                        'no_telepon' => $faker->phoneNumber,
                        'email' => $faker->email,
                        'status' => $statusPesanan,
                        'total_harga' => $totalHarga
                    ]);
                    $orderCounter++;

                    // Item Arang
                    PesananItem::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $produkArang->id,
                        'jumlah' => $beliArang,
                        'harga_satuan' => 11000,
                        'subtotal' => $beliArang * 11000
                    ]);

                    // Item Asap
                    if ($beliAsap > 0) {
                        PesananItem::create([
                            'pesanan_id' => $pesanan->id,
                            'produk_id' => $produkAsap->id,
                            'jumlah' => $beliAsap,
                            'harga_satuan' => 25000,
                            'subtotal' => $beliAsap * 25000
                        ]);
                    }

                    if ($statusPesanan == 'selesai') {
                        // Kurangi Stok Arang
                        $stokArangList = StokProduk::where('produk_id', $produkArang->id)->where('sisa_stok', '>', 0)->orderBy('tanggal', 'asc')->get();
                        $sisaKurangArang = $beliArang;
                        foreach ($stokArangList as $stok) {
                            if ($sisaKurangArang <= 0) break;
                            $potong = min($stok->sisa_stok, $sisaKurangArang);
                            $stok->jumlah_keluar += $potong;
                            $stok->sisa_stok -= $potong;
                            $stok->save();
                            $sisaKurangArang -= $potong;
                        }
                        $totalStokArang -= $beliArang;

                        // Kurangi Stok Asap
                        if ($beliAsap > 0) {
                            $stokAsapList = StokProduk::where('produk_id', $produkAsap->id)->where('sisa_stok', '>', 0)->orderBy('tanggal', 'asc')->get();
                            $sisaKurangAsap = $beliAsap;
                            foreach ($stokAsapList as $stok) {
                                if ($sisaKurangAsap <= 0) break;
                                $potong = min($stok->sisa_stok, $sisaKurangAsap);
                                $stok->jumlah_keluar += $potong;
                                $stok->sisa_stok -= $potong;
                                $stok->save();
                                $sisaKurangAsap -= $potong;
                            }
                            $totalStokAsap -= $beliAsap;
                        }

                        // Buat Transaksi Pembayaran
                        $transaksi = Transaksi::create([
                            'kode_transaksi' => 'TRX-' . $currentDate->format('Ymd') . '-' . str_pad($trxCounter, 3, '0', STR_PAD_LEFT),
                            'tanggal_transaksi' => $currentDate->copy()->addHours(10),
                            'jenis_transaksi' => 'penjualan',
                            'total' => $totalHarga,
                            'keterangan' => 'Pembayaran lunas pesanan ' . $pesanan->kode_pesanan,
                            'status' => 'selesai'
                        ]);
                        $trxCounter++;

                        TransaksiItem::create([
                            'transaksi_id' => $transaksi->id,
                            'produk_id' => $produkArang->id,
                            'jumlah' => $beliArang,
                            'harga_satuan' => 11000,
                            'subtotal' => $beliArang * 11000
                        ]);

                        if ($beliAsap > 0) {
                            TransaksiItem::create([
                                'transaksi_id' => $transaksi->id,
                                'produk_id' => $produkAsap->id,
                                'jumlah' => $beliAsap,
                                'harga_satuan' => 25000,
                                'subtotal' => $beliAsap * 25000
                            ]);
                        }
                    } else if ($statusPesanan == 'diproses') {
                        // Jika diproses mungkin sudah ada DP atau transaksi pending
                        $transaksi = Transaksi::create([
                            'kode_transaksi' => 'TRX-' . $currentDate->format('Ymd') . '-' . str_pad($trxCounter, 3, '0', STR_PAD_LEFT),
                            'tanggal_transaksi' => $currentDate->copy()->addHours(10),
                            'jenis_transaksi' => 'penjualan',
                            'total' => $totalHarga,
                            'keterangan' => 'Menunggu pelunasan pesanan ' . $pesanan->kode_pesanan,
                            'status' => 'pending'
                        ]);
                        $trxCounter++;
                    }
                }
            }
            
            // Maju 1 hari
            $currentDate->addDay();
        }

        // Update Stok Final di Master Produk
        $produkArang->stok = $totalStokArang;
        $produkArang->save();
        
        $produkAsap->stok = $totalStokAsap;
        $produkAsap->save();

        // Update Stok Final di Master Bahan
        if ($pembelianBahanBaku) {
            $pembelianBahanBaku->stok = $totalStokBatok;
            $pembelianBahanBaku->save();
        }

        $this->command->info('Data dummy (Januari 2026 - Sekarang) dengan variasi status berhasil dibuat!');
    }
}
