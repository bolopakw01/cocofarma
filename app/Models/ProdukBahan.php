<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukBahan extends Model
{
    protected $table = 'produk_bahans';

    protected $fillable = [
        'produk_id',
        'master_bahan_id',
        'jumlah_per_unit'
    ];

    protected $casts = [
        'produk_id' => 'integer',
        'master_bahan_id' => 'integer',
        'jumlah_per_unit' => 'decimal:4'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function masterBahan()
    {
        return $this->belongsTo(MasterBahanBaku::class, 'master_bahan_id');
    }
}
