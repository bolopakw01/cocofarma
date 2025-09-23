@extends('admin.layouts.app')

@php
    $pageTitle = 'Detail Produksi';
@endphp

@section('title', 'Detail Produksi - Cocofarma')

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

    .info-section {
        background: var(--light);
        border-radius: var(--border-radius);
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .info-section h3 {
        color: var(--dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-weight: 500;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-rencana { background: var(--warning); color: white; }
    .status-proses { background: var(--info); color: white; }
    .status-selesai { background: var(--success); color: white; }
    .status-gagal { background: var(--danger); color: white; }

    .table-responsive {
        margin-top: 20px;
    }

    .table th {
        background: var(--primary);
        color: white;
        border: none;
        font-weight: 600;
    }

    .table td {
        border-color: var(--light-gray);
    }

    .btn {
        border-radius: var(--border-radius);
        padding: 8px 16px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #3d9bb5;
        transform: translateY(-1px);
    }

    .btn-warning {
        background: var(--warning);
        color: white;
    }

    .btn-warning:hover {
        background: #d63384;
        transform: translateY(-1px);
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Detail Produksi</h1>
        <div>
            <a href="{{ route('backoffice.produksi.edit', $produksi->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Informasi Produksi -->
    <div class="info-section">
        <h3><i class="fas fa-info-circle"></i> Informasi Produksi</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nomor Produksi</span>
                <span class="info-value">{{ $produksi->nomor_produksi }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Produk</span>
                <span class="info-value">{{ $produksi->produk->nama_produk ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Produksi</span>
                <span class="info-value">{{ $produksi->tanggal_produksi->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="status-badge status-{{ $produksi->status }}">{{ ucfirst($produksi->status) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Target Produksi</span>
                <span class="info-value">{{ number_format($produksi->jumlah_target, 0) }} Unit</span>
            </div>
            @if($produksi->jumlah_hasil)
            <div class="info-item">
                <span class="info-label">Jumlah Hasil</span>
                <span class="info-value">{{ number_format($produksi->jumlah_hasil, 0) }} Unit</span>
            </div>
            @endif
            @if($produksi->grade_kualitas)
            <div class="info-item">
                <span class="info-label">Grade Kualitas</span>
                <span class="info-value">{{ $produksi->grade_kualitas }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">Biaya Produksi</span>
                <span class="info-value">Rp {{ number_format($produksi->biaya_produksi, 0, ',', '.') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Dibuat Oleh</span>
                <span class="info-value">{{ $produksi->user->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Dibuat</span>
                <span class="info-value">{{ $produksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Bahan Baku Yang Digunakan -->
    <div class="info-section">
        <h3><i class="fas fa-boxes"></i> Bahan Baku Yang Digunakan</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nama Bahan Baku</th>
                        <th>Jumlah Digunakan</th>
                        <th>Harga Satuan</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksi->produksiBahans as $bahan)
                    <tr>
                        <td>{{ $bahan->bahanBaku->nama_bahan ?? 'N/A' }}</td>
                        <td>{{ number_format($bahan->jumlah_digunakan, 2) }} {{ $bahan->bahanBaku->satuan ?? '' }}</td>
                        <td>Rp {{ number_format($bahan->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($bahan->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data bahan baku</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($produksi->produksiBahans->count() > 0)
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Biaya:</th>
                        <th>Rp {{ number_format($produksi->produksiBahans->sum('total_biaya'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if($produksi->catatan)
    <!-- Catatan -->
    <div class="info-section">
        <h3><i class="fas fa-sticky-note"></i> Catatan</h3>
        <p>{{ $produksi->catatan }}</p>
    </div>
    @endif
</div>

@endsection