@extends('admin.layouts.app')

@php
    $pageTitle = 'Produk';
@endphp

@section('title', 'Produk Operasional - Cocofarma')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --primary-hover: #3a4fd8;
        --success: #4cc9f0;
        --info: #4895ef;
        --warning: #f72585;
        --danger: #e63946;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: inherit;

    /* Smaller, muted up/down icons that stack vertically */
    .table th i.sort-up,
    .table th i.sort-down {
        color: rgba(0,0,0,0.35);
        font-size: 0.65rem;
        margin-left: 6px;
    }
    }

    html, body {
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
    }


    .sort-icons {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-left: 8px;
        vertical-align: middle;
        line-height: 1;
    }

    .table th .sort-icons i { margin: 0; padding: 0; height: 12px; }

    /* Bring the two arrows closer so they visually connect */
    .table th .sort-icons i.sort-up { margin-bottom: -5px; }
    .table th .sort-icons i.sort-down { margin-top: -5px; }

    .table th.active i.sort-up.active-up,
    .table th.active i.sort-down.active-down {
        color: #000 !important;
        font-size: 0.75rem;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
        overflow: hidden;

    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }

    h1 {
        color: var(--dark);
        font-size: 1.6rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .controls {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        padding: 16px;
        background: var(--light);
        border-radius: var(--border-radius);
    }

    .left-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .right-controls {
        display: flex;
        gap: 10px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        width: 250px;
        transition: var(--transition);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        width: 280px; /* expand on focus to match master-bahan */
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .entries-select {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .entries-select select {
        padding: 8px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        background: white;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary);
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #3aafd9;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #c22c38;
        transform: translateY(-1px);
    }

    .btn-action {
        padding: 5px 10px;
        font-size: 0.8rem;
        margin: 0 2px;
    }

    .btn-info {
        background: var(--info);
        color: white;
    }

    .btn-info:hover {
        background: #3a7fd8;
        transform: translateY(-1px);
    }

    .btn-warning {
        background: var(--warning);
        color: white;
    }

    .btn-warning:hover {
        background: #d61c6a;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        border: 1px solid #6c757d;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
        transform: translateY(-1px);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
        position: relative;
        table-layout: fixed;
        min-width: 100%;
        max-width: none;
    }

    th,
    td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid var(--light-gray);
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 0;
    }

    th:nth-child(3),
    td:nth-child(3) {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
    }

    /* Right-align biaya column */
    th:nth-child(8),
    td:nth-child(8) {
        text-align: right;
    }

    th {
        background-color: var(--light);
        font-weight: 600;
        color: var(--dark);
        position: sticky;
        top: 0;
        z-index: 5;
        cursor: pointer;
        user-select: none;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    th:hover {
        background-color: #e9ecef;
    }

    th i {
        margin-left: 5px;
        font-size: 0.8rem;
        opacity: 0.6;
    }

    th.active i {
        opacity: 1;
    }

    tr {
        transition: var(--transition);
    }

    tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 5px;
        min-width: 120px;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-info {
        color: var(--gray);
        font-size: 0.9rem;
        flex-shrink: 0;
        white-space: nowrap;
        max-width: 30%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pagination-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: flex-end;
        flex-shrink: 0;
        max-width: 70%;
    }

    .pagination-buttons button {
        padding: 6px 10px;
        border: 1px solid var(--light-gray);
        background: white;
        border-radius: var(--border-radius);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .pagination-buttons button:hover {
        background: var(--light);
    }

    .pagination-buttons button.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination-buttons button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .label {
        font-size: .9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
    }

    .value {
        font-weight: 600;
        font-size: 1.05rem;
        color: #343a40;
    }

    .stok-highlight {
        font-size: 1.4rem;
        font-weight: 700;
        color: #198754;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .swal-detail-popup {
    font-family: inherit !important;
    }

    .flex-grow-1 {
        flex-grow: 1;
    }
    /* Center table columns for this page's data table to match requested layout */
    #dataTable th,
    #dataTable td {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-box"></i> Produk Operasional</h1>
        <a href="#" class="btn btn-primary" style="display:none;"><i class="fas fa-plus"></i> Tambah Produk</a>
    </div>

    <div class="controls">
        <div class="left-controls">
            <div class="entries-select">
                <label for="entriesSelect">Tampilkan</label>
                <select id="entriesSelect">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="all">Semua</option>
                </select>
                <span>entri</span>
            </div>

            <!-- search moved to right-controls to match master-bahan layout -->
        </div>

        <div class="right-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari produk...">
                <button type="button" class="clear-btn" onclick="clearSearch()" style="display:none;"><i class="fas fa-times"></i></button>
            </div>

            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="btn btn-success" id="btnExport" style="display:none;"><i class="fas fa-file-export"></i> Export</button>
            @endif
            @if(Auth::check() && Auth::user()->role === 'super_admin')
            <button class="btn btn-primary" id="btnPrint" style="display:none;"><i class="fas fa-print"></i> Print</button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        @php
            $show = 'stok';
            if(isset($stokProduks) && $stokProduks->count() > 0) {
                $show = 'stok';
            } elseif(isset($produksis) && $produksis->count() > 0) {
                $show = 'produksi';
            }
        @endphp

        <table class="table" id="dataTable">
            @if($show === 'stok')
                <thead>
                    <tr>
                        <th data-sort="no" style="width: 6%;">No
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="tanggal" style="width: 12%;">Tanggal
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="nama" style="width: 30%;">Nama
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="jumlah" style="width: 16%;">Jumlah
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="harga" style="width: 14%;">Harga
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="grade" style="width: 12%;">Grade
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th style="width: 22%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokProduks ?? collect() as $sp)
                        <tr data-stok-id="{{ $sp->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($sp->tanggal)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($sp->produk)->nama_produk ?? '-' }}</td>
                            <td>{{ $sp->sisa_stok == floor($sp->sisa_stok) ? number_format($sp->sisa_stok, 0, ',', '.') : number_format($sp->sisa_stok, 2, ',', '.') }}</td>
                            <td>{{ $sp->harga_satuan ? 'Rp ' . number_format($sp->harga_satuan, 0, ',', '.') : '-' }}</td>
                            <td>{{ $sp->grade_display ?? $sp->grade_kualitas ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-info btn-action" onclick="viewDetails({{ $sp->id }}, 'stok')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(Auth::check() && Auth::user()->role === 'super_admin')
                                    <button class="btn btn-warning btn-action" onclick="window.location.href='{{ route('backoffice.produk.stok.edit', $sp->id) }}'" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-action" data-delete-url="{{ route('backoffice.produk.stok.destroy', $sp->id) }}" onclick="deleteStok(this)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">
                                <i class="fas fa-box-open" style="font-size:3rem; margin-bottom:10px;"></i><br>
                                Belum ada data stok produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            @else
                <thead>
                    <tr>
                        <th data-sort="no" style="width: 6%;">No
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="tanggal" style="width: 12%;">Tanggal
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="nama" style="width: 30%;">Nama
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="jumlah" style="width: 18%;">Jumlah
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th data-sort="grade" style="width: 12%;">Grade
                            <span class="sort-icons">
                                <i class="fas fa-sort-up sort-up"></i>
                                <i class="fas fa-sort-down sort-down"></i>
                            </span>
                        </th>
                        <th style="width: 22%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksis ?? collect() as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($p->tanggal_produksi)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($p->produk)->nama_produk ?? '-' }}</td>
                            <td>{{ ($p->jumlah_hasil ?? 0) == floor(($p->jumlah_hasil ?? 0)) ? number_format($p->jumlah_hasil ?? 0, 0, ',', '.') : number_format($p->jumlah_hasil ?? 0, 2, ',', '.') }}</td>
                            <td>{{ $p->grade_display ?? $p->grade_kualitas ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-info btn-action" onclick="viewDetails({{ $p->id }}, 'produksi')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(Auth::check() && Auth::user()->role === 'super_admin')
                                    <button class="btn btn-warning btn-action" onclick="window.location.href='{{ route('backoffice.produksi.edit', $p->id) }}'" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-action" data-delete-url="{{ route('backoffice.produksi.destroy', $p->id) }}" onclick="deleteProduksi(this)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #6c757d;">
                                <i class="fas fa-box-open" style="font-size:3rem; margin-bottom:10px;"></i><br>
                                Belum ada data produksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            @endif
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Simple client-side search/filter for table rows
    (function(){
        const searchInput = document.getElementById('searchInput');
        const entriesSelect = document.getElementById('entriesSelect');

        function filterTable() {
            const q = (searchInput.value || '').toLowerCase();
            const table = document.getElementById('dataTable');
            if(!table) return;
            const rows = table.querySelectorAll('tbody tr');
            let visible = 0;
            const limit = entriesSelect.value === 'all' ? Infinity : parseInt(entriesSelect.value || '10');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.indexOf(q) !== -1;
                if(match && visible < limit) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if(searchInput) searchInput.addEventListener('input', filterTable);
        if(entriesSelect) entriesSelect.addEventListener('change', filterTable);

        window.clearSearch = function(){
            if(searchInput) searchInput.value = '';
            filterTable();
        }

        // Initial run
        document.addEventListener('DOMContentLoaded', filterTable);
    })();

    // Action functions
    function viewDetails(id, type) {
        if (type === 'stok') {
            // Show modal with stok details
            showStokDetails(id);
        } else if (type === 'produksi') {
            // Redirect to produksi detail
            window.location.href = `/backoffice/produksi/${id}`;
        }
    }

    function editStok(id) {
        // For now, show alert since no dedicated stok edit route
        // redirect to stok edit page if route exists, otherwise show alert
        if (typeof window.routeToStokEdit === 'function') {
            routeToStokEdit(id);
            return;
        }
        window.location.href = `/backoffice/produk/stok/${id}/edit`;
    }

    function deleteStok(el) {
        const url = el.getAttribute('data-delete-url');
        Swal.fire({
            title: 'Hapus Stok?',
            text: 'Yakin ingin menghapus stok operasional ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            // Ensure absolute URL
            const absoluteUrl = url.startsWith('http') ? url : (window.location.origin + url.replace(/^\//, '/'));
            fetch(absoluteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            }).then(async res => {
                if (res.ok) {
                    Swal.fire({title: 'Berhasil', text: 'Stok dihapus.', icon: 'success'}).then(()=> location.reload());
                } else {
                    const t = await res.text();
                    Swal.fire({title: 'Gagal', html: t || 'Gagal menghapus stok.', icon: 'error'});
                }
            }).catch(err => Swal.fire({title: 'Gagal', text: err.toString(), icon: 'error'}));
        });
    }

    function editProduksi(id) {
        // Redirect to edit produksi
        window.location.href = `/backoffice/produksi/${id}/edit`;
    }

    function deleteProduksi(el) {
        const url = el.getAttribute('data-delete-url');
        Swal.fire({
            title: 'Hapus Produksi?',
            text: 'Yakin ingin menghapus produksi ini? Pastikan stok produk dan stok operasional sudah kosong.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const absoluteUrl = url.startsWith('http') ? url : (window.location.origin + url.replace(/^\//, '/'));
            fetch(absoluteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            }).then(async res => {
                if (res.ok) {
                    Swal.fire({title: 'Berhasil', text: 'Produksi dihapus.', icon: 'success'}).then(()=> location.reload());
                } else {
                    const t = await res.text();
                    Swal.fire({title: 'Gagal', html: t || 'Gagal menghapus produksi.', icon: 'error'});
                }
            }).catch(err => Swal.fire({title: 'Gagal', text: err.toString(), icon: 'error'}));
        });
    }

    function showStokDetails(id) {
        const row = document.querySelector('tr[data-stok-id="' + id + '"]');
        if (!row) {
            Swal.fire({title: 'Detail', text: 'Detail stok tidak ditemukan.', icon: 'info'});
            return;
        }
        // columns: idx, tanggal, nama, jumlah, harga, grade, aksi
        const cols = row.querySelectorAll('td');
        const tanggal = cols[1] ? cols[1].textContent.trim() : '-';
        const nama = cols[2] ? cols[2].textContent.trim() : '-';
        const jumlah = cols[3] ? cols[3].textContent.trim() : '-';
        const harga = cols[4] ? cols[4].textContent.trim() : '-';
        const grade = cols[5] ? cols[5].textContent.trim() : '-';

        const html = `
            <div style="text-align:left">
                <p><strong>Tanggal:</strong> ${tanggal}</p>
                <p><strong>Produk:</strong> ${nama}</p>
                <p><strong>Jumlah Sisa:</strong> ${jumlah}</p>
                <p><strong>Harga Satuan:</strong> ${harga}</p>
                <p><strong>Grade:</strong> ${grade}</p>
            </div>
        `;

        Swal.fire({
            title: 'Detail Stok',
            html: html,
            width: 600,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Tutup'
        });
    }
</script>
@endpush

@endsection



