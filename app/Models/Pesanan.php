<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;
    protected $table = 'pesanans';

    protected $fillable = [
        'kode_pesanan',
        'tanggal_pesanan',
        'nama_pelanggan',
        'alamat',
        'no_telepon',
        'status',
        'total_harga'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date',
        'total_harga' => 'decimal:2',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan PesananItem
    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class, 'pesanan_id');
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor untuk total item
    public function getTotalItemAttribute()
    {
        return $this->pesananItems->sum('jumlah');
    }

    // Scope untuk pesanan aktif
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
