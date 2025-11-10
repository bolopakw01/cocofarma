<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_produk' => 'PRD' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama_produk' => fake()->word() . ' Product',
            'kategori' => fake()->randomElement(['Obat', 'Suplemen', 'Kosmetik']),
            'satuan' => fake()->randomElement(['Tablet', 'Kapsul', 'Botol', 'Tube']),
            'harga_jual' => fake()->numberBetween(10000, 100000),
            'stok' => fake()->numberBetween(0, 100),
            'minimum_stok' => fake()->numberBetween(5, 20),
            'foto' => null,
            'deskripsi' => fake()->sentence(),
            'status' => fake()->randomElement(['aktif', 'nonaktif']),
        ];
    }
}