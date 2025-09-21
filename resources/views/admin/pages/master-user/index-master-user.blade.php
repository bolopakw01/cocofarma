@extends('admin.layouts.app')

@php
    $pageTitle = 'Master User';
@endphp

@section('title', 'Master User - Cocofarma')

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

    .status-active {
        background: #28a745;
        box-shadow: 0 0 6px rgba(40, 167, 69, 0.4);
    }

    .status-inactive {
        background: #dc3545;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.4);
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
        font-family: "Segoe UI", Roboto, sans-serif;
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

    .detail-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .detail-label {
        font-size: .9rem;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .detail-value {
        font-size: 1rem;
        color: #343a40;
        font-weight: 500;
    }

    .stok-highlight {
        font-size: 1.2rem;
        font-weight: 700;
        color: #198754;
    }

    .stok-low {
        color: #dc3545 !important;
    }

    .stok-medium {
        color: #ffc107 !important;
    }

    .stok-high {
        color: #198754 !important;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Master User</h1>
        <a href="{{ route('backoffice.master-user.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
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
                <input type="text" id="searchInput" placeholder="Cari user..." value="{{ request('search') }}">
                <button type="button" class="clear-btn" onclick="clearSearch()" style="display:none;"><i class="fas fa-times"></i></button>
            </div>

            <button class="btn btn-success" id="btnExport"><i class="fas fa-file-export"></i> Export</button>
            <button class="btn btn-primary" id="btnPrint"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table" id="dataTable">
        <thead>
            <tr>
                <th data-sort="no" style="width: 6%;">No
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="nama" style="width: 20%;">Nama
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="username" style="width: 15%;">Username
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="email" style="width: 20%;">Email
                    <span class="sort-icons">
                        <i class="fas fa-sort-up sort-up"></i>
                        <i class="fas fa-sort-down sort-down"></i>
                    </span>
                </th>
                <th data-sort="role" style="width: 15%;">Role
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
                <th style="width: 14%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $index => $user)
            <tr>
                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role == 'super_admin' ? 'badge-success' : 'badge-danger' }}">
                        {{ $user->role == 'super_admin' ? 'Super Admin' : 'Admin' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }}">
                        {{ $user->status ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </td>
                <td class="actions">
                    <button class="btn btn-info btn-action" onclick="showDetail({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}', {{ $user->status ? 'true' : 'false' }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="{{ route('backoffice.master-user.edit', $user->id) }}" class="btn btn-warning btn-action">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-action" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}', '{{ route('backoffice.master-user.destroy', $user->id) }}', this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <i class="fas fa-users" style="font-size: 3rem; color: #6c757d; margin-bottom: 10px;"></i>
                    <br>
                    Tidak ada data user
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if(isset($users) && $users->hasPages() && $users->lastPage() > 1)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            @if($users->onFirstPage())
                <button disabled><i class="fas fa-chevron-left"></i></button>
            @else
                <a href="{{ $users->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-left"></i></button></a>
            @endif

            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if($page == $users->currentPage())
                    <button class="active">{{ $page }}</button>
                @else
                    <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button>{{ $page }}</button></a>
                @endif
            @endforeach

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}"><button><i class="fas fa-chevron-right"></i></button></a>
            @else
                <button disabled><i class="fas fa-chevron-right"></i></button>
            @endif
        </div>
    </div>
    @elseif(isset($users) && $users->total() > 0)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() ?? 0 }} entri
        </div>
        <div class="pagination-buttons">
            <!-- Tombol pagination kosong untuk konsistensi layout -->
        </div>
    </div>
    @elseif(isset($users) && $users->perPage() >= 1000)
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan semua {{ $users->total() ?? 0 }} entri
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

    function showDetail(id, nama, username, email, role, status) {
        Swal.fire({
            title: 'Detail User',
            html: `
                <div class="detail-box">
                    <div class="detail-header">
                        <div class="icon-wrapper">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="detail-title">${nama}</div>
                            <div class="detail-sub">@${username}</div>
                        </div>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">${email}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Role</div>
                            <div class="detail-value">${role === 'super_admin' ? 'Super Admin' : 'Admin'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">${status === true || status === 'true' ? 'Aktif' : 'Non-Aktif'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">ID User</div>
                            <div class="detail-value">${id}</div>
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

    // Event listener untuk tombol delete - langsung dan sederhana
    document.addEventListener('click', function(event) {
        // Handle delete button clicks
    });

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

    function confirmDelete(id, nama, url, buttonElement) {
        Swal.fire({
            title: 'Hapus User',
            html: `Apakah Anda yakin ingin menghapus user <strong>${nama}</strong>?<br><small style="color: #6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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

    function resetPagination() {
        // Reset to first page when filtering/sorting
        const url = new URL(window.location);
        url.searchParams.delete('page');
        window.history.replaceState({}, '', url);
    }
</script>
@endsection

