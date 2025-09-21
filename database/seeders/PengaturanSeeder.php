<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturans = [
            [
                'nama_pengaturan' => 'app_name',
                'nilai' => 'Cocofarma',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'app_version',
                'nilai' => '1.0.0',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'company_name',
                'nilai' => 'PT. Cocofarma Indonesia',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'company_address',
                'nilai' => 'Jl. Industri No. 123, Jakarta',
                'tipe' => 'text'
            ],
            [
                'nama_pengaturan' => 'company_phone',
                'nilai' => '+62 21 1234 5678',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'company_email',
                'nilai' => 'info@cocofarma.com',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'dashboard_goal_production',
                'nilai' => '1000',
                'tipe' => 'number'
            ],
            [
                'nama_pengaturan' => 'dashboard_goal_sales',
                'nilai' => '50000000',
                'tipe' => 'number'
            ],
            [
                'nama_pengaturan' => 'low_stock_threshold',
                'nilai' => '100',
                'tipe' => 'number'
            ],
            [
                'nama_pengaturan' => 'backup_frequency',
                'nilai' => 'daily',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'maintenance_mode',
                'nilai' => 'false',
                'tipe' => 'boolean'
            ],
            [
                'nama_pengaturan' => 'max_login_attempts',
                'nilai' => '5',
                'tipe' => 'number'
            ],
            [
                'nama_pengaturan' => 'session_timeout',
                'nilai' => '7200',
                'tipe' => 'number'
            ],
            [
                'nama_pengaturan' => 'currency',
                'nilai' => 'IDR',
                'tipe' => 'string'
            ],
            [
                'nama_pengaturan' => 'timezone',
                'nilai' => 'Asia/Jakarta',
                'tipe' => 'string'
            ]
        ];

        foreach ($pengaturans as $pengaturan) {
            Pengaturan::create($pengaturan);
        }
    }
}
