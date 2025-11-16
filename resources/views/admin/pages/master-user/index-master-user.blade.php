@extends('admin.layouts.app')

@php
    $pageTitle = 'Master User';
@endphp

@section('title', 'Master User - Cocofarma')

@push('styles')
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --primary: #2563eb;
        --muted: #6b7280;
        --text: #0f172a;
        --bg: #f8fafc;
        --card-bg: #ffffff;
        --radius: 14px;
        --shadow: 0 10px 30px rgba(2,6,23,0.08);
        --transition: all 0.3s ease;
    }

    /* Base type scaling */
    body{ font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial; background:var(--bg); color:var(--text); font-size:clamp(14px, 1.2vw, 16px); }

    .master-user-avatar-cell {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .master-user-avatar-thumb {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
        overflow: hidden;
    }

    .master-user-avatar-thumb.has-image {
        border-color: #3b82f6;
        background: transparent;
    }

    .master-user-avatar-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* SweetAlert container tweaks */
    .swal2-popup{
        max-width: 820px !important;
        border-radius: 16px !important;
        padding: 0 !important;
        overflow: visible;
        box-shadow: var(--shadow);
        animation: popup-scale 0.28s ease;
    }
    @keyframes popup-scale{0%{transform:scale(.96);opacity:0}100%{transform:scale(1);opacity:1}}

    /* Card */
    .user-card{ background:var(--card-bg) !important; border-radius:var(--radius); overflow:hidden; box-shadow: 0 8px 22px rgba(2,6,23,0.06); text-align:left; color:var(--text) !important; width:100%; }

    /* Header: avatar on left, text on right for wide screens; stacked on small screens */
    .user-header{ display:flex; flex-direction:row; gap:18px; align-items:flex-start; padding:18px 22px 14px 22px; background:linear-gradient(90deg, rgba(37,99,235,0.04), rgba(99,102,241,0.02)); }
    .avatar{ width:120px; height:120px; border-radius:12px; flex-shrink:0; box-shadow:0 10px 30px rgba(2,6,23,0.06); overflow:hidden; display:inline-grid; place-items:center; background:var(--primary); }
    .avatar img{ width:100%; height:100%; object-fit:cover; display:block }
    .avatar-initials{ font-weight:700; font-size:38px; color:#fff }

    .user-info{ flex:1; min-width:0 }
    .user-name{ margin:0; font-size:clamp(1.125rem, 2.2vw, 1.375rem); font-weight:700; letter-spacing:0.1px; color:var(--text); overflow-wrap:break-word }
    .user-handle{ color:var(--muted); margin-bottom:6px; font-size:0.95rem }
    .chip{ display:inline-flex; align-items:center; gap:8px; padding:.18rem .55rem; border-radius:999px; font-weight:600; font-size:.8rem; background:rgba(0,0,0,0.04); color:var(--muted); border:1px solid rgba(0,0,0,0.04); }

    .status-badge{ display:inline-block; margin-left:8px; padding:.22rem .6rem; border-radius:999px; font-weight:700; font-size:.78rem; background:linear-gradient(180deg, rgba(37,99,235,0.12), rgba(37,99,235,0.06)); color:var(--primary); border:1px solid rgba(37,99,235,0.12) }

    .role-badge{ margin-left:8px; background:linear-gradient(180deg, rgba(99,102,241,0.10), rgba(99,102,241,0.04)); color:#4f46e5; border:1px solid rgba(79,70,229,0.08) }

    .user-body{ padding:16px 22px 8px 22px; }
    .info-grid{ display:grid; grid-template-columns: 130px 1fr; gap:12px 18px; align-items:start }
    .info-label{ font-weight:700; color:var(--muted); font-size:0.95rem }
    .info-value{ color:var(--text); font-size:0.98rem; word-break:break-word }

    .contact-row{ display:flex; gap:10px; align-items:center }
    .btn-copy{ border:1px dashed rgba(0,0,0,0.06); background:transparent; padding:.26rem .5rem; border-radius:10px; color:#6b7280; transition: all 0.2s ease; }
    .btn-copy:hover{ background:#f1f5f9; color:#374151; border-color:#d1d5db; }

    .address-collapsed{ max-height:3.6rem; overflow:hidden; transition:max-height .22s ease }
    .address-expanded{ max-height: 200px }
    .address-textarea{ width:100%; min-height:48px; resize:vertical; }

    .card-footer{ padding:14px 18px; display:flex; gap:10px; justify-content:space-between; align-items:center; background:transparent }

    /* footer metadata (e.g. 'Dibuat') smaller and muted */
    .card-footer .footer-meta{ font-size:0.78rem; color:var(--muted); opacity:0.95; }

    .close-x{ position:absolute; right:18px; top:14px; background:transparent; border:none; color:var(--muted); font-size:1.25rem }
    .theme-btn{ position:absolute; right:56px; top:12px; background:transparent; border:none; color:var(--muted); font-size:1.05rem; display:inline-flex; align-items:center; gap:6px }

    /* Tablet breakpoint */
    @media (max-width:1024px){
        .user-header{ padding:16px 18px 12px 18px; gap:14px }
        .avatar{ width:110px; height:110px }
        .info-grid{ grid-template-columns: 120px 1fr }
        .user-name{ font-size:1.2rem }
        .swal2-popup{ max-width: 92vw !important }
    }

    /* Mobile: stack and make inputs full width */
    @media (max-width:640px){
        .user-header{ flex-direction:column; gap:12px; padding:16px }
        .avatar{ width:90px; height:90px }
        .info-grid{ grid-template-columns: 1fr; gap:8px 0; }
        /* When grid collapses to 1 column, put the label above the value */
        .info-grid > div:nth-child(odd){ order: 0 }
        .info-grid > div:nth-child(even){ order: 1 }
        .info-label{ display:block; margin-bottom:6px }
        .card-footer{ flex-direction:column; align-items:stretch; gap:8px }
        .card-footer .btn{ width:100% }
        .swal2-popup{ max-width: 96vw !important }
    }

    /* Larger desktops: give more room for labels */
    @media (min-width:1200px){
        .info-grid{ grid-template-columns: 160px 1fr }
        .avatar{ width:140px; height:140px }
    }

    /* Force pure white card and black text */
    .user-card,
    .user-card .user-name,
    .user-card .user-handle,
    .user-card .user-info,
    .user-card .info-label,
    .user-card .info-value,
    .user-card .chip,
    .user-card .status-badge,
    .user-card .role-badge {
        color: #000000 !important;
    }

    /* Make links inside the card inherit black color */
    .user-card a { color: inherit !important; }

    /* Ensure muted text also appears darker */
    .text-muted { color: #222 !important; }

    /* Remove default Swal confirm button styling if present */
    .swal2-confirm.btn.btn-primary{ background:var(--primary); border:none; box-shadow:none }
</style>
@endpush

@section('content')
<x-admin.data-table id="master-user-table">
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="user" alt="Master User" size="28" />
            <span>Master User</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.master-user.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" />
                Tambah User
            </a>
        </div>
    </x-slot>

    <x-slot name="controls">
        <div class="bolopa-tabel-left-controls">
            <div class="bolopa-tabel-entries-select">
                <label for="entriesSelect">Tampilkan</label>
                <select id="entriesSelect">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ in_array(request('per_page', 10), ['1000', 'all']) ? 'selected' : '' }}>Semua</option>
                </select>
                <span>entri</span>
            </div>
        </div>

        <div class="bolopa-tabel-right-controls">
            <div class="bolopa-tabel-search-box">
                <x-admin.icon name="search" alt="Cari" size="16" />
                <input type="text" id="searchInput" placeholder="Cari user..." value="{{ request('search') }}">
            </div>

            @if(Auth::check() && Auth::user()->role === 'super_admin')
                <button class="bolopa-tabel-btn bolopa-tabel-btn-success" id="btnExport" type="button">
                    <x-admin.icon name="export" alt="Export" />
                    Export
                </button>
            @endif
            @if(Auth::check() && Auth::user()->role === 'super_admin')
                <button class="bolopa-tabel-btn bolopa-tabel-btn-primary" id="btnPrint" type="button">
                    <x-admin.icon name="print" alt="Print" />
                    Print
                </button>
            @endif
        </div>
    </x-slot>

    <x-slot name="table">
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th data-sort="no">No
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th style="text-align: center;">Foto</th>
                    <th data-sort="nama">Nama
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="username">Username
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="email">Email
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="role" style="text-align: center;">Role
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="status" style="text-align: center;">Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $index => $user)
                    @php
                        $avatarUrl = $user->avatar_path ? asset('storage/' . ltrim($user->avatar_path, '/')) : '';
                        $initial = strtoupper(substr(trim($user->name), 0, 1) ?: 'U');
                    @endphp
                    <tr data-search="{{ strtolower($user->name.' '.$user->username.' '.$user->email.' '.$user->role) }}">
                        <td data-sort-value="{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                        </td>
                        <td class="master-user-avatar-cell" data-sort-value="{{ $avatarUrl ? 1 : 0 }}">
                            <div class="master-user-avatar-thumb{{ $avatarUrl ? ' has-image' : '' }}" aria-hidden="true">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="Avatar {{ $user->name }}">
                                @else
                                    {{ $initial }}
                                @endif
                            </div>
                        </td>
                        <td data-sort-value="{{ strtolower($user->name) }}">
                            <span class="bolopa-tabel-status-indicator {{ $user->status ? 'bolopa-tabel-status-active' : 'bolopa-tabel-status-inactive' }}"></span>
                            {{ $user->name }}
                        </td>
                        <td data-sort-value="{{ strtolower($user->username) }}">{{ $user->username }}</td>
                        <td data-sort-value="{{ strtolower($user->email) }}">{{ $user->email }}</td>
                        <td data-sort-value="{{ strtolower($user->role) }}" style="text-align: center;">
                            @if($user->role === 'super_admin')
                                <span style="background-color: #add8e6; color: #002329; padding: 0.18rem 0.55rem; border-radius: 999px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 8px;">
                                    Super Admin
                                </span>
                            @else
                                <span class="bolopa-tabel-badge bolopa-tabel-badge-warning">
                                    Admin
                                </span>
                            @endif
                        </td>
                        <td data-sort-value="{{ $user->status ? 1 : 0 }}" style="text-align: center;">
                            <span class="bolopa-tabel-badge {{ $user->status ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                                {{ $user->status ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                            @php
                                $phone = $user->phone ?? '';
                                $address = $user->address ?? '';
                                $createdAt = $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '';
                            @endphp
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                                onclick="showDetail({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->username) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', {{ $user->status ? 'true' : 'false' }}, '{{ addslashes($avatarUrl) }}', '{{ addslashes($phone) }}', '{{ addslashes($address) }}', '{{ addslashes($createdAt) }}', '{{ route('backoffice.master-user.edit', $user->id) }}')"
                                aria-label="Lihat detail {{ $user->name }}">
                                <x-admin.icon name="view" alt="Detail" size="16" />
                            </button>
                            <a href="{{ route('backoffice.master-user.edit', $user->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $user->name }}">
                                <x-admin.icon name="edit" alt="Edit" size="16" />
                            </a>
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                                onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ route('backoffice.master-user.destroy', $user->id) }}')"
                                aria-label="Hapus {{ $user->name }}">
                                <x-admin.icon name="delete" alt="Hapus" size="16" />
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px;">
                            <x-admin.icon name="user" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                            <br>
                            Tidak ada data user
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($users) && ($users->hasPages() || $users->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($users->total() > 0)
                        Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($users->hasPages())
                        @if($users->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $users->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if($page == $users->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($users->perPage() >= 1000 && $users->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="masterUserToast"></div>
@endsection

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script src="{{ asset('bolopa/back/js/bolopa-export-print.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableApi = window.initBolopaTable({
            tableSelector: '#dataTable',
            entriesSelector: '#entriesSelect',
            searchInputSelector: '#searchInput',
            toastSelector: '#masterUserToast'
        });

        const searchInput = document.getElementById('searchInput');

        const entriesSelect = document.getElementById('entriesSelect');
        if (entriesSelect) {
            entriesSelect.addEventListener('change', function (event) {
                const value = event.target.value;
                const url = new URL(window.location.href);
                if (value === 'all') {
                    url.searchParams.set('per_page', '1000');
                } else {
                    url.searchParams.set('per_page', value);
                }
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        }

        const notify = function (message, type) {
            if (tableApi && typeof tableApi.showToast === 'function') {
                tableApi.showToast(message, type);
            } else if (type === 'error') {
                console.error(message);
            }
        };

        window.initBolopaExportPrint({
            tableSelector: '#dataTable',
            exportButtonSelector: '#btnExport',
            printButtonSelector: '#btnPrint',
            filenamePrefix: 'master-user',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Master User',
            printBrandSubtitle: 'Daftar akun pengguna',
            printNotes: 'Catatan: Kolom aksi dihilangkan pada hasil cetak untuk menjaga privasi aksi.',
            totalLabel: 'Total User',
            notify: notify,
            messages: {
                exportSuccess: 'Data user berhasil diekspor.',
                exportError: 'Gagal export data user.',
                printInfo: 'Membuka tampilan print...',
                printError: 'Gagal membuka tampilan print.'
            }
        });
    });

    function confirmDelete(id, nama, url) {
        Swal.fire({
            title: 'Hapus User',
            html: `Apakah Anda yakin ingin menghapus user <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteUser(url);
            }
        });
    }

    async function deleteUser(url) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showToast('CSRF token tidak ditemukan.', 'error');
                return;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            });

            const rawText = await response.text();
            let data = null;
            if (rawText) {
                try {
                    data = JSON.parse(rawText);
                } catch (parseError) {
                    // biarkan data null, gunakan rawText untuk pesan
                }
            }

            if (response.ok) {
                showToast(data && data.message ? data.message : 'User berhasil dihapus.', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                const errorMsg = data && data.message ? data.message : (rawText ? rawText.substring(0, 120) : 'Gagal menghapus user.');
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            showToast('Terjadi kesalahan saat menghapus user.', 'error');
        }
    }

    function showDetail(id, nama, username, email, role, status, avatarUrl, phone, address, createdAt, editUrl) {
        const roleLabel = role === 'super_admin' ? 'Super Admin' : 'Admin';
        const statusLabel = status ? 'Aktif' : 'Non-Aktif';
        const initials = nama ? nama.trim().charAt(0).toUpperCase() : 'U';
        const safeAvatar = avatarUrl && avatarUrl.trim() !== '' ? avatarUrl : null;
        const avatarUrlFinal = safeAvatar || createInitialsSVG(nama, 120);

        const createdText = createdAt && createdAt.trim() !== '' ? new Date(createdAt).toLocaleString('id-ID') : '—';

        const statusBadgeHtml = `<span class="bolopa-tabel-badge ${status ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger'}" aria-label="status">${statusLabel}</span>`;
        const roleBadgeHtml = role === 'super_admin' ? `<span class="ms-2" style="background-color: #add8e6; color: #fff; padding: 0.18rem 0.55rem; border-radius: 999px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 8px;">Super Admin</span>` : `<span class="bolopa-tabel-badge bolopa-tabel-badge-warning ms-2">${roleLabel}</span>`;

        const html = `
            <div class="position-relative">
                <button class="close-x" aria-label="Tutup" id="modalCloseX"><i class="bi bi-x-lg"></i></button>
                <div class="card user-card" role="dialog" aria-labelledby="user-name" id="userProfileDialog">
                    <div class="user-header">
                        <div class="avatar" id="avatarWrap" style="width:120px;height:120px;border-radius:12px;">
                            <img src="${avatarUrlFinal}" alt="Avatar ${nama}" id="avatarImg" onerror="this.onerror=null;this.src='${createInitialsSVG(nama,120)}'" />
                        </div>
                        <div class="user-info">
                            <div class="d-flex align-items-center flex-wrap">
                                <h4 class="user-name me-2" id="user-name">${nama}</h4>
                                ${statusBadgeHtml}
                            </div>
                            <div class="user-handle">&#64;${username} ${roleBadgeHtml} • <span class="text-muted">#${id}</span></div>
                        </div>
                    </div>

                    <div class="user-body">
                        <div class="info-grid">
                            <div>
                                <div class="info-label">Email</div>
                            </div>
                            <div>
                                <div class="info-value contact-row">
                                    <input type="email" class="form-control form-control-sm me-2" id="emailInput" value="${email}" aria-label="Email" readonly />
                                    <button class="btn-copy" onclick="copyToClipboard('${email}')" title="Salin Email"><i class="bi bi-clipboard"></i></button>
                                </div>
                            </div>

                            <div>
                                <div class="info-label">Telepon</div>
                            </div>
                            <div>
                                <div class="info-value contact-row">
                                    <input type="tel" class="form-control form-control-sm me-2" id="phoneInput" value="${phone}" aria-label="Telepon" readonly />
                                    ${phone ? `<button class="btn-copy" onclick="copyToClipboard('${phone}')" title="Salin Telepon"><i class="bi bi-clipboard"></i></button>` : ''}
                                </div>
                            </div>

                            <div>
                                <div class="info-label">Alamat</div>
                            </div>
                            <div>
                                <div class="info-value">
                                    <textarea class="form-control form-control-sm address-textarea" id="addrInput" rows="2" aria-label="Alamat" readonly>${address}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div>
                            <div class="footer-meta">Dibuat: ${createdText}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="${editUrl}" class="btn btn-outline-primary btn-sm" id="editBtn">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            ${email ? `<a href="mailto:${email}" class="btn btn-outline-secondary btn-sm" id="msgBtn">
                                <i class="bi bi-envelope me-1"></i>Kirim Email
                            </a>` : ''}
                            <button class="btn btn-primary btn-sm" id="closeBtn">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            html,
            showConfirmButton: false,
            showCloseButton: false,
            focusConfirm: false,
            width: 'min(96vw, 820px)',
            background: 'rgba(0,0,0,0.5)',
            buttonsStyling: false,
            allowEscapeKey: true,
            allowOutsideClick: true,
            didOpen: () => {
                const closeBtn = document.getElementById('closeBtn');
                const closeX = document.getElementById('modalCloseX');

                if(closeBtn) closeBtn.addEventListener('click', () => Swal.close());
                if(closeX) closeX.addEventListener('click', () => Swal.close());

                if(closeBtn) closeBtn.focus();
            },
            customClass: {
                popup: 'swal-detail-popup'
            }
        });
    }

    // Helper to create an SVG data URL with initials if avatar fails or not provided
    function createInitialsSVG(name, size = 120, bg = '#2b6df6', fg = '#fff', radius = 12){
        const initials = (name || '').split(' ').filter(Boolean).slice(0,2).map(s => s[0].toUpperCase()).join('') || 'U';
        const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='${size}' height='${size}' viewBox='0 0 ${size} ${size}'><rect width='100%' height='100%' fill='${bg}' rx='${radius}' /><text x='50%' y='55%' font-family='Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial' font-size='${Math.round(size/2.8)}' fill='${fg}' text-anchor='middle' alignment-baseline='middle' font-weight='700'>${initials}</text></svg>`;
        return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
    }

    // Function to copy text to clipboard
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Disalin ke clipboard!', 'success');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                fallbackCopyTextToClipboard(text);
            });
        } else {
            fallbackCopyTextToClipboard(text);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('Disalin ke clipboard!', 'success');
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            showToast('Gagal menyalin.', 'error');
        }
        document.body.removeChild(textArea);
    }

    function showToast(message, type) {
        const toast = document.getElementById('masterUserToast');
        if (toast && window.initBolopaTable) {
            const tableApi = window.initBolopaTable({ toastSelector: '#masterUserToast' });
            if (tableApi && typeof tableApi.showToast === 'function') {
                tableApi.showToast(message, type);
            }
        }
    }

    function resetPagination() {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush
