<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchProduksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_produksis';

    protected $fillable = [
        'nomor_batch',
        'produk_id',
        'tungku_id',
        'tanggal_produksi',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'total_biaya_bahan',
        'total_biaya_operasional',
        'catatan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'total_biaya_bahan' => 'decimal:2',
        'total_biaya_operasional' => 'decimal:2',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Tungku
    public function tungku()
    {
        return $this->belongsTo(Tungku::class, 'tungku_id');
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan Produk (untuk batch ini)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Relasi dengan Produksi (hasil produksi)
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'batch_produksi_id');
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'rencana' => 'Rencana',
            'proses' => 'Sedang Proses',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor untuk total biaya
    public function getTotalBiayaAttribute()
    {
        return $this->total_biaya_bahan + $this->total_biaya_operasional;
    }

    // Accessor untuk durasi produksi
    public function getDurasiProduksiAttribute()
    {
        if ($this->waktu_mulai && $this->waktu_selesai) {
            $durasi = $this->waktu_mulai->diff($this->waktu_selesai);
            return $durasi->format('%H:%I:%S');
        }
        return null;
    }

    // Scope untuk batch aktif
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['rencana', 'proses']);
    }
}
