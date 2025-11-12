@extends('admin.layouts.app')

@php
    $pageTitle = 'Detail Produk';
@endphp

@section('title', 'Detail Produk - Cocofarma')

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
        max-width: 900px;
        margin: 40px auto 60px auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        overflow: hidden;
        min-height: calc(100vh - 200px);
        position: relative;
        animation: fadeInUp 0.6s ease-out;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-header h1 i {
        color: var(--primary);
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    .detail-container {
        animation: slideInLeft 0.5s ease-out 0.2s both;
    }

    .detail-section {
        margin-bottom: 30px;
        animation: slideInLeft 0.5s ease-out 0.3s both;
    }

    .detail-section:nth-child(2) {
        animation-delay: 0.4s;
    }

    .detail-section:nth-child(3) {
        animation-delay: 0.5s;
    }

    .detail-section:nth-child(4) {
        animation-delay: 0.6s;
    }

    .detail-section h3 {
        color: var(--dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-section h3 i {
        color: var(--primary);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-item {
        margin-bottom: 20px;
    }

    .detail-label {
        display: block;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .detail-value {
        padding: 12px 16px;
        background: var(--light);
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        color: var(--dark);
        word-break: break-word;
    }

    .detail-value.status-active {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
        border-color: rgba(76, 201, 240, 0.3);
        font-weight: 600;
    }

    .detail-value.status-inactive {
        background: rgba(230, 57, 70, 0.1);
        color: var(--danger);
        border-color: rgba(230, 57, 70, 0.3);
        font-weight: 600;
    }

    .image-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .product-image {
        max-width: 300px;
        max-height: 300px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: 3px solid var(--light-gray);
        object-fit: cover;
    }

    .no-image {
        width: 200px;
        height: 200px;
        background: var(--light);
        border: 2px dashed var(--light-gray);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        font-size: 1rem;
        margin: 0 auto;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 2px solid var(--light-gray);
        animation: slideInLeft 0.5s ease-out 1s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .container {
            margin: 20px auto 40px auto;
            padding: 20px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .product-image {
            max-width: 250px;
            max-height: 250px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Detail Master Produk</h1>
        <a href="{{ route('backoffice.master-produk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="detail-container">
        <!-- Product Image Section -->
        <div class="detail-section">
            <div class="image-section">
                @if($produk->foto)
                    <img src="{{ asset('bolopa/pokoknyayangadapadasistem/FotoProduk/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="product-image">
                @else
                    <div class="no-image">
                        <i class="fas fa-image" style="font-size: 3rem; color: var(--light-gray); margin-bottom: 10px;"></i>
                        <div>Tidak ada foto produk</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Basic Information -->
        <div class="detail-section">
            <h3><i class="fas fa-info-circle"></i> Informasi Dasar</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Kode Produk</span>
                    <div class="detail-value">{{ $produk->kode_produk }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Nama Produk</span>
                    <div class="detail-value">{{ $produk->nama_produk }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Kategori</span>
                    <div class="detail-value">{{ $produk->kategori }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Satuan</span>
                    <div class="detail-value">{{ $produk->satuan }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Grade</span>
                    <div class="detail-value">{{ $produk->grade_display }}</div>
                </div>
            </div>
        </div>

        <!-- Pricing and Stock Information -->
        <div class="detail-section">
            <h3><i class="fas fa-tags"></i> Harga & Stok</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Harga Jual</span>
                    <div class="detail-value">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Minimum Stok</span>
                    <div class="detail-value">{{ $produk->minimum_stok }} {{ $produk->satuan }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <div class="detail-value status-{{ $produk->status == 'aktif' ? 'active' : 'inactive' }}">
                        {{ $produk->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($produk->deskripsi)
        <div class="detail-section">
            <h3><i class="fas fa-align-left"></i> Deskripsi Produk</h3>
            <div class="detail-item">
                <div class="detail-value" style="white-space: pre-line;">{{ $produk->deskripsi }}</div>
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="detail-section">
            <h3><i class="fas fa-clock"></i> Informasi Sistem</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Dibuat Pada</span>
                    <div class="detail-value">{{ $produk->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Terakhir Diupdate</span>
                    <div class="detail-value">{{ $produk->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('backoffice.master-produk.edit', $produk) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Produk
        </a>
        <a href="{{ route('backoffice.master-produk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection