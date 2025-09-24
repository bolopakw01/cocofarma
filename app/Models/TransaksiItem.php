<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiItem extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi_items';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'bahan_baku_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'transaksi_id' => 'integer',
        'produk_id' => 'integer',
        'bahan_baku_id' => 'integer',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    // Relasi dengan Produk (untuk penjualan)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Relasi dengan BahanBaku (untuk pembelian)
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Accessor untuk nama item
    public function getNamaItemAttribute()
    {
        if ($this->produk) {
            return $this->produk->nama_produk;
        } elseif ($this->bahanBaku) {
            return $this->bahanBaku->nama_bahan;
        }

        return 'Item tidak ditemukan';
    }
}
