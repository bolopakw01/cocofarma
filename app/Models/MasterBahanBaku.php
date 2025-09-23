<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBahanBaku extends Model
{
    protected $table = 'master_bahan_baku';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'satuan',
        'harga_per_satuan',
        'deskripsi',
        'status',
        'stok_minimum'
    ];

    protected $casts = [
        'harga_per_satuan' => 'decimal:2',
        'stok_minimum' => 'decimal:4',
        'status' => 'string'
    ];

    // Relationship dengan bahan baku operasional
    public function bahanBakus()
    {
        return $this->hasMany(BahanBaku::class, 'master_bahan_id');
    }

    // Relationship dengan bahan baku aktif
    public function bahanBakusAktif()
    {
        return $this->hasMany(BahanBaku::class, 'master_bahan_id')->where('status', 'aktif');
    }

    // Accessor untuk total stok semua bahan baku
    public function getTotalStokAttribute()
    {
        return $this->bahanBakusAktif->sum('stok');
    }

    // Accessor untuk rata-rata harga
    public function getRataRataHargaAttribute()
    {
        $bahanBakus = $this->bahanBakusAktif;
        if ($bahanBakus->isEmpty()) {
            return $this->harga_per_satuan;
        }

        return $bahanBakus->avg('harga_per_satuan');
    }

    // Scope untuk master bahan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Method untuk mendapatkan bahan baku dengan stok terbanyak
    public function getBahanBakuDenganStokTerbanyak()
    {
        return $this->bahanBakusAktif()->orderBy('stok', 'desc')->first();
    }
}