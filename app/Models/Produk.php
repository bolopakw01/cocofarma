<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CodeCounter;

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
        'status' => 'string'
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
                $date = now()->format('dmy'); // DDMMYY
                $base = 'MP' . $date; // key for counter, per day

                $name = $model->nama_produk ?? '';
                $abbr = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
                if ($abbr === '') {
                    $abbr = 'XXX';
                }

                DB::transaction(function () use ($base, $abbr, $model) {
                    $counter = CodeCounter::where('key', $base)->lockForUpdate()->first();
                    if (! $counter) {
                        $counter = CodeCounter::create(['key' => $base, 'counter' => 1]);
                        $num = 1;
                    } else {
                        $counter->counter = $counter->counter + 1;
                        $counter->save();
                        $num = $counter->counter;
                    }

                    $nextNumber = $num > 999 ? str_pad((string) $num, 4, '0', STR_PAD_LEFT) : str_pad((string) $num, 3, '0', STR_PAD_LEFT);
                    $kode = $base . $abbr . $nextNumber;

                    // Ensure uniqueness, including soft-deleted records
                    $maxTries = 10;
                    $tries = 0;
                    while (Produk::withTrashed()->where('kode_produk', $kode)->exists() && $tries < $maxTries) {
                        $rand = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
                        $kode = $base . $abbr . $nextNumber . $rand;
                        $tries++;
                    }
                    $model->kode_produk = $kode;
                });
            }
        });
    }
}
