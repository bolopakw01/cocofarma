<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produksi extends Model
{
    use SoftDeletes;
    protected $table = 'produksis';

    protected $fillable = [
        'nomor_produksi',
        'batch_produksi_id',
        'produk_id',
        'tanggal_produksi',
        'jumlah_target',
        'jumlah_hasil',
        'grade_kualitas',
        'biaya_produksi',
        'status',
        'status_transfer',
        'tanggal_transfer',
    'catatan',
    'catatan_produksi',
        'user_id'
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'produk_id' => 'integer',
        'batch_produksi_id' => 'integer',
        'jumlah_target' => 'decimal:2',
        'jumlah_hasil' => 'decimal:2',
        'biaya_produksi' => 'decimal:2',
        'status' => 'string',
        'status_transfer' => 'string',
        'tanggal_transfer' => 'datetime',
        'catatan_produksi' => 'string'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Relasi dengan ProduksiBahan
    public function produksiBahans()
    {
        return $this->hasMany(ProduksiBahan::class, 'produksi_id');
    }

    // Relasi dengan BatchProduksi
    public function batchProduksi()
    {
        return $this->belongsTo(BatchProduksi::class, 'batch_produksi_id');
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan StokProduk
    public function stokProduk()
    {
        return $this->hasOne(StokProduk::class, 'batch_produksi_id', 'batch_produksi_id')
                    ->where('produk_id', $this->produk_id);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'rencana' => 'Rencana',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal'
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusTransferLabelAttribute()
    {
        $labels = [
            'pending' => 'Belum Dipindahkan',
            'held' => 'Ditahan',
            'transferred' => 'Sudah Dipindahkan',
        ];

        $current = $this->status_transfer ?? 'pending';

        return $labels[$current] ?? ucfirst($current);
    }

    // Accessor untuk grade label
    public function getGradeLabelAttribute()
    {
        if (!$this->grade_kualitas) {
            return null;
        }

        try {
            $grades = Pengaturan::getProductGrades();
            if (empty($grades)) {
                // Fallback to default grades
                $defaultGrades = [
                    'A' => 'Premium',
                    'B' => 'Standard',
                    'C' => 'Below Standard'
                ];
                return $defaultGrades[$this->grade_kualitas] ?? $this->grade_kualitas;
            }

            // Find the grade by index (A=0, B=1, C=2, etc.)
            $gradeIndex = ord($this->grade_kualitas) - 65; // Convert A to 0, B to 1, etc.
            if (isset($grades[$gradeIndex])) {
                return $grades[$gradeIndex]['label'];
            }

            return $this->grade_kualitas;
        } catch (\Exception $e) {
            // Emergency fallback
            $defaultGrades = [
                'A' => 'Premium',
                'B' => 'Standard',
                'C' => 'Below Standard'
            ];
            return $defaultGrades[$this->grade_kualitas] ?? $this->grade_kualitas;
        }
    }

    // Accessor untuk grade display (format lengkap: "Grade A (Premium Quality)")
    public function getGradeDisplayAttribute()
    {
        if (!$this->grade_kualitas) {
            return '-';
        }

        try {
            $grades = Pengaturan::getProductGrades();
            if (empty($grades)) {
                // If no grades configured at all, return '-'
                return '-';
            }

            // Find the grade by index (A=0, B=1, C=2, etc.)
            $gradeIndex = ord($this->grade_kualitas) - 65; // Convert A to 0, B to 1, etc.
            if (isset($grades[$gradeIndex])) {
                $gradeName = $grades[$gradeIndex]['name'];
                $gradeLabel = $grades[$gradeIndex]['label'];
                return "{$gradeName} ({$gradeLabel})";
            }

            return $this->grade_kualitas;
        } catch (\Exception $e) {
            // Emergency fallback - return '-' instead of default grades
            return '-';
        }
    }

    // Scope untuk produksi aktif
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
