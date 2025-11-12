@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Informasi Produksi';
    $tanggalProduksi = $produksi->tanggal_produksi
        ? \Illuminate\Support\Carbon::parse($produksi->tanggal_produksi)->format('Y-m-d')
        : '';
@endphp

@section('title', 'Edit Informasi Produksi - Cocofarma')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --primary-hover: #3a4fd8;
        --danger: #e63946;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --dark: #212529;
        --border-radius: 10px;
        --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .page-shell {
        max-width: 900px;
        margin: 20px auto;
        padding: 24px;
        background: #ffffff;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        border-bottom: 1px solid var(--light-gray);
        padding-bottom: 16px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.6rem;
        color: var(--dark);
    }

    .page-header span {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 400;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
    }

    .btn-secondary {
        background: #f1f3f5;
        color: var(--dark);
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 18px;
    }

    .info-card {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-size: 0.8rem;
        color: var(--gray);
    }

    .info-value {
        font-size: 1rem;
        color: var(--dark);
        font-weight: 600;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(67, 97, 238, 0.1);
        color: #2747c7;
    }

    .form-section {
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 24px;
    }

    .form-section h2 {
        font-size: 1.1rem;
        margin-bottom: 18px;
        color: var(--dark);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 18px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
    }

    .form-group select,
    .form-group input,
    .form-group textarea {
        border: 1px solid var(--light-gray);
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-group select:focus,
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .error-message {
        color: var(--danger);
        font-size: 0.8rem;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .page-shell {
            padding: 18px;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>

<div class="page-shell">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Informasi Produksi</h1>
        <a href="{{ route('backoffice.produksi.show', $produksi) }}" class="btn btn-secondary">
            <i class="fas fa-stream"></i> Kelola Status
        </a>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <span class="info-label">Nomor Produksi</span>
            <span class="info-value">{{ $produksi->nomor_produksi }}</span>
        </div>
        <div class="info-card">
            <span class="info-label">Batch Produksi</span>
            <span class="info-value">{{ $produksi->batchProduksi->nomor_batch ?? '-' }}</span>
        </div>
        <div class="info-card">
            <span class="info-label">Status Saat Ini</span>
            <span class="status-pill">
                <i class="fas fa-circle"></i> {{ $produksi->status_label }}
            </span>
        </div>
        <div class="info-card">
            <span class="info-label">Jumlah Hasil</span>
            <span class="info-value">
                {{ $produksi->jumlah_hasil == floor($produksi->jumlah_hasil ?? 0)
                    ? number_format($produksi->jumlah_hasil ?? 0, 0)
                    : number_format($produksi->jumlah_hasil ?? 0, 2) }} Unit
            </span>
        </div>
    </div>

    @if($produksi->catatan_produksi)
        <div class="info-grid" style="background: #fff7e6; border: 1px solid #ffe8b5;">
                <div class="info-card">
                    <span class="info-label">Catatan Produksi</span>
                    <span class="info-value">{{ $produksi->catatan_produksi }}</span>
                </div>
        </div>
    @endif

    <form action="{{ route('backoffice.produksi.update', $produksi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="status" value="{{ old('status', $produksi->status) }}">
        <input type="hidden" name="jumlah_hasil" value="{{ old('jumlah_hasil', $produksi->jumlah_hasil) }}">
        <input type="hidden" name="grade_kualitas" value="{{ old('grade_kualitas', $produksi->grade_kualitas) }}">
    <input type="hidden" name="transfer_ke_produk" value="{{ old('transfer_ke_produk', $produksi->status_transfer === 'held' ? 0 : 1) }}">

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 18px;">
                <strong><i class="fas fa-exclamation-triangle"></i> Ada kesalahan:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-section">
            <h2><i class="fas fa-box"></i> Informasi Produk</h2>
            <div class="form-group">
                <label for="produk_id">Produk <span class="text-danger">*</span></label>
                <select name="produk_id" id="produk_id" required>
                    <option value="">Pilih Produk</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ (int) old('produk_id', $produksi->produk_id) === (int) $produk->id ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} (Stok Master: {{ $produk->stok == floor($produk->stok) ? number_format($produk->stok, 0) : number_format($produk->stok, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('produk_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h2><i class="fas fa-calendar-alt"></i> Detail Jadwal</h2>
            <div class="form-group">
                <label for="tanggal_produksi">Tanggal Produksi <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_produksi" id="tanggal_produksi" value="{{ old('tanggal_produksi', $tanggalProduksi) }}" required>
                @error('tanggal_produksi')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="jumlah_target">Target Produksi (Unit) <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_target" id="jumlah_target" min="0.01" step="0.01" value="{{ old('jumlah_target', $produksi->jumlah_target) }}" required>
                @error('jumlah_target')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h2><i class="fas fa-sticky-note"></i> Catatan</h2>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="catatan">Catatan Tambahan</label>
                    <textarea name="catatan_produksi" id="catatan_produksi" rows="4" placeholder="Tambahkan catatan untuk produksi">{{ old('catatan_produksi', $produksi->catatan_produksi) }}</textarea>
                @error('catatan_produksi')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
