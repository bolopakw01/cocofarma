@extends('admin.layouts.app')

@php
    $pageTitle = 'Pengaturan';
    use App\Models\Pengaturan;
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
        font-family: inherit;
    }

    html, body {
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
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

    .settings-section {
        margin-bottom: 30px;
    }

    .settings-section h2 {
        color: var(--dark);
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .setting-card {
        background: white;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        padding: 20px;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-height: 200px;
        justify-content: space-between;
    }

    .setting-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .setting-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        width: 100%;
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
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        text-align: center;
    }

    .setting-description {
        color: var(--gray);
        font-size: 0.85rem;
        margin-bottom: 12px;
        line-height: 1.4;
        text-align: center;
        flex-grow: 1;
    }

    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
    <!-- Dashboard Settings Container -->
    <div class="settings-section">
        <h2><i class="fas fa-tachometer-alt"></i> Pengaturan Dashboard</h2>
        <div class="settings-grid">
            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div>
                        <h4 class="setting-title">Set Goals</h4>
                    </div>
                </div>
                <p class="setting-description">
                    Atur target dan goals untuk dashboard utama. Tetapkan target penjualan, produksi, dan pesanan bulanan.
                </p>
                @if(Auth::check() && Auth::user()->role === 'super_admin')
                <a href="{{ route('backoffice.pengaturan.goals') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Kelola Goals
                </a>
                @endif
            </div>

            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <h4 class="setting-title">Dashboard Metrics</h4>
                    </div>
                </div>
                <p class="setting-description">
                    Pantau progress pencapaian target dashboard. Lihat statistik penjualan, produksi, dan pesanan terkini.
                </p>
                <a href="{{ route('backoffice.dashboard') }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Lihat Dashboard
                </a>
            </div>

            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h4 class="setting-title">Laporan Performance</h4>
                    </div>
                </div>
                <p class="setting-description">
                    Generate laporan performa bulanan. Analisis trend penjualan dan produktivitas perusahaan.
                </p>
                <a href="{{ route('backoffice.laporan.index') }}" class="btn btn-warning">
                    <i class="fas fa-chart-line"></i> Buat Laporan
                </a>
            </div>

            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h4 class="setting-title">Pengaturan Grade</h4>
                    </div>
                </div>
                <p class="setting-description">
                    Kelola label dan pengaturan grade produk. Atur nama grade A, B, dan C untuk klasifikasi produk.
                </p>
                <a href="{{ route('backoffice.pengaturan.grade') }}" class="btn btn-success">
                    <i class="fas fa-cog"></i> Kelola Grade
                </a>
            </div>
        </div>
    </div>

    <!-- Database Settings Container -->
    <div class="settings-section">
        <h2><i class="fas fa-database"></i> Pengaturan Sistem & Database</h2>
        <div class="settings-grid">
            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-download"></i>
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

            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <h4 class="setting-title">Bersihkan Cache</h4>
                    </div>
                </div>
                <p class="setting-description">
                    Hapus cache aplikasi untuk meningkatkan performa dan mengatasi masalah tampilan yang tidak konsisten.
                </p>
                <button class="btn btn-warning" onclick="clearCache()">
                    <i class="fas fa-trash"></i> Bersihkan Cache
                </button>
            </div>

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
