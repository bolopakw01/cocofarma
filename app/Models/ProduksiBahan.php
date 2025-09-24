<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProduksiBahan extends Model
{
    use SoftDeletes;
    protected $table = 'produksi_bahans';

    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',
        'stok_bahan_baku_id',
        'jumlah_digunakan',
        'harga_satuan',
        'total_biaya',
        'harga_override'
    ];

    protected $casts = [
        'produksi_id' => 'integer',
        'bahan_baku_id' => 'integer',
        'stok_bahan_baku_id' => 'integer',
        'jumlah_digunakan' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_biaya' => 'decimal:2',
        'harga_override' => 'decimal:4'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Produksi
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    // Relasi dengan BahanBaku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Relasi dengan StokBahanBaku
    public function stokBahanBaku()
    {
        return $this->belongsTo(StokBahanBaku::class, 'stok_bahan_baku_id');
    }
}
