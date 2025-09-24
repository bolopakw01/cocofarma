<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CodeCounter;

class MasterBahanBaku extends Model
{
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

    // Accessor untuk rata-rata harga
    public function getRataRataHargaAttribute()
    {
        $bahanBakus = $this->bahanBakusAktif;
        if ($bahanBakus->isEmpty()) {
            return $this->harga_per_satuan;
        }

        return $bahanBakus->avg('harga_per_satuan');
    }

    // Scope untuk master bahan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Auto-generate kode_bahan for master bahan when creating.
     * Format: MB + DDMMYY + 3 letters + global sequence per hari (001, 002...)
     * Example: MB240925GUL001
     * If collision, add random 2 digits to ensure uniqueness (including soft-deleted).
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_bahan)) {
                $date = now()->format('dmy'); // DDMMYY
                $base = 'MB' . $date; // key for counter, per day

                $name = $model->nama_bahan ?? '';
                $abbr = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
                if ($abbr === '') {
                    $abbr = 'XXX';
                }

                DB::transaction(function () use ($base, $abbr, $model) {
                    $maxTries = 1000;
                    $num = 1;
                    $counter = CodeCounter::where('key', $base)->lockForUpdate()->first();
                    if ($counter) {
                        $num = $counter->counter + 1;
                    }
                    $found = false;
                    for ($i = 0; $i < $maxTries; $i++) {
                        $nextNumber = $num > 999 ? str_pad((string) $num, 4, '0', STR_PAD_LEFT) : str_pad((string) $num, 3, '0', STR_PAD_LEFT);
                        $kode = $base . $abbr . $nextNumber;
                        if (!MasterBahanBaku::withTrashed()->where('kode_bahan', $kode)->exists()) {
                            // Update counter in DB for each attempt
                            if (! $counter) {
                                CodeCounter::create(['key' => $base, 'counter' => $num]);
                            } else {
                                $counter->counter = $num;
                                $counter->save();
                            }
                            $found = true;
                            break;
                        }
                        $num++;
                    }
                    $model->kode_bahan = $kode;
                });
            }
        });
    }

    // Method untuk mendapatkan bahan baku dengan stok terbanyak
    public function getBahanBakuDenganStokTerbanyak()
    {
        return $this->bahanBakusAktif()->orderBy('stok', 'desc')->first();
    }
}