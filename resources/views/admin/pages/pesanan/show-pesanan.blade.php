@extends('admin.layouts.app')

@php
    $pageTitle = 'Detail Pesanan';
@endphp

@section('title', 'Detail Pesanan - Cocofarma')

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
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        overflow: hidden;
        margin-top: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--gray);
    }

    .detail-container {
        background: var(--light);
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 20px;
    }

    .detail-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--light-gray);
    }

    .detail-section h3 {
        color: var(--dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .detail-value {
        color: var(--gray);
        font-size: 0.95rem;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-diproses {
        background: #cce5ff;
        color: #004085;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
    }

    .status-dibatalkan {
        background: #f8d7da;
        color: #721c24;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .items-table th,
    .items-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--light-gray);
    }

    .items-table th {
        background: var(--light);
        font-weight: 600;
        color: var(--dark);
    }

    .items-table .subtotal {
        font-weight: 600;
        color: var(--success);
        text-align: right;
    }

    .total-section {
        background: var(--light);
        padding: 20px;
        border-radius: var(--border-radius);
        margin-top: 20px;
        text-align: right;
    }

    .total-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success);
        margin: 0;
    }

    .total-label {
        font-size: 0.9rem;
        color: var(--gray);
        margin-bottom: 10px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
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

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #c22c38;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--light-gray);
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .detail-container {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            margin: 10px;
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
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

        .items-table {
            font-size: 0.85rem;
        }

        .items-table th,
        .items-table td {
            padding: 8px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Detail Pesanan - {{ $pesanan->kode_pesanan }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backoffice.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backoffice.pesanan.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item active">Detail Pesanan</li>
            </ol>
        </nav>
    </div>

    <div class="detail-container">
        <div class="detail-section">
            <h3><i class="fas fa-user"></i> Informasi Pesanan</h3>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Kode Pesanan</div>
                    <div class="detail-value">{{ $pesanan->kode_pesanan }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Tanggal Pesanan</div>
                    <div class="detail-value">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge status-{{ $pesanan->status }}">
                            @switch($pesanan->status)
                                @case('pending')
                                    Pending
                                    @break
                                @case('diproses')
                                    Diproses
                                    @break
                                @case('selesai')
                                    Selesai
                                    @break
                                @case('dibatalkan')
                                    Dibatalkan
                                    @break
                                @default
                                    Unknown
                            @endswitch
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Total Harga</div>
                    <div class="detail-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3><i class="fas fa-user"></i> Informasi Pelanggan</h3>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Nama Pelanggan</div>
                    <div class="detail-value">{{ $pesanan->nama_pelanggan }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">No. Telepon</div>
                    <div class="detail-value">{{ $pesanan->no_telepon }}</div>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">{{ $pesanan->alamat }}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h3><i class="fas fa-list"></i> Item Pesanan</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 40%;">Produk</th>
                        <th style="width: 15%;">Jumlah</th>
                        <th style="width: 20%;">Harga Satuan</th>
                        <th style="width: 20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->pesananItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->produk->nama_produk }} ({{ $item->produk->satuan }})</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-label">Total Pesanan</div>
                <div class="total-amount">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('backoffice.pesanan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                <i class="fas fa-exchange-alt"></i> Update Status
            </button>
            <a href="{{ route('backoffice.pesanan.edit', $pesanan->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Pesanan
            </a>
            <form action="{{ route('backoffice.pesanan.destroy', $pesanan->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                    <i class="fas fa-trash"></i> Hapus Pesanan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">
                    <i class="fas fa-exchange-alt"></i> Update Status Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('backoffice.pesanan.update-status', $pesanan->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi Pengelolaan Stok:</strong><br>
                        • <strong>Diproses:</strong> Stok akan dikurangi dan ditahan<br>
                        • <strong>Selesai:</strong> Stok berkurang permanen<br>
                        • <strong>Dibatalkan:</strong> Stok akan dikembalikan
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection