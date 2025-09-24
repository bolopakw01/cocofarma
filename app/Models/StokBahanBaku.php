<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokBahanBaku extends Model
{
    use SoftDeletes;
    protected $table = 'stok_bahan_baku';

    protected $fillable = [
        'bahan_baku_id',
        'nomor_batch',
        'supplier',
        'jumlah_masuk',
        'harga_satuan',
        'tanggal_kadaluarsa',
        'sisa_stok',
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'bahan_baku_id' => 'integer',
        'jumlah_masuk' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'sisa_stok' => 'decimal:2',
        'tanggal' => 'date',
        'tanggal_kadaluarsa' => 'date'
    ];

    protected $dates = ['deleted_at'];

    // Relationship dengan bahan baku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    // Relationship dengan produksi bahan (untuk tracking penggunaan)
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class, 'stok_bahan_baku_id');
    }

    // Scope untuk stok masuk
    public function scopeStokMasuk($query)
    {
        return $query->where('jumlah_masuk', '>', 0);
    }

    // Scope untuk stok keluar
    public function scopeStokKeluar($query)
    {
        return $query->where('jumlah_keluar', '>', 0);
    }

    // Scope berdasarkan tanggal
    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    // Scope berdasarkan periode
    public function scopePeriode($query, $tanggalAwal, $tanggalAkhir)
    {
        return $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
    }
}