@extends('admin.layouts.app')

@section('title', 'Bahan Baku Produksi')

@section('content')
@php
    // Arrow icon paths with fallback to typcn arrows if iconamoon files are missing
    $arrowUpPath = 'bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg';
    $arrowDownPath = 'bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg';
    $arrowUpIcon = file_exists(public_path($arrowUpPath)) ? asset($arrowUpPath) : asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg');
    $arrowDownIcon = file_exists(public_path($arrowDownPath)) ? asset($arrowDownPath) : asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg');
@endphp
<x-admin.data-table>
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="product" alt="Bahan Baku" size="28" />
            <span>Bahan Baku Produksi</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.bahanbaku.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" size="16" />
                <span>Tambah Bahan Baku</span>
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
                <input type="text" id="searchInput" placeholder="Cari bahan baku..." value="{{ request('search') }}">
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
                    <th data-sort="no" style="width: 6%;">
                        No
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="kode" style="width: 12%;">
                        Kode Bahan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="nama" style="width: 20%;">
                        Nama Bahan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="master" style="width: 20%;">
                        Master Bahan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="satuan" style="width: 10%;">
                        Satuan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="stok" style="width: 12%;">
                        Total Stok
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="harga" style="width: 12%;">
                        Harga
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="status" style="width: 10%;">
                        Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ $arrowUpIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ $arrowDownIcon }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th style="width: 13%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanBakus ?? [] as $index => $bahan)
                <tr data-search="{{ strtolower($bahan->kode_bahan . ' ' . $bahan->nama_bahan . ' ' . ($bahan->masterBahan->nama_bahan ?? '') . ' ' . $bahan->satuan . ' ' . $bahan->status) }}">
                    <td data-sort-value="{{ $bahanBakus->firstItem() + $index }}">{{ $bahanBakus->firstItem() + $index }}</td>
                    <td data-sort-value="{{ strtolower($bahan->kode_bahan) }}">{{ $bahan->kode_bahan }}</td>
                    <td data-sort-value="{{ strtolower($bahan->nama_bahan) }}">{{ $bahan->nama_bahan }}</td>
                    <td data-sort-value="{{ strtolower($bahan->masterBahan->nama_bahan ?? '-') }}">{{ $bahan->masterBahan->nama_bahan ?? '-' }}</td>
                    <td data-sort-value="{{ strtolower($bahan->satuan) }}">{{ $bahan->satuan }}</td>
                    <td data-sort-value="{{ $bahan->stok }}">{{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }}</td>
                    <td data-sort-value="{{ $bahan->harga_per_satuan ?? 0 }}">{{ isset($bahan->harga_per_satuan) ? 'Rp ' . number_format($bahan->harga_per_satuan, 0, ',', '.') : '-' }}</td>
                    <td data-sort-value="{{ $bahan->status === 'aktif' ? 1 : 0 }}">
                        <span class="bolopa-tabel-badge {{ $bahan->status === 'aktif' ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                            {{ $bahan->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 8px 12px;">
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                            onclick="showDetail({{ $bahan->id }}, '{{ addslashes($bahan->kode_bahan) }}', '{{ addslashes($bahan->nama_bahan) }}', '{{ addslashes($bahan->masterBahan->nama_bahan ?? '-') }}', '{{ $bahan->satuan }}', {{ $bahan->stok }}, {{ $bahan->harga_per_satuan ?? 0 }}, '{{ $bahan->status }}')"
                            aria-label="Lihat detail {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="view" alt="Detail" size="16" />
                        </button>
                        <a href="{{ route('backoffice.bahanbaku.edit', $bahan->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="edit" alt="Edit" size="16" />
                        </a>
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                            onclick="confirmDelete({{ $bahan->id }}, '{{ addslashes($bahan->nama_bahan) }}', '{{ route('backoffice.bahanbaku.destroy', $bahan->id) }}')"
                            aria-label="Hapus {{ $bahan->nama_bahan }}">
                            <x-admin.icon name="delete" alt="Hapus" size="16" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="bolopa-tabel-empty">
                        <x-admin.icon name="product" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                        <br>
                        Belum ada data bahan baku
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

<div class="bolopa-tabel-toast" id="bahanBakuToast"></div>
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
            toastSelector: '#bahanBakuToast'
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
            filenamePrefix: 'bahan-baku',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Bahan Baku',
            printBrandSubtitle: 'Daftar stok bahan baku operasional',
            printNotes: 'Catatan: Kolom aksi dihilangkan saat cetak untuk menjaga tata letak laporan.',
            totalLabel: 'Total Bahan Baku',
            notify: notify,
            messages: {
                exportSuccess: 'Data bahan baku berhasil diekspor.',
                exportError: 'Gagal export data bahan baku.',
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
            title: 'Hapus Bahan Baku',
            html: `Apakah Anda yakin ingin menghapus bahan baku <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            // reverseButtons swaps the order of confirm and cancel so delete appears on the left
            reverseButtons: true,
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

    function showDetail(id) {
        const detailUrl = '{{ url("backoffice/bahanbaku") }}' + '/' + id + '/detail';
        Swal.fire({
            title: 'Memuat detail... ',
            didOpen: () => {
                Swal.showLoading();
            },
            showConfirmButton: false
        });

        fetch(detailUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat data');
                return res.json();
            })
            .then(data => {
                const statusLabel = data.status === 'aktif' ? 'Aktif' : 'Nonaktif';
                const harga = data.harga_per_satuan ? Number(data.harga_per_satuan) : 0;
                const stok = data.stok !== undefined ? Number(data.stok) : 0;

                const html = `
                    <div style="text-align:left; max-width:520px;">
                        <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px;">
                            @php
                                $categoryIconPath = 'bolopa/back/images/icon/material-symbols--category-rounded.svg';
                                $fallbackIcon = asset('bolopa/back/images/icon/fluent-mdl2--product.svg');
                                $categoryIcon = file_exists(public_path($categoryIconPath)) ? asset($categoryIconPath) : $fallbackIcon;
                            @endphp
                            <img src="{{ $categoryIcon }}" alt="Bahan" style="width:36px;height:36px;">
                            <div>
                                <div style="font-weight:600; font-size:1.1rem;">${data.nama_bahan}</div>
                                <div style="color:#6c757d; font-size:0.9rem;">${data.satuan} • Kode: ${data.kode_bahan}</div>
                                <div style="color:#6c757d; font-size:0.85rem;">Master: ${data.master ? data.master.nama_bahan : '-'}</div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:10px;">
                            <div style="background:#f8f9fa; padding:10px; border-radius:8px;">
                                <div style="font-size:0.8rem; color:#6c757d;">Harga per Satuan</div>
                                <div style="font-weight:600;">Rp ${Number(harga).toLocaleString('id-ID')}</div>
                            </div>
                            <div style="background:#f8f9fa; padding:10px; border-radius:8px;">
                                <div style="font-size:0.8rem; color:#6c757d;">Total Stok</div>
                                <div style="font-weight:600;">${stok ? Number(stok).toLocaleString('id-ID') : 0}</div>
                            </div>
                        </div>

                        <div style="margin-bottom:10px;">
                            <div style="font-size:0.8rem; color:#6c757d;">Deskripsi</div>
                            <div style="margin-top:6px;">
                                <div style="max-height:140px; overflow:auto; padding:10px; background:#ffffff; border:1px solid #eef0f3; border-radius:6px; line-height:1.4;">
                                    ${data.deskripsi ? data.deskripsi.replace(/\n/g, '<br>') : '<span style="color:#6c757d;">(tidak ada deskripsi)</span>'}
                                </div>
                            </div>
                        </div>

                        <div style="font-size:0.8rem; color:#6c757d; margin-top:6px;">Dibuat: ${data.created_at ?? '-'} • Terakhir diubah: ${data.updated_at ?? '-'}</div>
                    </div>
                `;

                Swal.fire({
                    title: 'Detail Bahan Baku',
                    html: html,
                    width: 600,
                    showCloseButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Tutup',
                    customClass: { popup: 'swal-detail-popup' }
                });
            })
            .catch(err => {
                Swal.fire({ title: 'Error', text: 'Gagal memuat detail bahan.', icon: 'error' });
                console.error(err);
            });
    }

    function resetPagination() {
        window.location.href = '{{ route('backoffice.bahanbaku.index') }}';
    }
</script>
@endpush
