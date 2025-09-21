@extends('admin.layouts.app')

@php
    $pageTitle = 'Pengaturan';
@endphp

@section('title', 'Pengaturan Sistem - Cocofarma')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --primary-hover: #3a4fd8;
        --success: #4cc9f0;
        --info: #4895ef;
        --warning: #f72585;
        --danger: #e63946;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    html, body {
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
        overflow: hidden;
        margin-top: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.6rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .setting-card {
        background: white;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        padding: 20px;
        transition: var(--transition);
    }

    .setting-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .setting-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .setting-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .setting-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .setting-description {
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary);
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #3aafd9;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #c22c38;
        transform: translateY(-1px);
    }

    .btn-warning {
        background: var(--warning);
        color: white;
    }

    .btn-warning:hover {
        background: #d61c6a;
        transform: translateY(-1px);
    }

    .dashboard-settings {
        background: white;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 20px;
    }

    .dashboard-settings h3 {
        color: var(--dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .alert {
        padding: 12px 16px;
        border-radius: var(--border-radius);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-info {
        background: rgba(73, 149, 239, 0.1);
        border: 1px solid rgba(73, 149, 239, 0.2);
        color: var(--info);
    }

    .alert-success {
        background: rgba(76, 201, 240, 0.1);
        border: 1px solid rgba(76, 201, 240, 0.2);
        color: var(--success);
    }

    .alert-warning {
        background: rgba(247, 37, 133, 0.1);
        border: 1px solid rgba(247, 37, 133, 0.2);
        color: var(--warning);
    }

    @media (max-width: 768px) {
        .container {
            margin: 10px;
            padding: 15px;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Pengaturan Sistem</h1>
    </div>

    <!-- Dashboard Goals Settings -->
    <div class="dashboard-settings">
        <h3><i class="fas fa-chart-line"></i> Target Dashboard</h3>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Target Dashboard</strong> digunakan untuk menampilkan progress pencapaian di halaman dashboard utama.
            </div>
        </div>

        <form action="{{ route('backoffice.pengaturan.save-dashboard-goal') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label for="monthly_sales_goal">Target Penjualan Bulanan (Rp)</label>
                    <input type="number" id="monthly_sales_goal" name="monthly_sales_goal"
                           value="{{ old('monthly_sales_goal', setting('monthly_sales_goal', 50000000)) }}"
                           placeholder="50000000" min="0">
                </div>

                <div class="form-group">
                    <label for="monthly_production_goal">Target Produksi Bulanan (Unit)</label>
                    <input type="number" id="monthly_production_goal" name="monthly_production_goal"
                           value="{{ old('monthly_production_goal', setting('monthly_production_goal', 1000)) }}"
                           placeholder="1000" min="0">
                </div>

                <div class="form-group">
                    <label for="monthly_order_goal">Target Pesanan Bulanan</label>
                    <input type="number" id="monthly_order_goal" name="monthly_order_goal"
                           value="{{ old('monthly_order_goal', setting('monthly_order_goal', 50)) }}"
                           placeholder="50" min="0">
                </div>

                <div class="form-group">
                    <label for="low_stock_threshold">Batas Stok Rendah (%)</label>
                    <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                           value="{{ old('low_stock_threshold', setting('low_stock_threshold', 20)) }}"
                           placeholder="20" min="0" max="100">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Target Dashboard
            </button>
        </form>
    </div>

    <!-- System Settings Grid -->
    <div class="settings-grid">
        <!-- Database Backup -->
        <div class="setting-card">
            <div class="setting-header">
                <div class="setting-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <h4 class="setting-title">Backup Database</h4>
                </div>
            </div>
            <p class="setting-description">
                Buat cadangan database untuk keamanan data. Backup akan disimpan di folder storage/app/backups.
            </p>
            <form action="{{ route('backoffice.pengaturan.backup-database') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-download"></i> Backup Sekarang
                </button>
            </form>
        </div>

        <!-- System Alerts -->
        <div class="setting-card">
            <div class="setting-header">
                <div class="setting-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h4 class="setting-title">Notifikasi Sistem</h4>
                </div>
            </div>
            <p class="setting-description">
                Kelola pengaturan notifikasi untuk alert stok rendah, pesanan baru, dan aktivitas sistem lainnya.
            </p>
            <a href="{{ route('backoffice.pengaturan.alerts') }}" class="btn btn-primary">
                <i class="fas fa-cog"></i> Kelola Notifikasi
            </a>
        </div>

        <!-- System Information -->
        <div class="setting-card">
            <div class="setting-header">
                <div class="setting-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4 class="setting-title">Info Sistem</h4>
                </div>
            </div>
            <p class="setting-description">
                Lihat informasi sistem seperti versi aplikasi, penggunaan storage, dan statistik penggunaan.
            </p>
            <button class="btn btn-info" onclick="showSystemInfo()">
                <i class="fas fa-eye"></i> Lihat Info
            </button>
        </div>

        <!-- Cache Management -->
        <div class="setting-card">
            <div class="setting-header">
                <div class="setting-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h4 class="setting-title">Manajemen Cache</h4>
                </div>
            </div>
            <p class="setting-description">
                Bersihkan cache aplikasi untuk meningkatkan performa dan mengatasi masalah tampilan.
            </p>
            <button class="btn btn-warning" onclick="clearCache()">
                <i class="fas fa-trash"></i> Bersihkan Cache
            </button>
        </div>
    </div>
</div>

<script>
    function showSystemInfo() {
        Swal.fire({
            title: 'Informasi Sistem',
            html: `
                <div style="text-align: left; font-family: 'Segoe UI', sans-serif;">
                    <div style="margin-bottom: 15px;">
                        <strong>Versi Aplikasi:</strong> Cocofarma v1.0.0
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong>Framework:</strong> Laravel 11.x
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong>PHP Version:</strong> 8.2.x
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong>Database:</strong> MySQL 8.0
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong>Status Sistem:</strong>
                        <span style="color: #198754; font-weight: bold;">Aktif</span>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup'
        });
    }

    function clearCache() {
        Swal.fire({
            title: 'Bersihkan Cache',
            text: 'Apakah Anda yakin ingin membersihkan cache aplikasi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f72585',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Ya, Bersihkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate cache clearing
                Swal.fire({
                    title: 'Cache Dibersihkan!',
                    text: 'Cache aplikasi telah berhasil dibersihkan.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Show success message if form was submitted
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Show error message if form had errors
    @if($errors->any())
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menyimpan data.',
            icon: 'error'
        });
    @endif
</script>
@endsection
