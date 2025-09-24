<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananItem extends Model
{
    use SoftDeletes;
    protected $table = 'pesanan_items';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'pesanan_id' => 'integer',
        'produk_id' => 'integer',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    // Relasi dengan Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
