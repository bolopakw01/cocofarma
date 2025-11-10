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
                    <th data-sort="no" class="bolopa-align-center bolopa-align-middle bolopa-nowrap">No
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th class="bolopa-align-center bolopa-align-middle">Gambar</th>
                    <th data-sort="kode" class="bolopa-align-left bolopa-align-middle">Kode Produk
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="nama" class="bolopa-align-left bolopa-align-middle">Nama Produk
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="kategori" class="bolopa-align-left bolopa-align-middle">Kategori
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="satuan" class="bolopa-align-left bolopa-align-middle">Satuan
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="harga" class="bolopa-align-right bolopa-align-middle">Harga
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="minimum" class="bolopa-align-right bolopa-align-middle">Min Stok
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th data-sort="status" class="bolopa-align-center bolopa-align-middle">Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                            <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                        </span>
                    </th>
                    <th class="bolopa-align-center bolopa-align-middle">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks ?? [] as $index => $produk)
                    <tr data-search="{{ strtolower($produk->kode_produk.' '.$produk->nama_produk.' '.$produk->kategori.' '.$produk->satuan) }}">
                        <td data-sort-value="{{ ($produks->currentPage() - 1) * $produks->perPage() + $index + 1 }}" class="bolopa-align-center bolopa-align-middle">
                            {{ ($produks->currentPage() - 1) * $produks->perPage() + $index + 1 }}
                        </td>
                        <td class="bolopa-align-center bolopa-align-middle">
                            @php
                                $imagePath = $produk->foto ? public_path('bolopa/pokoknyayangadapadasistem/FotoProduk/' . $produk->foto) : null;
                                $imageExists = $imagePath && file_exists($imagePath);
                            @endphp
                            @if($produk->foto && $imageExists)
                                <img src="{{ asset('bolopa/pokoknyayangadapadasistem/FotoProduk/' . $produk->foto) }}"
                                     alt="{{ $produk->nama_produk }}"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; vertical-align: middle;">
                            @else
                                <div style="width: 50px; height: 50px; background: #6c757d; border-radius: 4px; border: 1px solid #ddd; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; vertical-align: middle;">
                                    {{ Str::upper(Str::substr($produk->nama_produk, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td data-sort-value="{{ strtolower($produk->kode_produk) }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->kode_produk }}</td>
                        <td data-sort-value="{{ strtolower($produk->nama_produk) }}" class="bolopa-align-left bolopa-align-middle">
                            <span class="bolopa-tabel-status-indicator {{ $produk->status ? 'bolopa-tabel-status-active' : 'bolopa-tabel-status-inactive' }}"></span>
                            {{ $produk->nama_produk }}
                        </td>
                        <td data-sort-value="{{ strtolower($produk->kategori) }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->kategori }}</td>
                        <td data-sort-value="{{ strtolower($produk->satuan) }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->satuan }}</td>
                        <td data-sort-value="{{ $produk->harga_jual }}" class="bolopa-align-right bolopa-align-middle">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                        <td data-sort-value="{{ $produk->minimum_stok }}" class="bolopa-align-right bolopa-align-middle">{{ $produk->minimum_stok }}</td>
                        <td data-sort-value="{{ $produk->status === 'aktif' ? 1 : 0 }}" class="bolopa-align-center bolopa-align-middle">
                            <span class="bolopa-tabel-badge {{ $produk->status === 'aktif' ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger' }}">
                                {{ $produk->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="bolopa-tabel-actions bolopa-align-center bolopa-align-middle" style="display: flex; align-items: center; justify-content: center; padding: 15px 12px;">
                            <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                                data-produk-id="{{ $produk->id }}"
                                data-produk-kode="{{ $produk->kode_produk }}"
                                data-produk-nama="{{ $produk->nama_produk }}"
                                data-produk-deskripsi="{{ $produk->deskripsi ?? '' }}"
                                data-produk-foto="{{ $produk->foto ? asset('bolopa/pokoknyayangadapadasistem/FotoProduk/' . $produk->foto) : '' }}"
                                data-produk-kategori="{{ $produk->kategori }}"
                                data-produk-satuan="{{ $produk->satuan }}"
                                data-produk-harga="{{ $produk->harga_jual }}"
                                data-produk-stok="{{ $produk->stok }}"
                                data-produk-minimum="{{ $produk->minimum_stok }}"
                                data-produk-status="{{ $produk->status === 'aktif' ? 'true' : 'false' }}"
                                onclick="showDetailFromData(this)"
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
                        <td colspan="10" class="bolopa-align-center bolopa-align-middle" style="padding:40px;">
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

@push('styles')
<style>
    .swal2-popup-card {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .swal2-popup-card .swal-card {
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(15,23,42,0.08);
        border-radius: 12px;
        margin: 0;
        text-align: left;
        position: relative;
    }

    /* Table column alignments */
    #dataTable th:nth-child(1), #dataTable td:nth-child(1) { text-align: center; } /* No */
    #dataTable th:nth-child(2), #dataTable td:nth-child(2) { text-align: center; } /* Gambar */
    #dataTable th:nth-child(3), #dataTable td:nth-child(3) { text-align: left; } /* Kode Produk */
    #dataTable th:nth-child(4), #dataTable td:nth-child(4) { text-align: left; } /* Nama Produk */
    #dataTable th:nth-child(5), #dataTable td:nth-child(5) { text-align: left; } /* Kategori */
    #dataTable th:nth-child(6), #dataTable td:nth-child(6) { text-align: left; } /* Satuan */
    #dataTable th:nth-child(7), #dataTable td:nth-child(7) { text-align: right; } /* Harga Jual */
    #dataTable th:nth-child(8), #dataTable td:nth-child(8) { text-align: right; } /* Min Stok */
    #dataTable th:nth-child(9), #dataTable td:nth-child(9) { text-align: center; } /* Status */
    #dataTable th:nth-child(10), #dataTable td:nth-child(10) { text-align: center; } /* Aksi */

</style>
@endpush
@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script src="{{ asset('bolopa/back/js/bolopa-export-print.js') }}"></script>
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
            filenamePrefix: 'master-produk',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Master Produk',
            printBrandSubtitle: 'Sistem Manajemen Produk',
            printNotes: 'Catatan: Kolom aksi dihilangkan untuk keperluan cetak. Gambar ditampilkan sebagai placeholder agar tetap konsisten.',
            totalLabel: 'Total Produk',
            notify: notify,
            messages: {
                exportSuccess: 'Data master produk berhasil diekspor.',
                exportError: 'Gagal export data master produk.',
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

    function showDetailFromData(button) {
        const id = button.getAttribute('data-produk-id');
        const kode = button.getAttribute('data-produk-kode');
        const nama = button.getAttribute('data-produk-nama');
        const deskripsi = button.getAttribute('data-produk-deskripsi');
        const fotoUrl = button.getAttribute('data-produk-foto');
        const kategori = button.getAttribute('data-produk-kategori');
        const satuan = button.getAttribute('data-produk-satuan');
        const harga = parseFloat(button.getAttribute('data-produk-harga'));
        const stok = parseInt(button.getAttribute('data-produk-stok'));
        const minimum = parseInt(button.getAttribute('data-produk-minimum'));
        const status = button.getAttribute('data-produk-status') === 'true';

        showDetail(id, kode, nama, deskripsi, fotoUrl, kategori, satuan, harga, stok, minimum, status);
    }

    function showDetail(id, kode, nama, deskripsi, fotoUrl, kategori, satuan, harga, stok, minimum, status) {
        const statusLabel = status ? 'Aktif' : 'Non-Aktif';
        const statusBadgeClass = status ? 'badge-success' : 'badge-danger';
        const statusDotClass = status ? 'dot-on' : 'dot-off';

        // Check if image file actually exists
        const imageExists = fotoUrl && fotoUrl.length > 0;
        const imagePart = imageExists ?
            `<img src="${fotoUrl}" alt="${nama}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">` :
            `<div style="width:100%;height:100%;background:#6c757d;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:72px;">
                ${nama.charAt(0).toUpperCase()}
            </div>`;

        const html = `
            <style>
                :root {
                    --bg:#f5f7fb;
                    --card:#ffffff;
                    --muted:#6b7280;
                    --accent:#2563eb;
                    --success:#16a34a;
                    --danger:#ef4444;
                    --radius:12px;
                    --shadow: 0 6px 18px rgba(15,23,42,0.08);
                    font-family: Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
                }
                .swal-card {background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;max-width:920px;width:100%;margin:0 auto;}
                .swal-card-header {display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #eef2f7;}
                .swal-card-header h1 {font-size:18px;margin:0;}
                .badges {display:flex;gap:8px;align-items:center;}
                .badge {font-size:13px;padding:6px 10px;border-radius:12px;color:#fff;display:inline-flex;align-items:center;gap:8px;font-weight:600;}
                .badge-success {background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
                .badge-danger {background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
                .badge .dot {width:10px;height:10px;border-radius:50%;display:inline-block;flex:0 0 auto;}
                .badge .dot.dot-on {background:#28a745;box-shadow:0 0 8px rgba(40,167,69,0.45);animation:badge-pulse 2s infinite;}
                .badge .dot.dot-off {background:#6c757d;opacity:0.7;box-shadow:none;}
                @keyframes badge-pulse {0%{transform:scale(.9);box-shadow:0 0 0 0 rgba(40,167,69,0.45)}70%{transform:scale(1);box-shadow:0 0 0 8px rgba(40,167,69,0)}100%{transform:scale(.9);box-shadow:0 0 0 0 rgba(40,167,69,0)}}
                .swal-card-body {display:grid;grid-template-columns:280px 1fr;gap:20px;padding:20px 24px;}
                .img-wrap {background:linear-gradient(180deg,#eef2ff,#fff);border-radius:10px;display:flex;align-items:center;justify-content:center;height:220px;border:1px dashed #e6eefc;overflow:hidden;}
                .product-sku {font-size:13px;color:var(--muted);margin-top:4px;text-align:left;}
                .details {display:flex;flex-direction:column;gap:12px;}
                .grid {display:grid;grid-template-columns:repeat(2,1fr);gap:12px;}
                .field-item {display:flex;flex-direction:column;gap:6px;}
                .field {background:#fbfdff;border:1px solid #f1f5f9;padding:12px;border-radius:8px;font-weight:600;color:#212529;}
                .label {font-size:12px;color:var(--muted);text-transform:uppercase;font-weight:500;}
                .desc {background:#fbfdff;border:1px solid #f1f5f9;padding:12px;border-radius:8px;min-height:84px;max-height:180px;overflow:auto;font-size:13px;color:#495057;}
                .desc::-webkit-scrollbar {width:8px;height:8px;}
                .desc::-webkit-scrollbar-thumb {background:#cbd5e1;border-radius:8px;}
                .desc::-webkit-scrollbar-track {background:transparent;}
                .desc {scrollbar-width:thin;scrollbar-color:#cbd5e1 transparent;}
                .swal-card-footer {display:flex;gap:10px;align-items:center;padding:16px 24px;border-top:1px solid #eef2f7;margin-left:0;justify-content:space-between;}
                .btn {border:0;padding:10px 14px;border-radius:8px;cursor:pointer;font-weight:600;}
                .btn.secondary {background:#6c757d;color:#fff;}
                .meta {color:var(--muted);font-size:13px;}
                @media (max-width:740px) {
                    .swal-card-body {grid-template-columns:1fr;}
                    .img-wrap {height:180px;}
                    .grid {grid-template-columns:1fr;}
                }
            </style>

            <div class="swal-card" role="region" aria-labelledby="judul-produk">
                <div class="swal-card-header">
                    <h1 id="judul-produk">Detail Produk</h1>
                    <div class="badges">
                        <span class="badge ${statusBadgeClass} d-inline-flex align-items-center">
                            <span class="dot ${statusDotClass}" aria-hidden="true"></span>${statusLabel}
                        </span>
                    </div>
                </div>

                <div class="swal-card-body">
                    <div>
                        <div class="img-wrap" aria-hidden="true">
                            ${imagePart}
                        </div>
                        <div>
                            <div class="product-sku">${kode}</div>
                        </div>
                    </div>

                    <div class="details">
                        <div class="grid">
                            <div class="field-item">
                                <div class="label">Nama Produk</div>
                                <div class="field">${nama}</div>
                            </div>
                            <div class="field-item">
                                <div class="label">Kategori</div>
                                <div class="field">${kategori}</div>
                            </div>
                            <div class="field-item">
                                <div class="label">Satuan</div>
                                <div class="field">${satuan}</div>
                            </div>
                            <div class="field-item">
                                <div class="label">Harga</div>
                                <div class="field">Rp ${Number(harga).toLocaleString('id-ID')}</div>
                            </div>
                            <div class="field-item">
                                <div class="label">Minimum Stok</div>
                                <div class="field">${minimum}</div>
                            </div>
                            <div class="field-item">
                                <div class="label">Stok</div>
                                <div class="field">${stok}</div>
                            </div>
                        </div>

                        <div class="field-item">
                            <div class="label">Deskripsi</div>
                            <div class="desc">${deskripsi || '<em style="color:#6c757d;">Tidak ada deskripsi</em>'}</div>
                        </div>
                    </div>
                </div>

                <div class="swal-card-footer">
                    <div class="meta">Dibuat: <strong style="margin-left:6px">${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</strong></div>
                    <button type="button" class="btn secondary" onclick="Swal.close()">Tutup</button>
                </div>
            </div>
        `;

        Swal.fire({
            html: html,
            width: Math.min(920, window.innerWidth * 0.95),
            showCloseButton: false,
            showConfirmButton: false,
            customClass: {
                popup: 'swal2-popup-card'
            },
            backdrop: true
        });
    }

    function resetPagination() {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // Export & print handled via shared helper
</script>
@endpush
