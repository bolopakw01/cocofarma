@extends('admin.layouts.app')

@php
    $pageTitle = 'Master Bahan Baku';
@endphp

@section('title', 'Master Bahan Baku - Cocofarma')

@section('content')
<x-admin.data-table id="master-bahan-table">
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="product" alt="Master Bahan" size="28" />
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
                            onclick="showDetail({{ $bahan->id }})"
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
                        <x-admin.icon name="product" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
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
<script src="{{ asset('bolopa/back/js/bolopa-export-print.js') }}"></script>
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
            filenamePrefix: 'master-bahan',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Master Bahan',
            printBrandSubtitle: 'Daftar master bahan baku',
            printNotes: 'Catatan: Kolom aksi dihilangkan pada hasil cetak untuk menjaga kerapian laporan.',
            totalLabel: 'Total Bahan',
            notify: notify,
            messages: {
                exportSuccess: 'Data master bahan berhasil diekspor.',
                exportError: 'Gagal export data master bahan.',
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
            html: `Apakah Anda yakin ingin menghapus bahan <strong>${nama}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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
        const detailUrl = '{{ url("backoffice/master-bahan") }}' + '/' + id + '/detail';
        Swal.fire({
            title: 'Memuat detail...',
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
                const statusLabel = data.status === 'aktif' ? 'Aktif' : 'Non-Aktif';
                const harga = data.harga_per_satuan ? Number(data.harga_per_satuan) : 0;
                const stokMin = data.stok_minimum !== null ? data.stok_minimum : '';

                let bahanListHtml = '';
                if (data.bahan_list && data.bahan_list.length) {
                    bahanListHtml = '<ul style="padding-left:16px;margin:6px 0 0 0;">';
                    data.bahan_list.slice(0,5).forEach(b => {
                        bahanListHtml += `<li>${b.nama_bahan} <small style="color:#6c757d;">(stok: ${Number(b.stok).toLocaleString('id-ID')})</small></li>`;
                    });
                    if (data.bahan_list.length > 5) {
                        bahanListHtml += `<li>...dan ${data.bahan_list.length - 5} lainnya</li>`;
                    }
                    bahanListHtml += '</ul>';
                } else {
                    bahanListHtml = '<div style="color:#6c757d;">Tidak ada bahan operasional terkait.</div>';
                }

                // Toggle control for Contoh Bahan Operasional (show/hide)
                // Label changed per UX: show as "Bahan Oprasional" with an arrow that toggles
                const contohToggleHtml = `
                    <div>
                        <button id="toggleBahanBtn" type="button" style="background:transparent;border:none;color:#4361ee;cursor:pointer;padding:0;margin-top:6px;" 
                            onclick="(function(){var el=document.getElementById('contoh-bahan-list');var btn=document.getElementById('toggleBahanBtn'); if(!el) return; if(el.style.display==='none'){el.style.display='block'; btn.innerText='Bahan Oprasional ▾';} else {el.style.display='none'; btn.innerText='Bahan Oprasional ▸';}})()">
                            Bahan Oprasional ▸
                        </button>
                    </div>
                `;

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
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:10px;">
                            <div style="background:#f8f9fa; padding:10px; border-radius:8px;">
                                <div style="font-size:0.8rem; color:#6c757d;">Harga per Satuan</div>
                                <div style="font-weight:600;">Rp ${Number(harga).toLocaleString('id-ID')}</div>
                            </div>
                            <div style="background:#f8f9fa; padding:10px; border-radius:8px;">
                                <div style="font-size:0.8rem; color:#6c757d;">Stok Minimum</div>
                                <div style="font-weight:600;">${stokMin !== '' ? Number(stokMin).toLocaleString('id-ID') : '-'}</div>
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

                        <div style="display:flex; gap:12px; align-items:center; margin-bottom:10px;">
                            <div style="flex:1">
                                <div style="font-size:0.8rem; color:#6c757d;">Jumlah Bahan Operasional</div>
                                <div style="font-weight:600;">${data.bahan_count}</div>
                            </div>
                            <div style="flex:1">
                                <div style="font-size:0.8rem; color:#6c757d;">Total Stok</div>
                                <div style="font-weight:600;">${data.total_stok ? Number(data.total_stok).toLocaleString('id-ID') : 0}</div>
                            </div>
                        </div>

                        <div style="margin-bottom:8px;">
                            ${contohToggleHtml}
                            <div id="contoh-bahan-list" style="display:none; margin-top:6px;">
                                ${bahanListHtml}
                            </div>
                        </div>

                        <div style="font-size:0.8rem; color:#6c757d; margin-top:6px;">Dibuat: ${data.created_at ?? '-'} • Terakhir diubah: ${data.updated_at ?? '-'}</div>
                    </div>
                `;

                Swal.fire({
                    title: 'Detail Master Bahan',
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
        window.location.href = '{{ route('backoffice.master-bahan.index') }}';
    }
</script>
@endpush
