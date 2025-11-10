<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class FixProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix product images by updating database entries to match existing files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking product images...');

        // Get all products with foto values
        $products = Produk::whereNotNull('foto')->get();

        // Get all existing image files in storage
        $existingFiles = collect(Storage::disk('public')->files('produk'))
            ->map(function($file) {
                return basename($file);
            })
            ->toArray();

        $this->info('Found ' . count($existingFiles) . ' image files in storage');
        $this->info('Files: ' . implode(', ', $existingFiles));

        foreach ($products as $product) {
            if (!in_array($product->foto, $existingFiles)) {
                $this->warn("Product '{$product->nama_produk}' has missing image: {$product->foto}");

                // If there's an existing file that looks like it should belong to this product,
                // or just use the first available image
                if (!empty($existingFiles)) {
                    $newImage = $existingFiles[0]; // Use first available image
                    $product->foto = $newImage;
                    $product->save();
                    $this->info("Updated product '{$product->nama_produk}' to use image: {$newImage}");
                } else {
                    // No images available, set to null
                    $product->foto = null;
                    $product->save();
                    $this->info("Removed missing image reference for product '{$product->nama_produk}'");
                }
            } else {
                $this->info("Product '{$product->nama_produk}' has valid image: {$product->foto}");
            }
        }

        $this->info('Product image check completed!');
    }
}
