<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'jenis_transaksi',
        'total',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total' => 'decimal:2',
        'jenis_transaksi' => 'string',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan TransaksiItem
    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }

    // Accessor untuk jenis transaksi label
    public function getJenisTransaksiLabelAttribute()
    {
        $labels = [
            'penjualan' => 'Penjualan',
            'pembelian' => 'Pembelian'
        ];

        return $labels[$this->jenis_transaksi] ?? $this->jenis_transaksi;
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Scope untuk penjualan
    public function scopePenjualan($query)
    {
        return $query->where('jenis_transaksi', 'penjualan');
    }

    // Scope untuk pembelian
    public function scopePembelian($query)
    {
        return $query->where('jenis_transaksi', 'pembelian');
    }

    // Scope berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
