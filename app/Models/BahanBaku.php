<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BahanBaku extends Model
{
    use SoftDeletes;
    protected $table = 'bahan_baku';

    protected $fillable = [
        'master_bahan_id',
        'kode_bahan',
        'nama_bahan',
        'satuan',
        'harga_per_satuan',
        'stok',
        'stok_minimum',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'status'
    ];

    protected $casts = [
        'master_bahan_id' => 'integer',
        'harga_per_satuan' => 'decimal:2',
        'stok' => 'decimal:4',
        'stok_minimum' => 'decimal:4',
        'tanggal_masuk' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relationship dengan master bahan baku
    public function masterBahan()
    {
        return $this->belongsTo(MasterBahanBaku::class, 'master_bahan_id');
    }

    // Relationship dengan stok bahan baku (untuk FIFO tracking)
    public function stokBahanBaku()
    {
        return $this->hasMany(StokBahanBaku::class, 'bahan_baku_id');
    }

    // Relationship dengan produksi bahan
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class, 'bahan_baku_id');
    }

    // Relationship dengan transaksi items
    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class, 'bahan_baku_id');
    }

    // Accessor untuk total stok tersedia
    public function getTotalStokAttribute()
    {
        // Hitung total stok dari detail stok bahan baku yang masih tersedia
        return $this->stokBahanBaku()->where('sisa_stok', '>', 0)->sum('sisa_stok');
    }

    // Accessor untuk status stok
    public function getStatusStokAttribute()
    {
        $totalStok = $this->stok;

        // Default minimum stok dari master bahan atau 10 jika tidak ada
        $minimumStok = $this->masterBahan ? 10 : 10;

        if ($totalStok <= $minimumStok) {
            return 'RENDAH';
        } elseif ($totalStok <= ($minimumStok * 1.5)) {
            return 'SEDANG';
        } else {
            return 'AMAN';
        }
    }

    // Accessor untuk cek apakah bahan baku expired
    public function getIsExpiredAttribute()
    {
        if (!$this->tanggal_kadaluarsa) {
            return false;
        }

        return $this->tanggal_kadaluarsa < now()->toDateString();
    }

    // Scope untuk bahan baku aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk bahan baku yang belum expired
    public function scopeBelumExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('tanggal_kadaluarsa')
              ->orWhere('tanggal_kadaluarsa', '>=', now()->toDateString());
        });
    }

    /**
     * Generate kode_bahan automatically when creating new BahanBaku.
     * Format: B- + 10 random alphanumeric characters
     * Example: B-A1B2C3D4E5
     * Ensures uniqueness by checking database for existing codes.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_bahan)) {
                $prefix = 'B-';
                $maxAttempts = 20;
                $attempt = 0;

                do {
                    $randomSegment = strtoupper(Str::random(10));
                    $kode = $prefix . $randomSegment;
                    $attempt++;
                } while (
                    BahanBaku::withTrashed()->where('kode_bahan', $kode)->exists() &&
                    $attempt < $maxAttempts
                );

                if (BahanBaku::withTrashed()->where('kode_bahan', $kode)->exists()) {
                    throw new \RuntimeException('Gagal menghasilkan kode bahan baku unik setelah beberapa percobaan.');
                }

                $model->kode_bahan = $kode;
            }
        });
    }
}
