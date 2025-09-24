<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tungku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tungkus';

    protected $fillable = [
        'kode_tungku',
        'nama_tungku',
        'kapasitas_max',
        'kapasitas_min',
        'satuan',
        'status',
        'lokasi',
        'tanggal_installasi',
        'catatan'
    ];

    protected $casts = [
        'kapasitas_max' => 'decimal:2',
        'kapasitas_min' => 'decimal:2',
        'tanggal_installasi' => 'date',
        'status' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan BatchProduksi
    public function batchProduksis()
    {
        return $this->hasMany(BatchProduksi::class, 'tungku_id');
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'aktif' => 'Aktif',
            'maintenance' => 'Maintenance',
            'nonaktif' => 'Nonaktif'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor untuk utilisasi tungku
    public function getUtilisasiRataRataAttribute()
    {
        $totalBatch = $this->batchProduksis()->where('status', 'selesai')->count();
        if ($totalBatch == 0) return 0;

        $totalKapasitas = $this->batchProduksis()->where('status', 'selesai')->sum('total_biaya_bahan');
        $rataRataKapasitas = $totalKapasitas / $totalBatch;

        return ($rataRataKapasitas / $this->kapasitas_max) * 100;
    }

    // Scope untuk tungku aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Method untuk cek ketersediaan tungku
    public function isTersedia($tanggal = null)
    {
        $tanggal = $tanggal ?? now()->toDateString();

        $batchAktif = $this->batchProduksis()
                          ->where('tanggal_produksi', $tanggal)
                          ->whereIn('status', ['rencana', 'proses'])
                          ->exists();

        return !$batchAktif && $this->status === 'aktif';
    }
}
