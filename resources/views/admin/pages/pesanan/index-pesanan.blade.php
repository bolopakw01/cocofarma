@extends('admin.layouts.app')

@section('title', 'Pesanan')

@section('content')
<x-admin.data-table>
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="cart" alt="Pesanan" size="28" />
            <span>Pesanan</span>
        </div>
        <div class="bolopa-tabel-header-actions">
            <a href="{{ route('backoffice.pesanan.create') }}" class="bolopa-tabel-btn bolopa-tabel-btn-primary">
                <x-admin.icon name="plus" alt="Tambah" size="16" />
                <span>Tambah Pesanan</span>
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
                <input type="text" id="searchInput" placeholder="Cari pesanan..." value="{{ request('search') }}">
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
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="kode_pesanan" style="width: 12%;">
                        Kode Pesanan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="tanggal_pesanan" style="width: 10%;">
                        Tanggal
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="nama_pelanggan" style="width: 18%;">
                        Pelanggan
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="total_harga" style="width: 12%;">
                        Total
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th data-sort="status" style="width: 10%;">
                        Status
                        <span class="bolopa-tabel-sort-wrap">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
                            <img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
                        </span>
                    </th>
                    <th style="width: 13%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans ?? [] as $index => $pesanan)
                <tr data-search="{{ strtolower($pesanan->kode_pesanan . ' ' . $pesanan->nama_pelanggan . ' ' . $pesanan->no_telepon . ' ' . $pesanan->status_label) }}">
                    <td data-sort-value="{{ ($pesanans->currentPage() - 1) * $pesanans->perPage() + $index + 1 }}">{{ ($pesanans->currentPage() - 1) * $pesanans->perPage() + $index + 1 }}</td>
                    <td data-sort-value="{{ strtolower($pesanan->kode_pesanan) }}">{{ $pesanan->kode_pesanan }}</td>
                    <td data-sort-value="{{ $pesanan->tanggal_pesanan->format('Y-m-d') }}">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</td>
                    <td data-sort-value="{{ strtolower($pesanan->nama_pelanggan) }}">
                        <div>{{ $pesanan->nama_pelanggan }}</div>
                        <div style="font-size: 0.8rem; color: #6c757d;">{{ $pesanan->no_telepon }}</div>
                    </td>
                    <td data-sort-value="{{ $pesanan->total_harga }}">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    <td data-sort-value="{{ $pesanan->status }}">
                        <span class="bolopa-tabel-badge {{ $pesanan->status == 'pending' ? 'bolopa-tabel-badge-warning' : ($pesanan->status == 'diproses' ? 'bolopa-tabel-badge-info' : ($pesanan->status == 'selesai' ? 'bolopa-tabel-badge-success' : 'bolopa-tabel-badge-danger')) }}">
                            {{ $pesanan->status_label }}
                        </span>
                    </td>
                    <td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; padding: 16px 12px;">
                        <a href="{{ route('backoffice.pesanan.show', $pesanan->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action" aria-label="Lihat detail {{ $pesanan->kode_pesanan }}">
                            <x-admin.icon name="view" alt="Detail" size="16" />
                        </a>
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-success bolopa-tabel-btn-action"
                            onclick="openStatusModal({{ $pesanan->id }}, '{{ $pesanan->status }}', '{{ $pesanan->kode_pesanan }}')"
                            aria-label="Update status {{ $pesanan->kode_pesanan }}">
                            <x-admin.icon name="switch" alt="Update Status" size="16" />
                        </button>
                        <a href="{{ route('backoffice.pesanan.edit', $pesanan->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $pesanan->kode_pesanan }}">
                            <x-admin.icon name="edit" alt="Edit" size="16" />
                        </a>
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
                            onclick="confirmDelete({{ $pesanan->id }}, '{{ $pesanan->kode_pesanan }}', '{{ route('backoffice.pesanan.destroy', $pesanan->id) }}')"
                            aria-label="Hapus {{ $pesanan->kode_pesanan }}">
                            <x-admin.icon name="delete" alt="Hapus" size="16" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="bolopa-tabel-empty">
                        <x-admin.icon name="order" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                        <br>
                        Tidak ada data pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-slot>

    @if(isset($pesanans) && ($pesanans->hasPages() || $pesanans->total() > 0))
        <x-slot name="footer">
            <div class="bolopa-tabel-pagination">
                <div class="bolopa-tabel-pagination-info">
                    @if($pesanans->total() > 0)
                        Menampilkan {{ $pesanans->firstItem() ?? 0 }} sampai {{ $pesanans->lastItem() ?? 0 }} dari {{ $pesanans->total() ?? 0 }} entri
                    @else
                        Tidak ada entri yang ditampilkan
                    @endif
                </div>
                <div class="bolopa-tabel-pagination-buttons">
                    @if($pesanans->hasPages())
                        @if($pesanans->onFirstPage())
                            <button type="button" disabled aria-label="Halaman sebelumnya">
                                <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                            </button>
                        @else
                            <a href="{{ $pesanans->previousPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman sebelumnya">
                                    <x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
                                </button>
                            </a>
                        @endif

                        @foreach($pesanans->getUrlRange(1, $pesanans->lastPage()) as $page => $url)
                            @if($page == $pesanans->currentPage())
                                <button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                    <button type="button">{{ $page }}</button>
                                </a>
                            @endif
                        @endforeach

                        @if($pesanans->hasMorePages())
                            <a href="{{ $pesanans->nextPageUrl() . (request('per_page') ? '&per_page=' . request('per_page') : '') }}">
                                <button type="button" aria-label="Halaman selanjutnya">
                                    <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                                </button>
                            </a>
                        @else
                            <button type="button" disabled aria-label="Halaman selanjutnya">
                                <x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
                            </button>
                        @endif
                    @elseif($pesanans->perPage() >= 1000 && $pesanans->total() > 0)
                        <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
                    @endif
                </div>
            </div>
        </x-slot>
    @endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="pesananToast"></div>
@endsection

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableApi = window.initBolopaTable({
            tableSelector: '#dataTable',
            entriesSelector: '#entriesSelect',
            searchInputSelector: '#searchInput',
            toastSelector: '#pesananToast'
        });

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            if (searchInput.value) {
                searchInput.dispatchEvent(new Event('input'));
            }
        }

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
                try {
                    exportTableData();
                    tableApi && tableApi.showToast('Data pesanan berhasil diexport.', 'success');
                } catch (error) {
                    console.error('Export error:', error);
                    tableApi && tableApi.showToast('Gagal export data pesanan.', 'error');
                }
            });
        }

        const printBtn = document.getElementById('btnPrint');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                try {
                    tableApi && tableApi.showToast('Membuka tampilan print...', 'info');
                    printTableData();
                } catch (error) {
                    console.error('Print error:', error);
                    tableApi && tableApi.showToast('Gagal membuka tampilan print.', 'error');
                }
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

    function confirmDelete(id, kode, url) {
        Swal.fire({
            title: 'Hapus Pesanan',
            html: `Apakah Anda yakin ingin menghapus pesanan <strong>${kode}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
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

    // Status Modal
    function openStatusModal(id, currentStatus, kodePesanan) {
        const statusOptions = {
            'pending': 'Pending',
            'diproses': 'Diproses',
            'selesai': 'Selesai',
            'dibatalkan': 'Dibatalkan'
        };

        let optionsHtml = '';
        for (const [value, label] of Object.entries(statusOptions)) {
            optionsHtml += `<option value="${value}" ${value === currentStatus ? 'selected' : ''}>${label}</option>`;
        }

        Swal.fire({
            title: 'Update Status Pesanan',
            html: `
                <div style="text-align: left; padding: 20px;">
                    <p><strong>Kode Pesanan:</strong> ${kodePesanan}</p>
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600;">Status Baru:</label>
                    <select id="status" class="swal2-input" style="width: 100%; padding: 10px;">
                        ${optionsHtml}
                    </select>

                    <div style="margin-top:12px; font-size:0.92rem; color:#495057;">
                        <strong>Informasi Pengelolaan Stok:</strong>
                        <ul style="margin:6px 0 0 18px; padding:0; line-height:1.4;">
                            <li><strong>Diproses:</strong> Stok akan dikurangi dan ditahan</li>
                            <li><strong>Selesai:</strong> Stok berkurang permanen</li>
                            <li><strong>Dibatalkan:</strong> Stok akan dikembalikan</li>
                        </ul>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                const status = document.getElementById('status').value;
                return { status: status };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(id, result.value.status);
            }
        });
    }

    function updateStatus(id, status) {
        const urlTemplate = '{{ route('backoffice.pesanan.update-status', ['pesanan' => '__ID__']) }}';
        const requestUrl = urlTemplate.replace('__ID__', id);

        fetch(requestUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const errorMessage = data && data.message ? data.message : 'Gagal update status';
                throw new Error(errorMessage);
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Status pesanan berhasil diupdate',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Gagal!', data.message || 'Gagal update status', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', error.message || 'Terjadi kesalahan', 'error');
        });
    }

    function exportTableData() {
        const table = document.getElementById('dataTable');
        if (!table) {
            throw new Error('Tabel data pesanan tidak ditemukan.');
        }

        const rows = Array.from(table.querySelectorAll('tr'));
        if (!rows.length) {
            throw new Error('Tidak ada data untuk diexport.');
        }

        const visibleRows = rows.filter((row) => row.style.display !== 'none');
        const headerRow = table.querySelector('thead tr');
        let actionColumnIndex = -1;
        if (headerRow) {
            const headerCells = Array.from(headerRow.children);
            actionColumnIndex = headerCells.findIndex((cell) => cell.textContent.trim().toLowerCase() === 'aksi');
        }

        const csvLines = visibleRows.map((row) => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells.map((cell, index) => {
                if (actionColumnIndex >= 0 && index === actionColumnIndex && cell.colSpan <= 1) {
                    return null;
                }

                const text = (cell.innerText || cell.textContent || '')
                    .replace(/\s+/g, ' ')
                    .replace(/"/g, '""')
                    .trim();
                return '"' + text + '"';
            }).filter((value) => value !== null).join(',');
        });

        const csvContent = '\uFEFF' + csvLines.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

        const timestamp = new Date().toISOString().replace(/[-:T]/g, '').slice(0, 14);
        const filename = `pesanan-export-${timestamp}.csv`;

        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);
    }

    function printTableData() {
        const tableContainer = document.querySelector('.bolopa-tabel-table-responsive');
        const table = document.getElementById('dataTable');
        if (!tableContainer || !table) {
            throw new Error('Tabel data pesanan tidak ditemukan.');
        }

        // Clone full table (include all rows, even those currently hidden by filters)
        const clonedTable = table.cloneNode(true);

        const headerRow = clonedTable.querySelector('thead tr');
        let actionColumnIndex = -1;
        if (headerRow) {
            const headerCells = Array.from(headerRow.children);
            actionColumnIndex = headerCells.findIndex((cell) => cell.textContent.trim().toLowerCase() === 'aksi');
            if (actionColumnIndex >= 0) {
                headerCells[actionColumnIndex].remove();
            }
        }

        if (actionColumnIndex >= 0) {
            clonedTable.querySelectorAll('tbody tr').forEach((row) => {
                const cells = row.children;
                if (cells[actionColumnIndex]) {
                    cells[actionColumnIndex].remove();
                }
            });
        }

        // Remove sort icons and any interactive controls inside the cloned table so print is clean
        clonedTable.querySelectorAll('img.bolopa-tabel-sort-icon, img.bolopa-tabel-sort-up, img.bolopa-tabel-sort-down, .bolopa-tabel-sort-icons, .clear-btn, .bolopa-tabel-search-box').forEach(el => el.remove());

        const printWindow = window.open('', '_blank', 'width=1024,height=768');
        if (!printWindow) {
            throw new Error('Popup print diblokir oleh browser.');
        }

        const styles = `
            <style>
                /* Print sizing and margins: prefer A4 portrait but let browser decide if different */
                @page { size: A4 portrait; margin: 12mm; }
                /* Base */
                body { font-family: 'Poppins', Arial, sans-serif; margin: 0; color: #1f2937; }
                .print-shell { padding: 12px 14px; }
                .brand { display:flex; align-items:center; gap:12px; margin-bottom:6px; }
                h1 { font-size: 16px; margin: 4px 0 8px 0; }
                .meta { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; color:#374151; font-size:12px; }
                /* Table */
                table { width: 100%; border-collapse: collapse; table-layout: auto; font-size:11px; }
                th, td { border: 2px solid #d1d5db; padding: 8px 10px; text-align: left; vertical-align: middle; }
                th { background: #f8fafc; font-weight: 700; color:#111827; }
                tbody tr:nth-child(even) td { background: #fbfdfe; }
                .small { font-size:11px; color:#6b7280; }
                .notes { margin-top:12px; font-size:12px; color:#374151; }

                /* Make sure long text wraps and cells don't force overflow */
                td, th { white-space: normal; word-break: break-word; }

                /* Print pagination safety: avoid cutting rows across pages */
                @media print {
                    .no-print { display: none !important; }
                    table { page-break-inside: auto; }
                    thead { display: table-header-group; }
                    tfoot { display: table-footer-group; }
                    tr { page-break-inside: avoid; page-break-after: auto; }
                    /* Reduce font slightly for very wide tables */
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        `;

        const totalCount = table.querySelectorAll('tbody tr').length;
        const metaInfo = `
            <div class="meta">
                <div>
                    <strong>Dicetak oleh:</strong> {{ auth()->user()->name ?? 'Administrator' }}<br>
                    <span class="small">Tanggal cetak: ${new Date().toLocaleString('id-ID')}</span>
                </div>
                <div style="text-align:right;">
                    <div><strong>Total Pesanan:</strong> ${totalCount}</div>
                    <div class="small">Sumber data: Sistem Bolopa</div>
                </div>
            </div>
        `;

        printWindow.document.write(`
            <html>
                <head>
                    <title>Data Pesanan</title>
                    ${styles}
                </head>
                <body>
                    <div class="print-shell">
                        <div class="brand">
                            <div>
                                <div style="font-weight:700; font-size:16px;">Cocofarma — Daftar Pesanan</div>
                                <div class="small">Laporan ringkas pesanan</div>
                            </div>
                        </div>
                        ${metaInfo}
                        ${clonedTable.outerHTML}
                        <div class="notes">
                            Catatan: Kolom aksi dihilangkan untuk keperluan cetak. Untuk detail pesanan, buka halaman detail tiap pesanan.
                        </div>
                    </div>
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.addEventListener('afterprint', () => {
            printWindow.close();
        });
    }

    function resetPagination() {
        window.location.href = '{{ route('backoffice.pesanan.index') }}';
    }
</script>
@endpush
