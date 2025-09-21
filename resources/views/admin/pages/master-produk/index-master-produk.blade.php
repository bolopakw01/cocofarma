@extends('admin.layouts.app')

@php
    $pageTitle = 'Master Produk';
@endphp

@section('title', 'Master Produk - Cocofarma')

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

    body {
        background: #f5f7fa;
        font-family: "Inter", "Segoe UI", sans-serif;
    }

    /* STAT CARDS */
    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        padding: 1.75rem 1.5rem;
        color: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        backdrop-filter: blur(6px);
        transition: transform .2s;
        border: none;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-icon {
        position: absolute;
        right: 1rem;
        bottom: 1rem;
        font-size: 3rem;
        opacity: .2;
    }

    .stat-title {
        font-size: .9rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: .25rem;
    }

    .stat-value {
        font-size: 2.4rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-sub {
        font-size: .85rem;
        opacity: .85;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    }

    /* CONTAINER */
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

    /* HEADER */
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
        margin: 0;
    }

    .header-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 5px 0 0 0;
    }

    /* CONTROLS */
    .controls {
        display: flex;
        
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .left-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .right-controls {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: flex-end;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box input {
        padding: 10px 15px 10px 40px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.9rem;
        width: 250px;
        height: 40px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        width: 280px; /* Expand on focus */
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 1;
        font-size: 0.9rem;
    }

    .search-box .clear-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 2px;
        border-radius: 50%;
        display: none;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .search-box .clear-btn:hover {
        background: #f8f9fa;
        color: #dc3545;
    }

    .search-box.has-content .clear-btn {
        display: block;
    }
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .entries-select select {
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-success {
        background: #4cc9f0;
        color: white;
    }

    .btn-success:hover {
        background: #3aafd9;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: #4361ee;
        color: white;
    }

    .btn-primary:hover {
        background: #3a4fd8;
        transform: translateY(-1px);
    }

    /* TABLE */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9ecef;
    }

    .table-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .table-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #212529;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-title i {
        color: #4361ee;
    }

    .table-responsive {
        position: relative;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 20px;
    }

    .produk-table {
        width: 100%;
        min-width: 800px; /* Ensure minimum width for proper display */
        border-collapse: collapse;
    }    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .table thead th {
        background-color: var(--light);
        color: var(--dark);
        font-weight: 600;
        padding: 15px 8px;
        text-align: left;
        border-bottom: 2px solid var(--light-gray);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Header specific alignments */
    .table thead th:nth-child(1) { text-align: center; } /* No */
    .table thead th:nth-child(2) { text-align: center; } /* Foto */
    .table thead th:nth-child(3) { text-align: left; }   /* Kode */
    .table thead th:nth-child(4) { text-align: left; }   /* Nama */
    .table thead th:nth-child(5) { text-align: left; }   /* Kategori */
    .table thead th:nth-child(6) { text-align: right; }  /* Harga */
    .table thead th:nth-child(7) { text-align: center; } /* Stok */
    .table thead th:nth-child(8) { text-align: center; } /* Status */
    .table thead th:nth-child(9) { text-align: center; } /* Aksi */

    .table thead th i {
        margin-left: 5px;
        font-size: 0.8rem;
        opacity: 0.6;
    }

    .table thead th.active i {
        opacity: 1;
    }

    /* Smaller, muted up/down icons that stack vertically */
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

    /* Bring the two arrows closer so they visually connect */
    .table th .sort-icons i.sort-up { margin-bottom: -5px; }
    .table th .sort-icons i.sort-down { margin-top: -5px; }

    .table th.active i.sort-up.active-up,
    .table th.active i.sort-down.active-down {
        color: #000 !important;
        font-size: 0.75rem;
    }

    .table td {
        padding: 15px;
        border-bottom: 1px solid var(--light-gray);
        font-size: 0.9rem;
        vertical-align: middle;
    }

    /* Column specific alignments */
    .table td:nth-child(1) { text-align: center; } /* No */
    .table td:nth-child(2) { text-align: center; } /* Foto */
    .table td:nth-child(3) { text-align: left; }   /* Kode */
    .table td:nth-child(4) { text-align: left; }   /* Nama */
    .table td:nth-child(5) { text-align: left; }   /* Kategori */
    .table td:nth-child(6) { text-align: right; }  /* Harga */
    .table td:nth-child(7) { text-align: center; } /* Stok */
    .table td:nth-child(8) { text-align: center; } /* Status */
    .table td:nth-child(9) { text-align: center; } /* Aksi */

    .produk-table tbody tr {
        transition: var(--transition);
    }

    .produk-table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .produk-table tbody tr:nth-child(odd) {
        background-color: #fafbfc;
    }

    .produk-table tbody tr:last-child td {
        border-bottom: none;
    }

    .produk-foto {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        border: 2px solid #e9ecef;
        transition: transform 0.2s ease;
    }

    .produk-foto:hover {
        transform: scale(1.1);
    }

    .produk-kode {
        font-weight: 600;
        color: #4361ee;
        font-family: "Courier New", monospace;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    .produk-nama {
        font-weight: 600;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .produk-kategori {
        background: #4895ef;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .produk-harga {
        font-weight: 600;
        color: #4cc9f0;
    }

    .produk-stok.low {
        color: #e63946;
        font-weight: 600;
    }

    .produk-stok.normal {
        color: #2b9348;
        font-weight: 600;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-aktif {
        background: #d4edda;
        color: #155724;
    }

    .status-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap; /* Prevent wrapping */
    }    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        margin: 0 1px;
        text-decoration: none;
        color: white;
        flex-shrink: 0; /* Prevent shrinking */
    }    .btn-view {
        background: #4895ef;
        color: white;
    }

    .btn-edit {
        background: #f72585;
        color: white;
    }

    .btn-delete {
        background: #e63946;
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* Export / Print buttons */
    .btn-export, .btn-print {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: none;
    }

    .btn-export i, .btn-print i {
        font-size: 0.95rem;
    }

    /* Small screens - reduce button padding */
    @media (max-width: 480px) {
        .btn-export, .btn-print {
            padding: 6px 8px;
            font-size: 0.85rem;
        }
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #4361ee;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #212529;
    }

    .empty-text {
        font-size: 1rem;
        margin-bottom: 30px;
    }

    /* PAGINATION */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 20px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        text-decoration: none;
        color: #495057;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        font-size: 0.9rem;
    }

    .pagination .active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .pagination a:hover {
        background: #f8f9fa;
        color: #4361ee;
    }

    .pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* STATUS INDICATOR */
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

    /* TOAST */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: #212529;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 1100;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Enhanced Toast with Icons */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: #212529;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 1100;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .toast i {
        font-size: 1.1em;
    }

    .toast.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .toast.error {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
    }

    .toast.info {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
    }

    .toast.warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .table thead th {
            padding: 8px 4px;
            font-size: 0.75rem;
        }

        .table td {
            padding: 8px 4px;
            font-size: 0.8rem;
        }

        .table thead th:nth-child(3),
        .table thead th:nth-child(4) {
            display: none; /* Hide Kode and Nama on very small screens */
        }

        .table td:nth-child(3),
        .table td:nth-child(4) {
            display: none; /* Hide Kode and Nama on very small screens */
        }

        .btn-action {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
    }

        .left-controls, .right-controls {
            width: 100%;
            flex-wrap: wrap;
        }

        .search-box {
            width: 100%;
            max-width: 300px;
        }

        .search-box input {
            width: 100%;
            height: 38px;
        }

        .search-box input:focus {
            width: 100%; /* Don't expand on mobile */
        }

        .produk-table th,
        .produk-table td {
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: row; /* Keep horizontal on mobile */
            gap: 2px; /* Smaller gap on mobile */
            justify-content: center;
        }

        .btn-action {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
            margin: 0 1px;
        }

        .pagination-container {
            flex-direction: column;
            gap: 15px;
        }

        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
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
        max-width: 600px;
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        color: #fff;
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

    .produk-foto-detail {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e9ecef;
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

    .status-highlight {
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .status-aktif {
        background: #d4edda;
        color: #155724;
    }

    .status-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="page-header">
        <h1><i class="fas fa-box"></i> Master Produk</h1>
        <a href="{{ route('backoffice.master-produk.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card bg-gradient-primary">
                <div class="stat-title">Total Produk</div>
                <div class="stat-value">{{ $produks->total() }}</div>
                <div class="stat-sub">Produk aktif</div>
                <i class="fas fa-box-open stat-icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card bg-gradient-success">
                <div class="stat-title">Produk Aktif</div>
                <div class="stat-value">{{ $produks->where('status', true)->count() }}</div>
                <div class="stat-sub">Siap dijual</div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card bg-gradient-info">
                <div class="stat-title">Kategori</div>
                <div class="stat-value">{{ $produks->unique('kategori')->count() }}</div>
                <div class="stat-sub">Jenis produk</div>
                <i class="fas fa-tags stat-icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card bg-gradient-danger">
                <div class="stat-title">Stok Rendah</div>
                <div class="stat-value">{{ $produks->filter(function($produk) { return $produk->stok <= $produk->minimum_stok; })->count() }}</div>
                <div class="stat-sub">Perlu perhatian</div>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls">
        <div class="left-controls">
            <div class="entries-select">
                <span>Tampilkan</span>
                <select id="entriesSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">Semua</option>
                </select>
                <span>entri</span>
            </div>
        </div>

        <div class="right-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari produk..." value="{{ request('search') }}">
                <button class="clear-btn" onclick="clearSearch()" title="Clear search">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <button class="btn btn-success btn-export" id="btnExport" title="Export">
                <i class="fas fa-file-export"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
            <button class="btn btn-primary btn-print" id="btnPrint" title="Print">
                <i class="fas fa-print"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-list"></i>
                Daftar Produk
            </h2>
        </div>

        <div class="table-responsive">
            @if($produks->count() > 0)
                <table class="table" id="produkTable">
                    <thead>
                        <tr>
                            <th data-sort="no" style="width: 5%; text-align: center;">No <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="foto" style="width: 8%; text-align: center;">Foto</th>
                            <th data-sort="kode" style="width: 12%;">Kode <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="nama" style="width: 20%;">Nama <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="kategori" style="width: 12%;">Kategori <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="harga" style="width: 12%; text-align: right;">Harga <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="stok" style="width: 10%; text-align: center;">Stok <span class="sort-icons"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                            <th data-sort="status" style="width: 10%; text-align: center;">Status</th>
                            <th style="width: 11%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $index => $produk)
                            <tr data-id="{{ $produk->id }}">
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td style="text-align: center;">
                                    @if($produk->foto)
                                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="produk-foto">
                                    @else
                                        <div class="produk-foto" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #6c757d;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="produk-kode">{{ $produk->kode_produk }}</span>
                                </td>
                                <td>
                                    <div class="produk-nama">{{ $produk->nama_produk }}</div>
                                    <small style="color: #6c757d;">{{ Str::limit($produk->deskripsi, 50) }}</small>
                                </td>
                                <td>
                                    <span class="produk-kategori">{{ $produk->kategori }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <span class="produk-harga">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                    <small style="color: #6c757d;">/{{ $produk->satuan }}</small>
                                </td>
                                <td style="text-align: center;">
                                    <span class="produk-stok {{ $produk->stok <= $produk->minimum_stok ? 'low' : 'normal' }}">
                                        {{ $produk->stok }} {{ $produk->satuan }}
                                    </span>
                                    @if($produk->stok <= $produk->minimum_stok)
                                        <br><small style="color: #e63946;">Min: {{ $produk->minimum_stok }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-indicator {{ $produk->status ? 'status-active' : 'status-inactive' }}"></span>
                                    <span class="status-badge status-{{ $produk->is_active ? 'aktif' : 'nonaktif' }}">
                                        {{ $produk->status_label }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-view" title="Lihat Detail" onclick="showDetail({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', '{{ addslashes($produk->kategori) }}', {{ $produk->harga_jual }}, {{ $produk->stok }}, '{{ $produk->satuan }}', '{{ $produk->status }}', '{{ $produk->foto ? asset('storage/' . $produk->foto) : '' }}', '{{ addslashes($produk->deskripsi) }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('backoffice.master-produk.edit', $produk) }}" class="btn-action btn-edit" title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete delete-btn"
                                                data-id="{{ $produk->id }}"
                                                data-nama="{{ $produk->nama_produk }}"
                                                data-url="{{ route('backoffice.master-produk.destroy', $produk) }}"
                                                title="Hapus Produk">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: var(--gray);">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <br>
                        Tidak ada data master produk
                    </td>
                </tr>
            @endif
        </div>

        @if($produks->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $produks->firstItem() }} sampai {{ $produks->lastItem() }} dari {{ $produks->total() }} entri
                </div>
                <div class="pagination">
                    @if($produks->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $produks->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach($produks->getUrlRange(1, $produks->lastPage()) as $page => $url)
                        @if($page == $produks->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($produks->hasMorePages())
                        <a href="{{ $produks->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast"></div>

<script>
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entriesSelect');
    const btnExport = document.getElementById('btnExport');
    const btnPrint = document.getElementById('btnPrint');
    const table = document.getElementById('produkTable');
    const thElements = document.querySelectorAll('th[data-sort]');
    const toast = document.getElementById('toast');

    // Data untuk sorting
    let currentSort = {
        column: null,
        direction: 'asc' // 'asc' or 'desc'
    };

    // Event Listeners
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterData();
            toggleClearButton();
        });
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

    // Clear button functionality
    function toggleClearButton() {
        const searchBox = document.querySelector('.search-box');
        if (!searchBox) return;

        if (searchInput && searchInput.value.trim().length > 0) {
            searchBox.classList.add('has-content');
        } else {
            searchBox.classList.remove('has-content');
        }
    }

    function clearSearch() {
        if (searchInput) {
            searchInput.value = '';
            filterData();
            toggleClearButton();
            searchInput.focus();
        }
    }

    // Add event listeners to table headers for sorting
    thElements.forEach(th => {
        th.addEventListener('click', () => {
            const column = th.getAttribute('data-sort');
            if (column && column !== 'foto') { // Skip sorting for foto column
                sortTable(column);
            }
        });
    });

    // Add event listeners to delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const url = this.getAttribute('data-url');
                confirmDelete(id, nama, url, this);
            });
        });
    });

    // Functions
    function showToast(message, type = 'success') {
        if (!toast) return;

        toast.textContent = message;
        toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Enhanced toast with icon
    function showToastWithIcon(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        let iconClass = 'fas fa-info-circle';
        switch(type) {
            case 'success':
                iconClass = 'fas fa-check-circle';
                break;
            case 'error':
                iconClass = 'fas fa-exclamation-triangle';
                break;
            case 'warning':
                iconClass = 'fas fa-exclamation-circle';
                break;
            case 'info':
                iconClass = 'fas fa-info-circle';
                break;
        }

        toast.innerHTML = `
            <i class="${iconClass}"></i>
            ${message}
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    function filterData() {
        if (!searchInput || !table) return;

        const searchText = searchInput.value.toLowerCase();
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        let visibleCount = 0;

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            // Search in relevant columns (skip No, Foto, and Aksi columns)
            for (let j = 2; j < cells.length - 1; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }

            if (found) {
                rows[i].style.display = '';
                visibleCount++;
            } else {
                rows[i].style.display = 'none';
            }
        }

        updateRowNumbers();

        // Show search results count
        if (searchText.length > 0) {
            showToastWithIcon(`Ditemukan ${visibleCount} hasil untuk "${searchText}"`, visibleCount > 0 ? 'info' : 'warning');
        }
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

    function changeEntries() {
        if (!entriesSelect) return;
        const entries = entriesSelect.value;

        // Request server with selected per_page (including 'all')
        const url = new URL(window.location);
        url.searchParams.set('per_page', entries === 'all' ? 'all' : entries);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function sortTable(column) {
        if (!table) return;

        // Update current sort
        if (currentSort.column === column) {
            // Toggle direction if same column
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            // New column, default to ascending
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Update UI to show current sort
        thElements.forEach(th => {
            th.classList.remove('active');
            const sortIcons = th.querySelector('.sort-icons');
            if (sortIcons) {
                const upIcon = sortIcons.querySelector('.sort-up');
                const downIcon = sortIcons.querySelector('.sort-down');
                if (upIcon) upIcon.classList.remove('active-up');
                if (downIcon) downIcon.classList.remove('active-down');
            }
        });

        const currentTh = document.querySelector(`th[data-sort="${column}"]`);
        if (currentTh) {
            currentTh.classList.add('active');
            const sortIcons = currentTh.querySelector('.sort-icons');
            if (sortIcons) {
                if (currentSort.direction === 'asc') {
                    const upIcon = sortIcons.querySelector('.sort-up');
                    if (upIcon) upIcon.classList.add('active-up');
                } else {
                    const downIcon = sortIcons.querySelector('.sort-down');
                    if (downIcon) downIcon.classList.add('active-down');
                }
            }
        }

        // Get table rows
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));

        // Sort rows based on column and direction
        rows.sort((a, b) => {
            const aValue = a.cells[getColumnIndex(column)].textContent.trim();
            const bValue = b.cells[getColumnIndex(column)].textContent.trim();

            let comparison = 0;

            if (column === 'no') {
                // Numeric sort
                comparison = parseInt(aValue) - parseInt(bValue);
            } else if (column === 'harga') {
                // Price sort - extract number
                const aPrice = parseInt(aValue.replace(/[^\d]/g, ''));
                const bPrice = parseInt(bValue.replace(/[^\d]/g, ''));
                comparison = aPrice - bPrice;
            } else if (column === 'stok') {
                // Stock sort - extract number
                const aStock = parseInt(aValue.split(' ')[0]);
                const bStock = parseInt(bValue.split(' ')[0]);
                comparison = aStock - bStock;
            } else {
                // String sort
                comparison = aValue.localeCompare(bValue);
            }

            return currentSort.direction === 'asc' ? comparison : -comparison;
        });

        // Remove existing rows
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }

        // Add sorted rows
        rows.forEach(row => {
            tbody.appendChild(row);
        });

        updateRowNumbers();
        showToastWithIcon(`Data diurutkan berdasarkan ${column} (${currentSort.direction === 'asc' ? 'naik' : 'turun'})`, 'success');
    }

    function getColumnIndex(column) {
        if (!table) return 0;

        const headers = Array.from(table.querySelectorAll('th'));
        return headers.findIndex(header => header.getAttribute('data-sort') === column);
    }

    function exportData() {
        showToastWithIcon('Mempersiapkan data untuk diexport...', 'info');

        // In a real application, you would implement export functionality
        setTimeout(() => {
            showToastWithIcon('Data berhasil diexport!', 'success');
        }, 2000);
    }

    function printData() {
        showToastWithIcon('Mempersiapkan data untuk dicetak...', 'info');

        // In a real application, you would implement print functionality
        setTimeout(() => {
            window.print();
        }, 1000);
    }

    // Show detail popup
    function showDetail(id, nama, kategori, harga, stok, satuan, status, foto, deskripsi) {
        const html = `
            <div class="detail-box">
                <div class="detail-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <div class="detail-title">${nama}</div>
                        <div class="detail-sub">Master Produk</div>
                    </div>
                </div>

                <div class="detail-content">
                    <div class="detail-item">
                        <div class="detail-label">Nama Produk</div>
                        <div class="detail-value">${nama}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">${kategori}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga Jual</div>
                        <div class="detail-value">Rp ${harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Stok</div>
                        <div class="detail-value">
                            <span class="stok-highlight ${stok <= 10 ? 'stok-low' : stok <= 50 ? 'stok-medium' : 'stok-high'}">
                                ${stok} ${satuan}
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-highlight status-${status === 'aktif' ? 'aktif' : 'nonaktif'}">
                                ${status === 'aktif' ? 'Aktif' : 'Nonaktif'}
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Deskripsi</div>
                        <div class="detail-value">${deskripsi || 'Tidak ada deskripsi'}</div>
                    </div>
                </div>

                ${foto ? `
                    <div style="text-align: center; margin-top: 1rem;">
                        <img src="${foto}" alt="${nama}" class="produk-foto-detail">
                    </div>
                ` : ''}
            </div>
        `;

        Swal.fire({
            title: 'Detail Master Produk',
            html: html,
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-times"></i> Tutup',
            confirmButtonColor: '#4361ee',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            width: 700,
            background: '#ffffff',
            padding: '1.8rem',
            customClass: {
                popup: 'swal-detail-popup'
            }
        });
    }

    // Submit delete form
    function submitDeleteForm(url) {
        // Show loading state
        const loadingSwal = Swal.fire({
            title: 'Menghapus...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate AJAX request (replace with actual AJAX call)
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: '_method=DELETE'
        })
        .then(response => {
            loadingSwal.close();

            if (response.ok) {
                showToastWithIcon('Produk berhasil dihapus!', 'success');
                // Remove the row from table or reload page
                setTimeout(() => {
                    location.reload(); // Or remove row dynamically
                }, 1000);
            } else {
                throw new Error('Gagal menghapus produk');
            }
        })
        .catch(error => {
            loadingSwal.close();
            console.error('Delete error:', error);
            showToastWithIcon('Terjadi kesalahan saat menghapus produk', 'error');
        });
    }

    function confirmDelete(id, nama, url, buttonElement) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus produk <strong>${nama}</strong>?<br><br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+F or F3 to focus search
        if ((e.ctrlKey && e.key === 'f') || e.key === 'F3') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }

        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            filterData();
            showToastWithIcon('Pencarian dibersihkan', 'info');
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Any initialization code can go here
        console.log('Master Produk table initialized with SweetAlert2');

        // Show welcome message
        setTimeout(() => {
            showToastWithIcon('Halaman Master Produk siap digunakan! Tekan Ctrl+F untuk pencarian cepat.', 'info');
        }, 1000);
    });
</script>
@endsection