<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;

class UpdateProductFoto extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = Produk::find(134);
        if ($produk) {
            $produk->foto = 'produk_GsHgReJ6FizE.jpg';
            $produk->save();
            $this->command->info('Updated product foto to: ' . $produk->foto);
        } else {
            $this->command->error('Product with ID 134 not found');
        }
    }
}
