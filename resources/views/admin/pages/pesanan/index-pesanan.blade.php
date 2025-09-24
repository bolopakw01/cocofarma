@extends('admin.layouts.app')

@php
    $pageTitle = 'Pesanan';
@endphp

@section('title', 'Pesanan - Cocofarma')

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
        max-width: 1200px;
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

    .controls {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        padding: 16px;
        background: var(--light);
        border-radius: var(--border-radius);
    }

    .left-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .right-controls {
        display: flex;
        gap: 10px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        width: 250px;
        transition: var(--transition);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        width: 280px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .entries-select {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .entries-select select {
        padding: 8px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        background: white;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
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

    .btn-action {
        padding: 5px 10px;
        font-size: 0.8rem;
        margin: 0 2px;
    }

    .btn-info {
        background: var(--info);
        color: white;
    }

    .btn-info:hover {
        background: #3a7fd8;
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

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
        position: relative;
        table-layout: fixed;
        min-width: 100%;
        max-width: none;
    }

    th, td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid var(--light-gray);
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 0;
    }

    th {
        background-color: var(--light);
        font-weight: 600;
        color: var(--dark);
        position: sticky;
        top: 0;
        z-index: 5;
        cursor: pointer;
        user-select: none;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    th:hover {
        background-color: #e9ecef;
    }

    th i {
        margin-left: 5px;
        font-size: 0.8rem;
        opacity: 0.6;
    }

    th.active i {
        opacity: 1;
    }

    /* Sort icons style (stacked up/down arrows) - match operational pages */
    .table th i.sort-up,
    .table th i.sort-down {
        color: rgba(0,0,0,0.35);
        font-size: 0.65rem;
        margin-left: 6px;
    }

    .sort-icons {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-left: 8px;
        vertical-align: middle;
        line-height: 1;
    }

    .table th .sort-icons i { margin: 0; padding: 0; height: 12px; }
    .table th .sort-icons i.sort-up { margin-bottom: -5px; }
    .table th .sort-icons i.sort-down { margin-top: -5px; }

    .table th.active i.sort-up.active-up,
    .table th.active i.sort-down.active-down {
        color: #000 !important;
        font-size: 0.75rem;
    }

    tr {
        transition: var(--transition);
    }

    tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 5px;
        min-width: 120px;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-info {
        color: var(--gray);
        font-size: 0.9rem;
        flex-shrink: 0;
        white-space: nowrap;
        max-width: 30%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pagination-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: flex-end;
        flex-shrink: 0;
        max-width: 70%;
    }

    .pagination-buttons button {
        padding: 6px 10px;
        border: 1px solid var(--light-gray);
        background: white;
        border-radius: var(--border-radius);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .pagination-buttons button:hover {
        background: var(--light);
    }

    .pagination-buttons button.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination-buttons button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(3px);
    }

    .modal-content {
        background: white;
        padding: 28px;
        border-radius: var(--border-radius);
        width: 500px;
        max-width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal.show .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--light-gray);
    }

    .modal-header h2 {
        font-size: 1.4rem;
        color: var(--dark);
    }

    .close {
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray);
        transition: var(--transition);
    }

    .close:hover {
        color: var(--dark);
        transform: rotate(90deg);
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

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--light-gray);
    }

    @media (max-width: 768px) {
        body {
            padding: 10px;
        }

        .container {
            margin: 10px;
            padding: 15px;
        }

        header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .controls {
            flex-direction: column;
            align-items: stretch;
        }

        .left-controls, .right-controls {
            justify-content: center;
        }

        .search-box input {
            width: 200px;
        }

        .search-box input:focus {
            width: 220px;
        }

        th, td {
            padding: 6px 8px;
            font-size: 0.85rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 600px;
        }

        .btn-action {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .actions {
            gap: 3px;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .pagination-info {
            max-width: 100%;
            text-align: center;
        }

        .pagination-buttons {
            max-width: 100%;
            justify-content: center;
        }

        .pagination-buttons button {
            min-width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
    }

    @media (min-width: 1200px) {
        table {
            table-layout: auto;
        }

        .table-responsive {
            overflow-x: visible;
        }

        .btn-action {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .actions {
            gap: 8px;
        }

        .pagination {
            justify-content: space-between;
        }

        .pagination-info {
            max-width: 30%;
        }

        .pagination-buttons {
            max-width: 70%;
        }

        .pagination-buttons button {
            min-width: 36px;
            height: 36px;
            font-size: 0.95rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1199px) {
        .pagination-info {
            max-width: 40%;
        }

        .pagination-buttons {
            max-width: 60%;
        }

        .pagination-buttons button {
            min-width: 32px;
            height: 32px;
        }

        .search-box input {
            width: 220px;
        }

        .search-box input:focus {
            width: 240px;
        }
    }

    /* Animation for table rows */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    tbody tr {
        animation: fadeIn 0.3s ease-out;
    }

    tbody tr:nth-child(odd) {
        animation-delay: 0.1s;
    }

    /* Status indicator */
    .status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-pending {
        background: #ffc107;
        box-shadow: 0 0 6px rgba(255, 193, 7, 0.4);
    }

    .status-diproses {
        background: #17a2b8;
        box-shadow: 0 0 6px rgba(23, 162, 184, 0.4);
    }

    .status-selesai {
        background: #28a745;
        box-shadow: 0 0 6px rgba(40, 167, 69, 0.4);
    }

    .status-dibatalkan {
        background: #dc3545;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.4);
    }

    /* Toast notification */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: #28a745;
        color: white;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        z-index: 9999;
        max-width: 400px;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast.error {
        background: #dc3545;
    }

    .table-responsive {
        position: relative;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .order-code {
        font-weight: 600;
        color: var(--primary);
    }

    .customer-info {
        max-width: 200px;
    }

    .customer-info .name {
        font-weight: 600;
        color: var(--dark);
    }

    .customer-info .phone {
        font-size: 0.8rem;
        color: var(--gray);
    }

    .order-total {
        font-weight: 600;
        color: var(--success);
    }

    .order-date {
        font-size: 0.85rem;
        color: var(--gray);
    }

    /* Toast notifications */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        max-width: 400px;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
    }

    .toast.error {
        background: #dc3545;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-shopping-cart"></i> Pesanan</h1>
        <a href="{{ route('backoffice.pesanan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pesanan</a>
    </div>

    <div class="controls">
        <div class="left-controls">
            <div class="entries-select">
                <label for="entriesSelect">Tampilkan</label>
                <select id="entriesSelect">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
                <span>entri</span>
            </div>
        </div>

        <div class="right-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari pesanan..." value="{{ request('search') }}">
                <button type="button" class="clear-btn" onclick="clearSearch()" style="display:none;"><i class="fas fa-times"></i></button>
            </div>

            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="btn btn-success" id="btnExport"><i class="fas fa-file-export"></i> Export</button>
            @endif
            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="btn btn-primary" id="btnPrint"><i class="fas fa-print"></i> Print</button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
    <table class="table" id="dataTable">
        <thead>
            <tr>
                <th data-sort="no" style="width: 5%;">No <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="kode_pesanan" style="width: 12%;">Kode Pesanan <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="tanggal_pesanan" style="width: 10%;">Tanggal <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="nama_pelanggan" style="width: 18%;">Pelanggan <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="total_harga" style="width: 12%;">Total <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="status" style="width: 10%;">Status <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans ?? [] as $index => $pesanan)
            <tr>
                <td>{{ ($pesanans->currentPage() - 1) * $pesanans->perPage() + $index + 1 }}</td>
                <td>
                    <div class="order-code">{{ $pesanan->kode_pesanan }}</div>
                </td>
                <td>
                    <div class="order-date">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="customer-info">
                        <div class="name">{{ $pesanan->nama_pelanggan }}</div>
                        <div class="phone">{{ $pesanan->no_telepon }}</div>
                    </div>
                </td>
                <td>
                    <div class="order-total">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                </td>
                <td>
                    <span class="badge {{ $pesanan->status == 'pending' ? 'badge-warning' : ($pesanan->status == 'diproses' ? 'badge-info' : ($pesanan->status == 'selesai' ? 'badge-success' : 'badge-danger')) }}">
                        {{ $pesanan->status_label }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('backoffice.pesanan.show', $pesanan->id) }}" class="btn btn-info btn-action" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button class="btn btn-success btn-action" onclick="openStatusModal({{ $pesanan->id }}, '{{ $pesanan->status }}', '{{ $pesanan->kode_pesanan }}')" title="Update Status">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                    <a href="{{ route('backoffice.pesanan.edit', $pesanan->id) }}" class="btn btn-warning btn-action" title="Edit Pesanan">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-action" onclick="confirmDelete({{ $pesanan->id }}, '{{ $pesanan->kode_pesanan }}', '{{ route('backoffice.pesanan.destroy', $pesanan->id) }}', this)" title="Hapus Pesanan">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #6c757d; margin-bottom: 10px;"></i>
                    <br>
                    Tidak ada data pesanan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if(isset($pesanans) && $pesanans->hasPages() && $pesanans->lastPage() > 1)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $pesanans->firstItem() ?? 0 }} sampai {{ $pesanans->lastItem() ?? 0 }} dari {{ $pesanans->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            @if($pesanans->onFirstPage())
                <button disabled><i class="fas fa-chevron-left"></i></button>
            @else
                <a href="{{ $pesanans->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-left"></i></button></a>
            @endif

            @foreach($pesanans->getUrlRange(1, $pesanans->lastPage()) as $page => $url)
                @if($page == $pesanans->currentPage())
                    <button class="active">{{ $page }}</button>
                @else
                    <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button>{{ $page }}</button></a>
                @endif
            @endforeach

            @if($pesanans->hasMorePages())
                <a href="{{ $pesanans->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-right"></i></button></a>
            @else
                <button disabled><i class="fas fa-chevron-right"></i></button>
            @endif
        </div>
    </div>
    @elseif(isset($pesanans) && $pesanans->total() > 0)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $pesanans->firstItem() ?? 0 }} sampai {{ $pesanans->lastItem() ?? 0 }} dari {{ $pesanans->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            <!-- Tombol pagination kosong untuk konsistensi layout -->
        </div>
    </div>
    @elseif(isset($pesanans) && $pesanans->perPage() >= 1000)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan semua {{ $pesanans->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            <button class="btn btn-secondary" onclick="resetPagination()">Kembali ke Pagination</button>
        </div>
    </div>
    @endif
</div>

<!-- Toast Notification -->
<div class="toast" id="toast"></div>

<script>
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entriesSelect');
    const btnExport = document.getElementById('btnExport');
    const btnPrint = document.getElementById('btnPrint');
    const table = document.getElementById('dataTable');
    const thElements = document.querySelectorAll('th[data-sort]');
    const toast = document.getElementById('toast');

    // Override default session message handling to use toast instead of SweetAlert
    // Run immediately to prevent layout SweetAlert from executing
    (function() {
        // Store original Swal for allowed dialogs
        let originalSwal = null;
        if (typeof Swal !== 'undefined') {
            originalSwal = window.Swal;
        }

        // Define allowed dialogs
        const allowedTitles = ['Detail Pesanan', 'Hapus Pesanan'];

        // Create showDetail function that uses original Swal
        window.showDetail = function(id, kode, nama, tanggal, status, total, alamat, telepon) {
            if (originalSwal) {
                originalSwal.fire({
                    title: 'Detail Pesanan',
                    html: `
                        <div class="detail-box">
                            <div class="detail-header">
                                <div class="icon-wrapper">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    <div class="detail-title">${kode}</div>
                                    <div class="detail-sub">${nama} - ${tanggal}</div>
                                </div>
                            </div>
                            <div class="detail-content">
                                <div class="detail-item">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">${status}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Harga</div>
                                    <div class="detail-value">Rp ${total.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Alamat</div>
                                    <div class="detail-value">${alamat}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Telepon</div>
                                    <div class="detail-value">${telepon}</div>
                                </div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-detail-popup'
                    }
                });
            }
        };

        // Create confirmDelete function that uses original Swal
        window.confirmDelete = function(id, kode, url, buttonElement) {
            if (originalSwal) {
                originalSwal.fire({
                    title: 'Hapus Pesanan',
                    html: `Apakah Anda yakin ingin menghapus pesanan <strong>${kode}</strong>?<br><small style="color: #6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e63946',
                    cancelButtonColor: '#4361ee',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'swal-delete-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitDeleteForm(url);
                    }
                });
            }
        };

        // Completely disable SweetAlert for everything else
        window.Swal = null;
        delete window.Swal;

        // Handle success messages with toast instead of SweetAlert
        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        // Handle error messages with toast instead of SweetAlert
        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Handle info messages with toast instead of SweetAlert
        @if (session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
    })();

    // Data untuk sorting
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    // Event Listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterData);
        searchInput.addEventListener('input', toggleClearButton);
    }
    if (entriesSelect) {
        entriesSelect.addEventListener('change', changeEntries);
    }
    if (btnExport) {
        btnExport.addEventListener('click', exportData);
    }
    if (btnPrint) {
        btnPrint.addEventListener('click', printData);
    }

    // Add event listeners to table headers for sorting
    thElements.forEach(th => {
        th.addEventListener('click', () => sortTable(th.getAttribute('data-sort')));
    });

    // Functions
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast ${type} show`;

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function filterData() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });

        resetPagination();
    }

    function changeEntries() {
        const value = entriesSelect.value;
        const url = new URL(window.location);

        if (value === 'all') {
            url.searchParams.set('per_page', '1000');
        } else {
            url.searchParams.set('per_page', value);
        }

        window.location.href = url.toString();
    }

    // Toggle clear button visibility
    function toggleClearButton() {
        const clearBtn = document.querySelector('.clear-btn');
        if (searchInput.value.length > 0) {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }
    }

    function clearSearch() {
        searchInput.value = '';
        filterData();
        toggleClearButton();
    }

    function sortTable(column) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Update sort direction
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Sort rows
        rows.sort((a, b) => {
            const aValue = a.children[getColumnIndex(column)].textContent.trim();
            const bValue = b.children[getColumnIndex(column)].textContent.trim();

            let comparison = 0;
            if (aValue < bValue) comparison = -1;
            if (aValue > bValue) comparison = 1;

            return currentSort.direction === 'asc' ? comparison : -comparison;
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));

        // Update sort indicators
        updateSortIndicators();

        resetPagination();
    }

    function getColumnIndex(column) {
        const headers = table.querySelectorAll('th[data-sort]');
        for (let i = 0; i < headers.length; i++) {
            if (headers[i].getAttribute('data-sort') === column) {
                return i;
            }
        }
        return 0;
    }

    function updateSortIndicators() {
        // Reset all indicators
        thElements.forEach(th => {
            const icons = th.querySelectorAll('.sort-up, .sort-down');
            icons.forEach(icon => {
                icon.classList.remove('active-up', 'active-down');
            });
            th.classList.remove('active');
        });

        // Set active indicator
        if (currentSort.column) {
            const activeTh = table.querySelector(`th[data-sort="${currentSort.column}"]`);
            if (activeTh) {
                activeTh.classList.add('active');
                const iconClass = currentSort.direction === 'asc' ? 'active-up' : 'active-down';
                const icon = activeTh.querySelector(`.${iconClass.replace('active-', 'sort-')}`);
                if (icon) {
                    icon.classList.add(iconClass);
                }
            }
        }
    }

    function exportData() {
        showToast('Fitur export akan segera hadir!', 'info');
    }

    function printData() {
        showToast('Fitur print akan segera hadir!', 'info');
    }

    function submitDeleteForm(url) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    function resetPagination() {
        // Reset to first page when filtering/sorting
        const url = new URL(window.location);
        url.searchParams.delete('page');
        window.history.replaceState({}, '', url);
    }

    // Modal functions
    function openStatusModal(pesananId, currentStatus, kodePesanan) {
        // Set modal title
        document.getElementById('statusModalLabel').innerHTML = `<i class="fas fa-exchange-alt me-2"></i>Update Status Pesanan - ${kodePesanan}`;

        // Set form action with correct relative URL for subdirectory compatibility
        const currentPath = window.location.pathname;
        const basePath = currentPath.substring(0, currentPath.indexOf('/backoffice'));
        const form = document.getElementById('statusForm');
        form.action = `${basePath}/backoffice/pesanan/${pesananId}/status`;

        // Set current status in select
        const statusSelect = document.getElementById('modalStatus');
        statusSelect.value = currentStatus;

        // Disable current status option
        const options = statusSelect.querySelectorAll('option');
        options.forEach(option => {
            option.disabled = false;
            if (option.value === currentStatus) {
                option.disabled = true;
                option.textContent = option.textContent + ' (Status Saat Ini)';
            } else {
                option.textContent = option.textContent.replace(' (Status Saat Ini)', '');
            }
        });

        // Show modal using Bootstrap 5
        const modal = new bootstrap.Modal(document.getElementById('statusModal'), {
            backdrop: false
        });
        modal.show();
    }

    // Handle status form submission - moved to DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        const statusForm = document.getElementById('statusForm');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const selectedStatus = formData.get('status');

                // Debug: Log all form data entries
                console.log('Form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                // Debug: Check form elements directly
                const statusSelect = this.querySelector('select[name="status"]');
                console.log('Status select element:', statusSelect);
                console.log('Status select value:', statusSelect ? statusSelect.value : 'NOT FOUND');

                // Show loading
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

                console.log('Submitting form to:', this.action);
                console.log('Form data object:', formData);
                console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);

                    if (data.success) {
                        // Close modal using Bootstrap 5
                        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                        modal.hide();

                        // Show success message
                        showToast(data.message || 'Status berhasil diupdate', 'success');

                        // Reload page after delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                    if (modal) {
                        modal.hide();
                    }

                    // Show error message
                    showToast('Terjadi kesalahan saat mengupdate status: ' + error.message, 'error');
                })
                .finally(() => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    });
</script>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>Update Status Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalStatus" class="form-label fw-bold">Status Pesanan</label>
                        <select class="form-select" id="modalStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pilih status baru untuk pesanan ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

