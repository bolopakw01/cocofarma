<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tungkus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tungku')->unique();
            $table->string('nama_tungku');
            $table->decimal('kapasitas_max', 10, 2);
            $table->decimal('kapasitas_min', 10, 2);
            $table->string('satuan');
            $table->string('status')->default('aktif');
            $table->string('lokasi')->nullable();
            $table->date('tanggal_installasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tungkus');
    }
};
