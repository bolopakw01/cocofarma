<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Produk;
use Illuminate\Support\Str;

class PesananSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Ensure there are some products
		$produks = Produk::orderBy('id')->take(5)->get();

		if ($produks->isEmpty()) {
			// Nothing to seed items against
			echo "No products found, skip pesanan seeder.\n";
			return;
		}

		$samples = [
			[
				'tanggal_pesanan' => now()->subDays(1)->toDateString(),
				'nama_pelanggan' => 'Andi Wijaya',
				'alamat' => 'Jl. Merdeka No.1, Bandung',
				'no_telepon' => '081234567890',
				'status' => 'pending'
			],
			[
				'tanggal_pesanan' => now()->subDays(5)->toDateString(),
				'nama_pelanggan' => 'Siti Aminah',
				'alamat' => 'Jl. Pahlawan No.2, Jakarta',
				'no_telepon' => '082345678901',
				'status' => 'diproses'
			],
			[
				'tanggal_pesanan' => now()->subDays(10)->toDateString(),
				'nama_pelanggan' => 'Budi Santoso',
				'alamat' => 'Jl. Kenanga No.3, Surabaya',
				'no_telepon' => '083456789012',
				'status' => 'selesai'
			],
		];

		foreach ($samples as $sample) {
			$tanggal = date('ymd', strtotime($sample['tanggal_pesanan']));
			$count = Pesanan::whereDate('tanggal_pesanan', $sample['tanggal_pesanan'])->count() + 1;
			$kode = 'PSN' . $tanggal . str_pad($count, 3, '0', STR_PAD_LEFT);

			// create pesanan
			$pesanan = Pesanan::create([
				'kode_pesanan' => $kode,
				'tanggal_pesanan' => $sample['tanggal_pesanan'],
				'nama_pelanggan' => $sample['nama_pelanggan'],
				'alamat' => $sample['alamat'],
				'no_telepon' => $sample['no_telepon'],
				'status' => $sample['status'],
				'total_harga' => 0
			]);

			// attach 1-3 random produk items
			$items = $produks->random(rand(1, min(3, $produks->count())));

			$total = 0;
			foreach ($items as $item) {
				$qty = rand(1, 5);
				$harga = (float) $item->harga_jual;
				$subtotal = $qty * $harga;

				PesananItem::create([
					'pesanan_id' => $pesanan->id,
					'produk_id' => $item->id,
					'jumlah' => $qty,
					'harga_satuan' => $harga,
					'subtotal' => $subtotal
				]);

				$total += $subtotal;
			}

			// update total harga
			$pesanan->update(['total_harga' => $total]);
		}
	}
}
