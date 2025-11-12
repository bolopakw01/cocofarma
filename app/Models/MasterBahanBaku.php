<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MasterBahanBaku extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'master_bahan_baku';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'satuan',
        'harga_per_satuan',
        'deskripsi',
        'status',
        'stok_minimum'
    ];

    protected $casts = [
        'harga_per_satuan' => 'decimal:2',
        'stok_minimum' => 'decimal:4',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relationship dengan bahan baku operasional
    public function bahanBakus()
    {
        return $this->hasMany(BahanBaku::class, 'master_bahan_id');
    }

    // Relationship dengan bahan baku aktif
    public function bahanBakusAktif()
    {
        return $this->hasMany(BahanBaku::class, 'master_bahan_id')->where('status', 'aktif');
    }

    // Accessor untuk total stok semua bahan baku
    public function getTotalStokAttribute()
    {
        return $this->bahanBakusAktif->sum('stok');
    }

    // Accessor untuk format harga sebagai integer (untuk form input)
    public function getHargaPerSatuanFormattedAttribute()
    {
        return number_format($this->harga_per_satuan, 0, '', '');
    }

    // Accessor untuk format stok minimum sebagai integer (untuk form input)
    public function getStokMinimumFormattedAttribute()
    {
        return $this->stok_minimum ? number_format($this->stok_minimum, 0, '', '') : '';
    }

    // Scope untuk master bahan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Auto-generate kode_bahan for master bahan when creating.
     * Format: MB- + 10 random characters (alphanumeric)
     * Example: MB-6MMBCO68GE
     * Ensures uniqueness by checking database for existing codes.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_bahan)) {
                $prefix = 'MB-';
                $maxAttempts = 20;
                $attempt = 0;

                do {
                    $randomSegment = strtoupper(Str::random(10));
                    $kode = $prefix . $randomSegment;
                    $attempt++;
                } while (
                    MasterBahanBaku::withTrashed()->where('kode_bahan', $kode)->exists() &&
                    $attempt < $maxAttempts
                );

                if (MasterBahanBaku::withTrashed()->where('kode_bahan', $kode)->exists()) {
                    throw new \RuntimeException('Gagal menghasilkan kode bahan baku unik setelah beberapa percobaan.');
                }

                $model->kode_bahan = $kode;
            }
        });
    }

    // Method untuk mendapatkan bahan baku dengan stok terbanyak
    public function getBahanBakuDenganStokTerbanyak()
    {
        return $this->bahanBakusAktif()->orderBy('stok', 'desc')->first();
    }
}