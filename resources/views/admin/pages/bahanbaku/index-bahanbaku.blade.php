@extends('admin.layouts.app')

@php
    $pageTitle = 'Bahan Baku';
@endphp

@section('title', 'Bahan Baku - Cocofarma')

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
    
    /* Smaller, muted up/down icons that stack vertically */
    .table th i.sort-up,
    .table th i.sort-down {
        color: rgba(0,0,0,0.35);
        font-size: 0.65rem;
        margin-left: 6px;
    }
    }
    
    html, body {
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
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

    /* Bring the two arrows closer so they visually connect */
    .table th .sort-icons i.sort-up { margin-bottom: -5px; }
    .table th .sort-icons i.sort-down { margin-top: -5px; }

    .table th.active i.sort-up.active-up,
    .table th.active i.sort-down.active-down {
        color: #000 !important;
        font-size: 0.75rem;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
        overflow: hidden;
        
    }
    
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }
    
    h1 {
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
        width: 280px; /* expand on focus to match master-bahan */
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
    
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: 1px solid #6c757d;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
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
    
    th:nth-child(3), td:nth-child(3) {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
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
            padding: 12px;
        }
        
        .container {
            padding: 16px;
        }
        
        header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .controls {
            flex-direction: column;
        }
        
        .left-controls, .right-controls {
            width: 100%;
            flex-wrap: wrap;
        }
        
        .search-box {
            width: 100%;
        }
        
        .search-box input {
            width: 100%;
        }
        
        th, td {
            padding: 6px 8px;
            font-size: 0.8rem;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        table {
            min-width: 800px;
        }
    }
    
    @media (min-width: 1200px) {
        table {
            min-width: 100%;
        }
        
        .table-responsive {
            overflow-x: visible;
        }
        
        .btn-action {
            margin-bottom: 0;
        }
        
        .pagination {
            flex-direction: row;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }
        
        .pagination-info {
            text-align: left;
            font-size: 0.8rem;
            flex: 1;
            max-width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .pagination-buttons {
            justify-content: flex-end;
            flex-shrink: 0;
        }
        
        .pagination-buttons button {
            padding: 5px 8px;
            min-width: 30px;
            font-size: 0.8rem;
        }
    }
    
    @media (min-width: 769px) and (max-width: 1199px) {
        .pagination-info {
            font-size: 0.85rem;
            max-width: 40%;
        }
        
        .pagination-buttons {
            max-width: 60%;
        }
        
        .pagination-buttons button {
            padding: 6px 10px;
            min-width: 32px;
            font-size: 0.85rem;
        }
    }
    }
    
    /* Animation for table rows */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    tbody tr {
        animation: fadeIn 0.3s ease forwards;
    }
    
    tbody tr:nth-child(odd) {
        background-color: #fafbfc;
    }
    
    /* Status indicator */
    .status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .status-active {
        background-color: #2ecc71;
    }
    
    .status-inactive {
        background-color: #e74c3c;
    }
    
    /* Toast notification */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: var(--dark);
        color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 1100;
    }
    
    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .table-responsive {
        position: relative;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

/* SweetAlert Custom Styling untuk Delete Confirmation */
.swal-delete-popup {
    border-radius: var(--border-radius) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
}

.swal-delete-popup .swal2-title {
    color: var(--dark) !important;
    font-weight: 600 !important;
    font-size: 1.4rem !important;
}

.swal-delete-popup .swal2-html-container {
    color: var(--gray) !important;
    font-size: 1rem !important;
    line-height: 1.6 !important;
}

.swal-delete-popup .swal2-icon.swal2-warning {
    border-color: var(--warning) !important;
    color: var(--warning) !important;
}
</style>

<style>
    /* SweetAlert Custom Styles */
    .swal-wide {
        width: 500px !important;
    }

    .swal2-popup .swal2-title {
        font-size: 24px !important;
        color: var(--primary) !important;
    }

    .swal2-popup .swal2-confirm {
        background-color: #e63946 !important;
        border-color: #e63946 !important;
        font-weight: 600 !important;
        padding: 10px 30px !important;
    }

    .swal2-popup .swal2-confirm:hover {
        background-color: #c22c38 !important;
        border-color: #c22c38 !important;
    }

    .swal2-popup .swal2-cancel {
        background-color: #4361ee !important;
        border-color: #4361ee !important;
        font-weight: 600 !important;
        padding: 10px 30px !important;
    }

    .swal2-popup .swal2-cancel:hover {
        background-color: #3a56d4 !important;
        border-color: #3a56d4 !important;
    }

    /* Detail Popup Styles */
    .detail-box {
        max-width: 520px;
        margin: auto;
        text-align: left;
        color: #343a40;
    font-family: inherit;
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #d1f1dc, #b0e7c1);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        color: #198754;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }

    .detail-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: .25rem;
        color: #343a40;
    }

    .detail-sub {
        font-size: .9rem;
        color: #6c757d;
    }

    .status-badge {
        font-weight: 600;
        padding: .4rem .75rem;
        border-radius: 1rem;
        font-size: .8rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: .25rem;
        flex-shrink: 0;
    }

    .status-active {
        background: #198754;
        color: #fff;
    }

    .status-inactive {
        background: #dc3545;
        color: #fff;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .75rem 0;
        border-bottom: 1px solid #e1e5ea;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .label {
        font-size: .9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
    }

    .value {
        font-weight: 600;
        font-size: 1.05rem;
        color: #343a40;
    }

    .stok-highlight {
        font-size: 1.4rem;
        font-weight: 700;
        color: #198754;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .swal-detail-popup {
    font-family: inherit !important;
    }

    .flex-grow-1 {
        flex-grow: 1;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-cogs"></i> Bahan Baku Produksi</h1>
        <a href="{{ route('backoffice.bahanbaku.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Bahan Baku</a>
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
            
            <!-- search moved to right-controls to match master-bahan layout -->
        </div>
        
        <div class="right-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari bahan baku..." value="{{ request('search') }}">
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
                <th data-sort="no" style="width: 6%;">No <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                <th data-sort="kode" style="width: 12%;">Kode Bahan
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="nama" style="width: 20%;">Nama Bahan
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="master" style="width: 20%;">Master Bahan
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="satuan" style="width: 10%;">Satuan
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="stok" style="width: 12%;">Total Stok
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="status" style="width: 10%;">Status
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th style="width: 13%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanBakus ?? [] as $index => $bahan)
            <tr>
                <td>{{ $bahanBakus->firstItem() + $index }}</td>
                <td>{{ $bahan->kode_bahan }}</td>
                <td>{{ $bahan->nama_bahan }}</td>
                <td>{{ $bahan->masterBahan->nama_bahan ?? '-' }}</td>
                <td>{{ $bahan->satuan }}</td>
                <td>{{ number_format($bahan->stok, 2) }}</td>
                <td>
                    <span class="badge {{ $bahan->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                        {{ $bahan->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="actions">
                    <button class="btn btn-info btn-action" title="Lihat" onclick="viewData(event)"><i class="fas fa-eye"></i></button>
                    <a href="{{ route('backoffice.bahanbaku.edit', $bahan->id) }}" class="btn btn-warning btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-action delete-btn" title="Hapus" 
                            data-id="{{ $bahan->id }}" 
                            data-nama="{{ $bahan->nama_bahan }}"
                            data-url="{{ route('backoffice.bahanbaku.destroy', $bahan->id) }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #6c757d;">
                    <i class="fas fa-cubes" style="font-size:3rem; margin-bottom:10px; display:block;"></i>
                    Belum ada data bahan baku
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    @if(isset($bahanBakus) && $bahanBakus->hasPages() && $bahanBakus->lastPage() > 1)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $bahanBakus->firstItem() ?? 0 }} sampai {{ $bahanBakus->lastItem() ?? 0 }} dari {{ $bahanBakus->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            @if($bahanBakus->onFirstPage())
                <button disabled><i class="fas fa-chevron-left"></i></button>
            @else
                <a href="{{ $bahanBakus->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-left"></i></button></a>
            @endif
            
            @foreach($bahanBakus->getUrlRange(1, $bahanBakus->lastPage()) as $page => $url)
                @if($page == $bahanBakus->currentPage())
                    <button class="active">{{ $page }}</button>
                @else
                    <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button>{{ $page }}</button></a>
                @endif
            @endforeach
            
            @if($bahanBakus->hasMorePages())
                <a href="{{ $bahanBakus->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-right"></i></button></a>
            @else
                <button disabled><i class="fas fa-chevron-right"></i></button>
            @endif
        </div>
    </div>
    @elseif(isset($bahanBakus) && $bahanBakus->total() > 0)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $bahanBakus->firstItem() ?? 0 }} sampai {{ $bahanBakus->lastItem() ?? 0 }} dari {{ $bahanBakus->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            <!-- Tombol pagination kosong untuk konsistensi layout -->
        </div>
    </div>
    @elseif(isset($bahanBakus) && $bahanBakus->perPage() >= 1000)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan semua {{ $bahanBakus->total() ?? 0 }} entri
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
    
    // Data untuk sorting
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    // Event Listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterData);
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
    
    // Add event listeners to action buttons
    // Note: Delete buttons now use individual event listeners with SweetAlert2
    // document.querySelectorAll('.btn-danger').forEach(btn => {
    //     btn.addEventListener('click', deleteData);
    // });
    
    // Add event listeners to table headers for sorting
    thElements.forEach(th => {
        th.addEventListener('click', () => {
            const column = th.getAttribute('data-sort');
            sortTable(column);
        });
    });
    
    // Functions
    function showToast(message, type = 'success') {
        if (toast) {
            toast.textContent = message;
            toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }
    
    function filterData() {
        if (!searchInput || !table) return;
        
        const searchText = searchInput.value.toLowerCase();
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].cells.length < 8) continue; // Skip if not data row
            
            const kode = rows[i].cells[1].textContent.toLowerCase();
            const nama = rows[i].cells[2].textContent.toLowerCase();
            const masterBahan = rows[i].cells[3].textContent.toLowerCase();
            
            const matchesSearch = kode.includes(searchText) || 
                                nama.includes(searchText) || 
                                masterBahan.includes(searchText);
            
            if (matchesSearch) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
    
    function changeEntries() {
        if (!entriesSelect) return;
        const entries = entriesSelect.value;

        // Request server with selected per_page (including 'all')
        const url = new URL(window.location);
        url.searchParams.set('per_page', entries === 'all' ? 'all' : entries);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // Toggle clear button visibility
    function toggleClearButton() {
        const input = document.getElementById('searchInput');
        const btn = document.querySelector('.search-box .clear-btn');
        if (!input || !btn) return;
        if (input.value.trim().length > 0) btn.style.display = 'block'; else btn.style.display = 'none';
    }

    function clearSearch() {
        const input = document.getElementById('searchInput');
        if (!input) return;
        input.value = '';
        filterData();
        toggleClearButton();
        input.focus();
    }
    
    function sortTable(column) {
        // NOTE: This client-side sorting is for demo purposes only.
        // In a real application, sorting should be done server-side to maintain
        // consistent numbering across pagination pages.
        
        // Update current sort
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Reset all indicators
        thElements.forEach(th => {
            th.classList.remove('active');
            const up = th.querySelector('i.sort-up');
            const down = th.querySelector('i.sort-down');
            if (up) up.classList.remove('active-up');
            if (down) down.classList.remove('active-down');
        });

        // Set active indicator on the clicked header
        const currentTh = document.querySelector(`th[data-sort="${column}"]`);
        if (currentTh) {
            currentTh.classList.add('active');
            const up = currentTh.querySelector('i.sort-up');
            const down = currentTh.querySelector('i.sort-down');
            if (currentSort.direction === 'asc') {
                if (up) up.classList.add('active-up');
            } else {
                if (down) down.classList.add('active-down');
            }
        }
        
        // Get table rows
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        
        // Sort rows based on column and direction
        rows.sort((a, b) => {
            if (a.cells.length < 8 || b.cells.length < 8) return 0;
            
            let aValue, bValue;
            const colIndex = getColumnIndex(column);
            
            if (column === 'no') {
                aValue = parseInt(a.cells[colIndex].textContent);
                bValue = parseInt(b.cells[colIndex].textContent);
            } else if (column === 'stok') {
                aValue = parseInt(a.cells[colIndex].textContent);
                bValue = parseInt(b.cells[colIndex].textContent);
            } else {
                aValue = a.cells[colIndex].textContent.toLowerCase();
                bValue = b.cells[colIndex].textContent.toLowerCase();
            }
            
            if (aValue < bValue) return currentSort.direction === 'asc' ? -1 : 1;
            if (aValue > bValue) return currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Remove existing rows
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Add sorted rows
        rows.forEach(row => {
            tbody.appendChild(row);
        });
    }
    
    function getColumnIndex(column) {
        const headers = Array.from(table.querySelectorAll('th'));
        return headers.findIndex(header => header.getAttribute('data-sort') === column);
    }
    
    function exportData() {
        showToast('Mempersiapkan data untuk diexport...', 'info');
    }
    
    function printData() {
        showToast('Mempersiapkan data untuk dicetak...', 'info');
    }
    
    function viewData(event) {
        const row = event.target.closest('tr');
        const cells = row.getElementsByTagName('td');

        const kode = cells[1].textContent;
        const nama = cells[2].textContent;
        const masterBahan = cells[3].textContent;
        const satuan = cells[4].textContent;
        const stok = cells[5].textContent.replace(/,/g, '');
        const status = cells[6].textContent;

        const html = `
            <div class="detail-box">
                <div class="detail-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="detail-title">${nama}</div>
                        <div class="detail-sub">Kode: ${kode}</div>
                    </div>
                    <div class="status-badge ${status.includes('Aktif') ? 'status-active' : 'status-inactive'}">
                        <i class="fas ${status.includes('Aktif') ? 'fa-circle-check' : 'fa-circle-xmark'} me-1"></i>${status}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label"><i class="fas fa-cubes me-2"></i>Master Bahan</div>
                    <div class="value">${masterBahan}</div>
                </div>

                <div class="detail-row">
                    <div class="label"><i class="fas fa-cubes me-2"></i>Stok</div>
                    <div class="stok-highlight">
                        ${parseInt(stok).toLocaleString('id-ID')} ${satuan}
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: 'Detail Bahan Baku',
            html: html,
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-times"></i> Tutup',
            confirmButtonColor: 'var(--primary)',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            width: 600,
            background: '#ffffff',
            padding: '1.8rem',
            customClass: {
                popup: 'swal-detail-popup'
            }
        });
    }
    
    // Event listener untuk tombol delete - langsung dan sederhana
    document.addEventListener('click', function(event) {
        if (event.target.closest('.delete-btn')) {
            event.preventDefault();
            const button = event.target.closest('.delete-btn');
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const url = button.getAttribute('data-url');

            if (typeof Swal !== 'undefined') {
                confirmDelete(id, nama, url, button);
            } else {
                console.error('SweetAlert2 not found! Falling back to confirm()');
                if (confirm(`Apakah Anda yakin ingin menghapus bahan baku ${nama}?`)) {
                    submitDeleteForm(url);
                }
            }
        }
    });

    function submitDeleteForm(url) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        // Method spoofing untuk DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    function confirmDelete(id, nama, url, buttonElement) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus bahan baku <strong>${nama}</strong>?<br><br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#4361ee',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Hapus',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
            reverseButtons: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            customClass: {
                popup: 'swal-delete-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitDeleteForm(url);
            }
        });
    }

    // Fungsi deleteData lama sudah tidak digunakan
    // Diganti dengan confirmDelete yang menggunakan SweetAlert2
    // function deleteData(event) {
    //     if (confirm('Apakah Anda yakin ingin menghapus bahan baku produksi ini?')) {
    //         const row = event.target.closest('tr');
    //         row.remove();
    //         showToast('Data berhasil dihapus!');
    //     }
    // }
    
    function resetPagination() {
        // Reset to default pagination (5 entries per page)
        const url = new URL(window.location);
        url.searchParams.set('per_page', '5');
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    }
</script>
@endsection