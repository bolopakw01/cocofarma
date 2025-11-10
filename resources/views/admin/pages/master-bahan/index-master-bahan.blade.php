@extends('admin.layouts.app')

@php
    $pageTitle = 'Master Bahan Baku';
@endphp

@section('title', 'Master Bahan Baku - Cocofarma')

@section('content')
<x-admin.data-table id="master-bahan-table">
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="material" alt="Master Bahan" size="28" />
            <span>Master Bahan Baku</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.master-bahan.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" />
                Tambah Master Bahan
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
                    <option value="all" {{ request('per_page', 10) == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
                <span>entri</span>
            </div>
        </div>

        <div class="bolopa-tabel-right-controls">
            <div class="bolopa-tabel-search-box">
                <x-admin.icon name="search" alt="Cari" size="16" />
                <input type="text" id="searchInput" placeholder="Cari master bahan..." value="{{ request('search') }}">
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
                    <th data-sort="no" style="width: 6%;">
                        No
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="kode" style="width: 15%;">
                        Kode
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="nama" style="width: 20%;">
                        Nama Bahan
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="satuan" style="width: 10%;">
                        Satuan
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="harga" style="width: 15%;">
                        Harga per Unit
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="status" style="width: 15%;">
                        Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th style="width: 19%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanBakus ?? [] as $index => $bahan)
                <tr data-search="{{ strtolower($bahan->kode_bahan.' '.$bahan->nama_bahan.' '.$bahan->satuan.' '.$bahan->status) }}">
                    <td data-sort-value="{{ ($bahanBakus->currentPage() - 1) * $bahanBakus->perPage() + $index + 1 }}">
                        {{ ($bahanBakus->currentPage() - 1) * $bahanBakus->perPage() + $index + 1 }}
                    </td>
                    <td data-sort-value="{{ strtolower($bahan->kode_bahan) }}">{{ $bahan->kode_bahan }}</td>
                    <td data-sort-value="{{ strtolower($bahan->nama_bahan) }}">{{ $bahan->nama_bahan }}</td>
                    <td data-sort-value="{{ strtolower($bahan->satuan) }}">{{ $bahan->satuan }}</td>
                    <td data-sort-value="{{ $bahan->harga_per_satuan }}">Rp {{ number_format($bahan->harga_per_satuan, 0, ',', '.') }}</td>
                    <td data-sort-value="{{ $bahan->status == 'aktif' ? 1 : 0 }}">
                        <span class="bolopa-tabel-badge {{ $bahan->status == 'aktif' ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                            {{ $bahan->status == 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                            onclick="showDetail({{ $bahan->id }}, '{{ addslashes($bahan->nama_bahan) }}', '{{ $bahan->satuan }}', {{ $bahan->harga_per_satuan }}, '{{ $bahan->status }}')"
                            aria-label="Lihat detail {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="view" alt="Detail" size="16" />
                        </button>
                        <a href="{{ route('backoffice.master-bahan.edit', $bahan->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="edit" alt="Edit" size="16" />
                        </a>
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                            onclick="confirmDelete({{ $bahan->id }}, '{{ addslashes($bahan->nama_bahan) }}', '{{ route('backoffice.master-bahan.destroy', $bahan->id) }}')"
                            aria-label="Hapus {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="delete" alt="Hapus" size="16" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px;">
                        <x-admin.icon name="material" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                        <br>
                        Tidak ada data master bahan baku
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($bahanBakus) && ($bahanBakus->hasPages() || $bahanBakus->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($bahanBakus->total() > 0)
                        Menampilkan {{ $bahanBakus->firstItem() ?? 0 }} sampai {{ $bahanBakus->lastItem() ?? 0 }} dari {{ $bahanBakus->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($bahanBakus->hasPages())
                        @if($bahanBakus->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $bahanBakus->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($bahanBakus->getUrlRange(1, $bahanBakus->lastPage()) as $page => $url)
                            @if($page == $bahanBakus->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($bahanBakus->hasMorePages())
                            <a href="{{ $bahanBakus->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($bahanBakus->perPage() >= 1000 && $bahanBakus->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="masterBahanToast"></div>
@endsection

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableApi = window.initBolopaTable({
            tableSelector: '#dataTable',
            entriesSelector: '#entriesSelect',
            searchInputSelector: '#searchInput',
            toastSelector: '#masterBahanToast'
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

        const exportBtn = document.getElementById('btnExport');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                tableApi && tableApi.showToast('Fitur export akan segera tersedia.', 'info');
            });
        }

        const printBtn = document.getElementById('btnPrint');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                tableApi && tableApi.showToast('Fitur print akan segera tersedia.', 'info');
            });
        }
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
            title: 'Hapus Bahan Baku',
            html: `Apakah Anda yakin ingin menghapus bahan <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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
    function confirmDelete(id, nama, url) {
        Swal.fire({
            title: 'Hapus Bahan Baku',
            html: `Apakah Anda yakin ingin menghapus bahan <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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

    function showDetail(id, nama, satuan, harga, status) {
        const statusLabel = status === 'aktif' ? 'Aktif' : 'Non-Aktif';
        Swal.fire({
            title: 'Detail Master Bahan',
            html: `
                <div class="detail-box">
                    <div class="detail-header">
                        <div class="icon-wrapper">
                            <img src="{{ asset('bolopa/back/images/icon/material-symbols--category-rounded.svg') }}" alt="Bahan" style="width:32px;height:32px;">
                        </div>
                        <div>
                            <div class="detail-title">${nama}</div>
                            <div class="detail-sub">${satuan}</div>
                        </div>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <div class="detail-label">Harga per Satuan</div>
                            <div class="detail-value">Rp ${harga.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">${statusLabel}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">ID Bahan</div>
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
        window.location.href = '{{ route('backoffice.master-bahan.index') }}';
    }
</script>
@endpush
