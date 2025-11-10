@extends('admin.layouts.app')

@section('title', 'Transaksi Penjualan - Cocofarma')

@section('content')
<x-admin.data-table>
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="transaction" alt="Transaksi" size="28" />
            <span>Transaksi Penjualan</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.transaksi.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" size="16" />
                <span>Tambah Transaksi</span>
            </a>
        </div>
    </x-slot>

    <x-slot name="controls">
        <div class="bolopa-tabel-left-controls">
            <div class="bolopa-tabel-entries">
                <label for="entriesSelect">Tampilkan</label>
                <select id="entriesSelect" class="bolopa-tabel-entries-select">
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
                <input type="text" id="searchInput" placeholder="Cari transaksi..." value="{{ request('search') }}">
            </div>

            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="bolopa-tabel-btn bolopa-tabel-btn-success" id="btnExport">
                <x-admin.icon name="export" alt="Export" size="18" /> Export
            </button>
            @endif
            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="bolopa-tabel-btn bolopa-tabel-btn-primary" id="btnPrint">
                <x-admin.icon name="print" alt="Print" size="18" /> Print
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
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th data-sort="kode_transaksi" style="width: 15%;">
                        Kode
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th data-sort="tanggal_transaksi" style="width: 12%;">
                        Tanggal
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th data-sort="jenis_transaksi" style="width: 12%;">
                        Tipe
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th data-sort="keterangan" style="width: 22%;">
                        Keterangan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th data-sort="total" style="width: 10%;">
                        Total
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="Sort naik" class="bolopa-tabel-sort-icon sort-up">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="Sort turun" class="bolopa-tabel-sort-icon sort-down">
                        </span>
                    </th>
                    <th style="width: 12%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis ?? [] as $index => $transaksi)
                <tr data-search="{{ strtolower(($transaksi->kode_transaksi ?? $transaksi->nomor_transaksi) . ' ' . (is_object($transaksi->tanggal_transaksi) ? $transaksi->tanggal_transaksi->format('d/m/Y') : $transaksi->tanggal_transaksi) . ' ' . ucfirst($transaksi->jenis_transaksi ?? $transaksi->tipe_transaksi ?? 'n/a') . ' ' . ($transaksi->keterangan ?? ($transaksi->nama_pelanggan ?? $transaksi->nama_supplier ?? '-')) . ' Rp ' . number_format($transaksi->total ?? $transaksi->total_amount ?? 0, 0, ',', '.')) }}">
                    <td data-sort-value="{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}</td>
                    <td data-sort-value="{{ $transaksi->kode_transaksi ?? $transaksi->nomor_transaksi }}">
                        <div style="font-weight: 600; color: var(--primary);">{{ $transaksi->kode_transaksi ?? $transaksi->nomor_transaksi }}</div>
                    </td>
                    <td data-sort-value="{{ is_object($transaksi->tanggal_transaksi) ? $transaksi->tanggal_transaksi->format('Ymd') : str_replace('/', '', $transaksi->tanggal_transaksi) }}">
                        <div style="font-size: 0.85rem; color: var(--gray);">{{ is_object($transaksi->tanggal_transaksi) ? $transaksi->tanggal_transaksi->format('d/m/Y') : $transaksi->tanggal_transaksi }}</div>
                    </td>
                    <td data-sort-value="{{ ucfirst($transaksi->jenis_transaksi ?? $transaksi->tipe_transaksi ?? 'n/a') }}">{{ ucfirst($transaksi->jenis_transaksi ?? $transaksi->tipe_transaksi ?? 'n/a') }}</td>
                    <td data-sort-value="{{ $transaksi->keterangan ?? ($transaksi->nama_pelanggan ?? $transaksi->nama_supplier ?? '-') }}">{{ $transaksi->keterangan ?? ($transaksi->nama_pelanggan ?? $transaksi->nama_supplier ?? '-') }}</td>
                    <td data-sort-value="{{ $transaksi->total ?? $transaksi->total_amount ?? 0 }}">
                        <div style="font-weight: 600; color: var(--success);">Rp {{ number_format($transaksi->total ?? $transaksi->total_amount ?? 0, 0, ',', '.') }}</div>
                    </td>
                    <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                        <a href="{{ route('backoffice.transaksi.show', $transaksi->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action">
                            <x-admin.icon name="view" alt="Lihat" size="16" />
                        </a>
                        <a href="#" class="bolopa-tabel-btn bolopa-tabel-btn-secondary bolopa-tabel-btn-action">
                            <x-admin.icon name="print" alt="Print" size="16" />
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="bolopa-tabel-empty">
                        <x-admin.icon name="transaction" alt="Tidak ada data" size="48" style="color: #6c757d; margin-bottom: 10px;" />
                        <br>
                        Belum ada data transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($transaksis) && ($transaksis->hasPages() || $transaksis->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($transaksis->total() > 0)
                        Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($transaksis->hasPages())
                        @if($transaksis->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $transaksis->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                            @if($page == $transaksis->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($transaksis->hasMorePages())
                            <a href="{{ $transaksis->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($transaksis->perPage() >= 1000 && $transaksis->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="transaksiToast"></div>
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
            toastSelector: '#transaksiToast'
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
            filenamePrefix: 'transaksi',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Transaksi',
            printBrandSubtitle: 'Rekap transaksi penjualan',
            printNotes: 'Catatan: Kolom aksi tidak disertakan pada cetak.',
            totalLabel: 'Total Transaksi',
            notify: notify,
            messages: {
                exportSuccess: 'Data transaksi berhasil diekspor.',
                exportError: 'Gagal export data transaksi.',
                printInfo: 'Membuka tampilan print...',
                printError: 'Gagal membuka tampilan print.'
            }
        });
    });

    function resetPagination() {
        window.location.href = '{{ route('backoffice.transaksi.index') }}';
    }
</script>
@endpush
