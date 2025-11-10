@extends('admin.layouts.app')

@php
    $pageTitle = 'Master Produk';
@endphp

@section('title', 'Master Produk - Cocofarma')

@section('content')
<x-admin.data-table id="master-produk-table">
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="product" alt="Master Produk" size="28" />
            <span>Master Produk</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.master-produk.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" />
                Tambah Produk
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
                <input type="text" id="searchInput" placeholder="Cari produk..." value="{{ request('search') }}">
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
                    <th data-sort="kode">Kode Produk
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="nama">Nama Produk
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="kategori">Kategori
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="satuan">Satuan
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="harga">Harga Jual
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="stok">Stok
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="minimum">Min Stok
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
                @forelse($produks ?? [] as $index => $produk)
                    <tr data-search="{{ strtolower($produk->kode_produk.' '.$produk->nama_produk.' '.$produk->kategori.' '.$produk->satuan) }}">
                        <td data-sort-value="{{ ($produks->currentPage() - 1) * $produks->perPage() + $index + 1 }}">
                            {{ ($produks->currentPage() - 1) * $produks->perPage() + $index + 1 }}
                        </td>
                        <td data-sort-value="{{ strtolower($produk->kode_produk) }}">{{ $produk->kode_produk }}</td>
                        <td data-sort-value="{{ strtolower($produk->nama_produk) }}">
                            <span class="bolopa-tabel-status-indicator {{ $produk->status ? 'bolopa-tabel-status-active' : 'bolopa-tabel-status-inactive' }}"></span>
                            {{ $produk->nama_produk }}
                        </td>
                        <td data-sort-value="{{ strtolower($produk->kategori) }}">{{ $produk->kategori }}</td>
                        <td data-sort-value="{{ strtolower($produk->satuan) }}">{{ $produk->satuan }}</td>
                        <td data-sort-value="{{ $produk->harga_jual }}">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                        <td data-sort-value="{{ $produk->stok }}">{{ $produk->stok }}</td>
                        <td data-sort-value="{{ $produk->minimum_stok }}">{{ $produk->minimum_stok }}</td>
                        <td data-sort-value="{{ $produk->status ? 1 : 0 }}">
                            <span class="bolopa-tabel-badge {{ $produk->status ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                                {{ $produk->status ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                                onclick="showDetail({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', '{{ $produk->kategori }}', '{{ $produk->satuan }}', {{ $produk->harga_jual }}, {{ $produk->stok }}, {{ $produk->minimum_stok }}, {{ $produk->status ? 'true' : 'false' }})"
                                aria-label="Lihat detail {{ $produk->nama_produk }}">
                                <x-admin.icon name="view" alt="Detail" size="16" />
                            </button>
                            <a href="{{ route('backoffice.master-produk.edit', $produk->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $produk->nama_produk }}">
                                <x-admin.icon name="edit" alt="Edit" size="16" />
                            </a>
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                                onclick="confirmDelete({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', '{{ route('backoffice.master-produk.destroy', $produk->id) }}')"
                                aria-label="Hapus {{ $produk->nama_produk }}">
                                <x-admin.icon name="delete" alt="Hapus" size="16" />
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:40px;">
                            <x-admin.icon name="product" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                            <br>
                            Tidak ada data produk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($produks) && ($produks->hasPages() || $produks->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($produks->total() > 0)
                        Menampilkan {{ $produks->firstItem() ?? 0 }} sampai {{ $produks->lastItem() ?? 0 }} dari {{ $produks->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($produks->hasPages())
                        @if($produks->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $produks->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($produks->getUrlRange(1, $produks->lastPage()) as $page => $url)
                            @if($page == $produks->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($produks->hasMorePages())
                            <a href="{{ $produks->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($produks->perPage() >= 1000 && $produks->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="masterProdukToast"></div>
@endsection

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableApi = window.initBolopaTable({
            tableSelector: '#dataTable',
            entriesSelector: '#entriesSelect',
            searchInputSelector: '#searchInput',
            toastSelector: '#masterProdukToast'
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
            title: 'Hapus Produk',
            html: `Apakah Anda yakin ingin menghapus produk <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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

    function showDetail(id, nama, kategori, satuan, harga, stok, minimum, status) {
        const statusLabel = status ? 'Aktif' : 'Non-Aktif';
        Swal.fire({
            title: 'Detail Produk',
            html: `
                <div class="detail-box">
                    <div class="detail-header">
                        <div class="icon-wrapper">
                            <img src="{{ asset('bolopa/back/images/icon/f7--cube-box-fill.svg') }}" alt="Produk" style="width:32px;height:32px;">
                        </div>
                        <div>
                            <div class="detail-title">${nama}</div>
                            <div class="detail-sub">${kategori}</div>
                        </div>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <div class="detail-label">Kode Produk</div>
                            <div class="detail-value">ID-${id}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Satuan</div>
                            <div class="detail-value">${satuan}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Harga Jual</div>
                            <div class="detail-value">Rp ${harga.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Stok</div>
                            <div class="detail-value">${stok}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Minimum Stok</div>
                            <div class="detail-value">${minimum}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">${statusLabel}</div>
                        </div>
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-detail-popup'
            },
            width: '500px'
        });
    }

    function resetPagination() {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush
