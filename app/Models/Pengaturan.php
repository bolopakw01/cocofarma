<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

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
