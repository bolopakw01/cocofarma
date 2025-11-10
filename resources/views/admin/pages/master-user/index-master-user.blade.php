@extends('admin.layouts.app')

@php
    $pageTitle = 'Master User';
@endphp

@section('title', 'Master User - Cocofarma')

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
                    <th data-sort="role">Role
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="status">Status
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
                    <tr data-search="{{ strtolower($user->name.' '.$user->username.' '.$user->email.' '.$user->role) }}">
                        <td data-sort-value="{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                        </td>
                        <td data-sort-value="{{ strtolower($user->name) }}">
                            <span class="bolopa-tabel-status-indicator {{ $user->status ? 'bolopa-tabel-status-active' : 'bolopa-tabel-status-inactive' }}"></span>
                            {{ $user->name }}
                        </td>
                        <td data-sort-value="{{ strtolower($user->username) }}">{{ $user->username }}</td>
                        <td data-sort-value="{{ strtolower($user->email) }}">{{ $user->email }}</td>
                        <td data-sort-value="{{ strtolower($user->role) }}">
                            <span class="bolopa-tabel-badge {{ $user->role === 'super_admin' ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                                {{ $user->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                            </span>
                        </td>
                        <td data-sort-value="{{ $user->status ? 1 : 0 }}">
                            <span class="bolopa-tabel-badge {{ $user->status ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                                {{ $user->status ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                                onclick="showDetail({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->username) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', {{ $user->status ? 'true' : 'false' }})"
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
                        <td colspan="7" style="text-align:center; padding:40px;">
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

    function submitDeleteForm(url) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    function confirmDelete(id, nama, url) {
        Swal.fire({
            title: 'Hapus User',
            html: `Apakah Anda yakin ingin menghapus user <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#4361ee',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitDeleteForm(url);
            }
        });
    }

    function showDetail(id, nama, username, email, role, status) {
        const roleLabel = role === 'super_admin' ? 'Super Admin' : 'Admin';
        const statusLabel = status ? 'Aktif' : 'Non-Aktif';
        Swal.fire({
            title: 'Detail User',
            html: `
                <div class="detail-box">
                    <div class="detail-header">
                        <div class="icon-wrapper">
                            <img src="{{ asset('bolopa/back/images/icon/bi--person-circle.svg') }}" alt="User" style="width:32px;height:32px;">
                        </div>
                        <div>
                            <div class="detail-title">${nama}</div>
                            <div class="detail-sub">&#64;${username}</div>
                        </div>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">${email}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Role</div>
                            <div class="detail-value">${roleLabel}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">${statusLabel}</div>
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

    function resetPagination() {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush
