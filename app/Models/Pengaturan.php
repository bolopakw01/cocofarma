<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaturan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pengaturans';

    protected $fillable = [
        'nama_pengaturan',
        'nilai',
        'tipe'
    ];

    protected $casts = [
        'nama_pengaturan' => 'string',
        'nilai' => 'string',
        'tipe' => 'string'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Helper to get a setting value by name.
     */
    public static function getValue(string $namaPengaturan, $default = null)
    {
        $row = static::where('nama_pengaturan', $namaPengaturan)->first();
        return $row ? $row->nilai : $default;
    }

    /**
     * Helper to set a setting value by name.
     */
    public static function setValue(string $namaPengaturan, $nilai, string $tipe = 'string')
    {
        return static::updateOrCreate(
            ['nama_pengaturan' => $namaPengaturan],
            ['nilai' => $nilai, 'tipe' => $tipe]
        );
    }

    /**
     * Get all product grades from JSON setting
     */
    public static function getProductGrades()
    {
        $raw = static::where('nama_pengaturan', 'product_grades')->first();
        if ($raw) {
            $decoded = json_decode($raw->nilai, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    /**
     * Get grade label by grade key (A, B, C, etc.)
     * For backward compatibility with existing A/B/C system
     */
    public static function getGradeLabel($gradeKey)
    {
        // First try to get from new dynamic grades system
        $grades = static::getProductGrades();
        foreach ($grades as $grade) {
            // If grade name matches the key (e.g., "Grade A" matches "A")
            if (strtolower($grade['name']) === 'grade ' . strtolower($gradeKey)) {
                return $grade['label'];
            }
        }

        // Fallback to old system for backward compatibility
        $fallbackLabels = [
            'A' => static::getValue('grade_a_label', 'Premium'),
            'B' => static::getValue('grade_b_label', 'Standard'),
            'C' => static::getValue('grade_c_label', 'Below Standard')
        ];

        return $fallbackLabels[$gradeKey] ?? $gradeKey;
    }

    /**
     * Default value set for radar performance metrics.
     */
    public static function defaultPerformanceMetrics(): array
    {
        return [
            [
                'label' => 'Pesanan Bulanan',
                'key' => 'pesanan_bulanan',
                'target' => 120,
                'benchmark' => 100,
                'description' => 'Total pesanan yang diterima selama bulan berjalan.',
            ],
            [
                'label' => 'Produksi Selesai',
                'key' => 'produksi_selesai',
                'target' => 80,
                'benchmark' => 70,
                'description' => 'Jumlah batch produksi yang rampung bulan ini.',
            ],
            [
                'label' => 'Stok Produk',
                'key' => 'stok_produk',
                'target' => 500,
                'benchmark' => 450,
                'description' => 'Total unit stok produk siap jual.',
            ],
            [
                'label' => 'Stok Bahan Baku',
                'key' => 'stok_bahan',
                'target' => 300,
                'benchmark' => 250,
                'description' => 'Total unit stok bahan baku tersedia.',
            ],
            [
                'label' => 'Produk Aktif',
                'key' => 'produk_aktif',
                'target' => 40,
                'benchmark' => 35,
                'description' => 'Jumlah SKU aktif yang masih siap jual.',
            ],
        ];
    }

    /**
     * Options for linking performance indicators with data sources.
     */
    public static function performanceSourceOptions(): array
    {
        return [
            'pesanan_total' => 'Total pesanan bulan ini',
            'produksi_total' => 'Total produksi selesai bulan ini',
            'stok_produk_total' => 'Total unit stok produk',
            'bahan_baku_total' => 'Total unit stok bahan baku',
            'produk_aktif_total' => 'Jumlah produk aktif',
        ];
    }

    /**
     * Decode and sanitize stored performance metrics for reuse in dashboard and settings UI.
     */
    public static function getDashboardPerformanceMetrics(): array
    {
        $defaults = static::defaultPerformanceMetrics();
        $raw = static::where('nama_pengaturan', 'dashboard_performance_metrics')->value('nilai');

        if (empty($raw)) {
            return $defaults;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        if ($decoded === []) {
            return [];
        }

        $sanitized = [];
        foreach ($decoded as $index => $metric) {
            if (!is_array($metric)) {
                continue;
            }

            $fallback = $defaults[$index] ?? static::fallbackPerformanceMetric($index);
            $sanitized[] = static::sanitizePerformanceMetric($metric, $fallback, $index);
        }

        return !empty($sanitized) ? $sanitized : $defaults;
    }

    /**
     * Provide fallback metric when custom entry missing from defaults.
     */
    protected static function fallbackPerformanceMetric(int $index): array
    {
        return [
            'label' => 'Indikator ' . ($index + 1),
            'key' => 'indikator_' . ($index + 1),
            'target' => 0,
            'benchmark' => 0,
            'description' => '',
        ];
    }

    /**
     * Ensure each metric is well-formed and constrained.
     */
    protected static function sanitizePerformanceMetric(array $metric, array $fallback, int $index): array
    {
        $label = trim((string) ($metric['label'] ?? ''));
        $key = trim((string) ($metric['key'] ?? ''));

        if ($label === '') {
            $label = $fallback['label'] ?? 'Indikator ' . ($index + 1);
        }

        if ($key === '') {
            $key = preg_replace('/[^a-z0-9]+/i', '_', strtolower($label));
            if ($key === '' || $key === '_') {
                $key = 'indikator_' . ($index + 1);
            }
        }

        return [
            'label' => $label,
            'key' => $key,
            'target' => static::normalizePerformanceNumber($metric['target'] ?? null, $fallback['target'] ?? 0),
            'benchmark' => static::normalizePerformanceNumber($metric['benchmark'] ?? null, $fallback['benchmark'] ?? 0),
            'description' => trim((string) ($metric['description'] ?? ($fallback['description'] ?? ''))),
        ];
    }

    /**
     * Normalize numeric inputs for target and benchmark.
     */
    protected static function normalizePerformanceNumber($value, $default = 0): float
    {
        if (!is_numeric($value)) {
            $value = $default;
        }

        $value = (float) $value;
        return $value < 0 ? 0.0 : $value;
    }

    // Accessor untuk nilai berdasarkan tipe
    public function getNilaiParsedAttribute()
    {
        switch ($this->tipe) {
            case 'integer':
            case 'int':
                return (int) $this->nilai;
            case 'decimal':
            case 'float':
            case 'double':
                return (float) $this->nilai;
            case 'boolean':
            case 'bool':
                return filter_var($this->nilai, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($this->nilai, true);
            default:
                return $this->nilai;
        }
    }
}
