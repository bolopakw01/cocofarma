<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CodeCounter;

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
     * Format: B-(tanggal Ymd)+(3 huruf nama)+(sequence 3 digits, starting 001)
     * Example: B-20250924ABC001
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_bahan)) {
                $prefix = 'B-';
                $date = now()->format('Ymd');
                $name = $model->nama_bahan ?? $model->nama ?? '';
                $abbr = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
                if ($abbr === '') {
                    $abbr = 'XXX';
                }
                $base = $prefix . $date . $abbr; // key for counter

                DB::transaction(function () use ($base, $model) {
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
                    $model->kode_bahan = $base . $nextNumber;
                });
            }
        });
    }
}
