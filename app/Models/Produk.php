<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'satuan',
        'harga_jual',
        'stok',
        'minimum_stok',
        'foto',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'minimum_stok' => 'integer',
        'status' => 'string'
    ];

    // Relasi dengan PesananItem
    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class, 'produk_id');
    }

    // Relasi dengan TransaksiItem (penjualan)
    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class, 'produk_id');
    }

    // Relasi dengan Produksi
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'produk_id');
    }

    // Relasi ke komposisi bahan (BOM)
    public function produkBahans()
    {
        return $this->hasMany(ProdukBahan::class, 'produk_id');
    }

    // Accessor untuk status produk
    public function getStatusLabelAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    // Accessor untuk boolean status (untuk kompatibilitas dengan views)
    public function getIsActiveAttribute()
    {
        return $this->status === 'aktif';
    }

    // Scope untuk produk aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
