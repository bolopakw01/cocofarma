<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'produks';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'satuan',
        'grade_kualitas',
        'harga_jual',
        'stok',
        'minimum_stok',
        'foto',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'minimum_stok' => 'integer',
        'status' => 'string',
        'grade_kualitas' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan PesananItem
    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class, 'produk_id');
    }

    // Relasi dengan TransaksiItem (penjualan)
    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class, 'produk_id');
    }

    // Relasi dengan StokProduk (stok operasional)
    public function stokProduks()
    {
        return $this->hasMany(StokProduk::class, 'produk_id');
    }

    // Relasi dengan Produksi
    public function produksis()
    {
        return $this->hasMany(Produksi::class, 'produk_id');
    }

    // Relasi ke komposisi bahan (BOM)
    public function produkBahans()
    {
        return $this->hasMany(ProdukBahan::class, 'produk_id');
    }

    // Accessor untuk status produk
    public function getStatusLabelAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    // Accessor untuk boolean status (untuk kompatibilitas dengan views)
    public function getIsActiveAttribute()
    {
        return $this->status === 'aktif';
    }

    // Scope untuk produk aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function setGradeKualitasAttribute($value)
    {
        $this->attributes['grade_kualitas'] = $value ? strtoupper($value) : null;
    }

    public function getGradeDisplayAttribute()
    {
        if (!$this->grade_kualitas) {
            return '-';
        }

        try {
            $grades = \App\Models\Pengaturan::getProductGrades();
            if (!empty($grades)) {
                $gradeIndex = ord($this->grade_kualitas) - 65;
                if (isset($grades[$gradeIndex])) {
                    $gradeName = $grades[$gradeIndex]['name'] ?? $this->grade_kualitas;
                    $gradeLabel = $grades[$gradeIndex]['label'] ?? '';
                    return trim($gradeLabel) ? sprintf('%s (%s)', $gradeName, $gradeLabel) : $gradeName;
                }
            }

            $label = \App\Models\Pengaturan::getGradeLabel($this->grade_kualitas);
            return sprintf('Grade %s (%s)', strtoupper($this->grade_kualitas), $label);
        } catch (\Throwable $e) {
            return strtoupper($this->grade_kualitas);
        }
    }

    /**
     * Generate kode_produk automatically when creating new Produk.
     * Format: MP + DDMMYY + 3 letters from name + global sequence per day (001, 002...)
     * Example: MP240925BOL001
     * If collision, add random 2 digits to ensure uniqueness.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_produk)) {
                $prefix = 'PRD-';
                $maxAttempts = 20;
                $attempt = 0;

                do {
                    $randomSegment = strtoupper(Str::random(10));
                    $kode = $prefix . $randomSegment;
                    $attempt++;
                } while (
                    Produk::withTrashed()->where('kode_produk', $kode)->exists() &&
                    $attempt < $maxAttempts
                );

                if (Produk::withTrashed()->where('kode_produk', $kode)->exists()) {
                    throw new \RuntimeException('Gagal menghasilkan kode produk unik setelah beberapa percobaan.');
                }

                $model->kode_produk = $kode;
            }
        });
    }
}
