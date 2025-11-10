<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MasterBahanBaku;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterBahanBaku>
 */
class MasterBahanBakuFactory extends Factory
{
    protected $model = MasterBahanBaku::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_bahan' => 'MBB' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama_bahan' => fake()->word() . ' Bahan',
            'satuan' => fake()->randomElement(['Kg', 'Liter', 'Pcs', 'Gram']),
            'harga_per_satuan' => fake()->numberBetween(5000, 50000),
            'deskripsi' => fake()->sentence(),
            'status' => fake()->randomElement(['aktif', 'nonaktif']),
            'stok_minimum' => fake()->numberBetween(10, 50),
        ];
    }
}