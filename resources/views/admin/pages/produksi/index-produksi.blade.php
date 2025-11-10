@extends('admin.layouts.app')

@section('title', 'Manajemen Produksi')

@section('content')
<x-admin.data-table>
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="production" alt="Produksi" size="28" />
            <span>Manajemen Produksi</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.produksi.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" size="16" />
                <span>Tambah Produksi</span>
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
                <input type="text" id="searchInput" placeholder="Cari produksi..." value="{{ request('search') }}">
            </div>

            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="bolopa-tabel-btn bolopa-tabel-btn-success" id="btnExport">
                <x-admin.icon name="export" alt="Export" size="16" />
                <span>Export</span>
            </button>
            @endif
            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="bolopa-tabel-btn bolopa-tabel-btn-primary" id="btnPrint">
                <x-admin.icon name="print" alt="Print" size="16" />
                <span>Print</span>
            </button>
            @endif
        </div>
    </x-slot>

    <x-slot name="table">
        <table class="bolopa-tabel" id="dataTable">
            <thead>
                <tr>
                    <th data-sort="no" style="width: 5%;">
                        No
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="nomor_produksi" style="width: 15%;">
                        Nomor Produksi
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="batch" style="width: 15%;">
                        Batch Produksi
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="produk" style="width: 10%;">
                        Produk
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="tanggal" style="width: 15%;">
                        Tanggal Produksi
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="target" style="width: 10%;">
                        Target
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="hasil" style="width: 10%;">
                        Hasil
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="biaya" style="width: 10%;">
                        Biaya
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="status" style="width: 10%;">
                        Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksis ?? [] as $index => $produksi)
                <tr data-search="{{ strtolower($produksi->nomor_produksi . ' ' . ($produksi->batchProduksi->nomor_batch ?? '') . ' ' . ($produksi->produk->nama_produk ?? '') . ' ' . $produksi->status_label) }}">
                    <td data-sort-value="{{ $produksis->firstItem() + $index }}">{{ $produksis->firstItem() + $index }}</td>
                    <td data-sort-value="{{ strtolower($produksi->nomor_produksi) }}">{{ $produksi->nomor_produksi }}</td>
                    <td data-sort-value="{{ strtolower($produksi->batchProduksi->nomor_batch ?? '-') }}">{{ $produksi->batchProduksi->nomor_batch ?? '-' }}</td>
                    <td data-sort-value="{{ strtolower($produksi->produk->nama_produk ?? '-') }}">{{ $produksi->produk->nama_produk ?? '-' }}</td>
                    <td data-sort-value="{{ $produksi->tanggal_produksi->format('Y-m-d') }}">{{ $produksi->tanggal_produksi->format('d/m/Y') }}</td>
                    <td data-sort-value="{{ $produksi->jumlah_target }}">{{ number_format($produksi->jumlah_target, 0) }}</td>
                    <td data-sort-value="{{ $produksi->jumlah_hasil }}">{{ number_format($produksi->jumlah_hasil, 0) }}</td>
                    <td data-sort-value="{{ $produksi->biaya_produksi }}">Rp {{ number_format($produksi->biaya_produksi, 0, ',', '.') }}</td>
                    <td data-sort-value="{{ $produksi->status }}">
                        <span class="bolopa-tabel-badge {{ in_array($produksi->status, ['selesai', 'proses']) ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                            {{ $produksi->status_label }}
                        </span>
                    </td>
                    <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                            onclick="showDetail({{ $produksi->id }}, '{{ addslashes($produksi->nomor_produksi) }}', '{{ addslashes($produksi->batchProduksi->nomor_batch ?? '-') }}', '{{ addslashes($produksi->produk->nama_produk ?? '-') }}', '{{ $produksi->tanggal_produksi->format('d/m/Y') }}', {{ $produksi->jumlah_target }}, {{ $produksi->jumlah_hasil }}, {{ $produksi->biaya_produksi }}, '{{ $produksi->status_label }}')"
                            aria-label="Lihat detail {{ $produksi->nomor_produksi }}">
                            <x-admin.icon name="view" alt="Detail" size="16" />
                        </button>
                        <a href="{{ route('backoffice.produksi.edit', $produksi->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $produksi->nomor_produksi }}">
                            <x-admin.icon name="edit" alt="Edit" size="16" />
                        </a>
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                            onclick="confirmDelete({{ $produksi->id }}, '{{ addslashes($produksi->nomor_produksi) }}', '{{ route('backoffice.produksi.destroy', $produksi->id) }}')"
                            aria-label="Hapus {{ $produksi->nomor_produksi }}">
                            <x-admin.icon name="delete" alt="Hapus" size="16" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="bolopa-tabel-empty">
                        <x-admin.icon name="production" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                        <br>
                        Belum ada data produksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($produksis) && ($produksis->hasPages() || $produksis->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($produksis->total() > 0)
                        Menampilkan {{ $produksis->firstItem() ?? 0 }} sampai {{ $produksis->lastItem() ?? 0 }} dari {{ $produksis->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($produksis->hasPages())
                        @if($produksis->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $produksis->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($produksis->getUrlRange(1, $produksis->lastPage()) as $page => $url)
                            @if($page == $produksis->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($produksis->hasMorePages())
                            <a href="{{ $produksis->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($produksis->perPage() >= 1000 && $produksis->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="produksiToast"></div>
@endsection

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableApi = window.initBolopaTable({
            tableSelector: '#dataTable',
            entriesSelector: '#entriesSelect',
            searchInputSelector: '#searchInput',
            toastSelector: '#produksiToast'
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

    function confirmDelete(id, nomor, url) {
        Swal.fire({
            title: 'Hapus Produksi',
            html: `Apakah Anda yakin ingin menghapus produksi <strong>${nomor}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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

    function showDetail(id, nomor, batch, produk, tanggal, target, hasil, biaya, status) {
        Swal.fire({
            title: 'Detail Produksi',
            html: `
                <div class="detail-box">
                    <div class="detail-header">
                        <div class="icon-wrapper">
                            <img src="{{ asset('bolopa/back/images/icon/carbon--production.svg') }}" alt="Produksi" style="width:32px;height:32px;">
                        </div>
                        <div>
                            <div class="detail-title">${nomor}</div>
                            <div class="detail-sub">${batch}</div>
                        </div>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <div class="detail-label">Produk</div>
                            <div class="detail-value">${produk}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Produksi</div>
                            <div class="detail-value">${tanggal}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Target</div>
                            <div class="detail-value">${target.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Hasil</div>
                            <div class="detail-value">${hasil.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Biaya Produksi</div>
                            <div class="detail-value">Rp ${biaya.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">${status}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">ID Produksi</div>
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
        window.location.href = '{{ route('backoffice.produksi.index') }}';
    }
</script>
@endpush
