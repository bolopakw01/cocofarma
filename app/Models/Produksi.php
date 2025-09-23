<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksis';

    protected $fillable = [
        'nomor_produksi',
        'batch_produksi_id',
        'produk_id',
        'tanggal_produksi',
        'jumlah_target',
        'jumlah_hasil',
        'grade_kualitas',
        'biaya_produksi',
        'status',
        'catatan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'produk_id' => 'integer',
        'batch_produksi_id' => 'integer',
        'jumlah_target' => 'decimal:2',
        'jumlah_hasil' => 'decimal:2',
        'biaya_produksi' => 'decimal:2',
        'status' => 'string'
    ];

    // Relasi dengan Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Relasi dengan ProduksiBahan
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class, 'produksi_id');
    }

    // Relasi dengan BatchProduksi
    public function batchProduksi()
    {
        return $this->belongsTo(BatchProduksi::class, 'batch_produksi_id');
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan StokProduk
    public function stokProduk()
    {
        return $this->hasOne(StokProduk::class, 'batch_produksi_id', 'batch_produksi_id')
                    ->where('produk_id', $this->produk_id);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'rencana' => 'Rencana',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal'
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    // Accessor untuk total bahan digunakan
    public function getTotalBahanDigunakanAttribute()
    {
        return $this->produksiBahans->sum('jumlah_digunakan');
    }

    // Scope untuk produksi aktif
    public function scopeAktif($query)
    {
        return $query->whereNotIn('status', ['selesai', 'dibatalkan']);
    }

    // Scope berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
