@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;

    $pageTitle = 'Produk';
@endphp

@section('title', 'Produk Operasional - Cocofarma')

@section('content')
<x-admin.data-table id="produk-operasional-table">
    <x-slot name="header">
        <div class="bolopa-tabel-header-title">
            <x-admin.icon name="product" alt="Produk Operasional" size="28" />
            <span>Produk Operasional</span>
        </div>
    </x-slot>

    <x-slot name="controls">
        <div class="bolopa-tabel-left-controls">
            <div class="bolopa-tabel-entries-select">
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
        </div>

        <div class="bolopa-tabel-right-controls">
            <div class="bolopa-tabel-search-box">
                <x-admin.icon name="search" alt="Cari" size="16" />
                <input type="text" id="searchInput" placeholder="Cari produk...">
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
        @php
            $hasMaster = isset($masterProduks) && $masterProduks->count() > 0;
            $hasStok = isset($stokProduks) && $stokProduks->count() > 0;
            $hasProduksi = isset($produksis) && $produksis->count() > 0;
            $show = $hasMaster ? 'master' : ($hasStok ? 'stok' : ($hasProduksi ? 'produksi' : 'master'));
        @endphp

        @if($show === 'master')
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th data-sort="no" class="bolopa-align-center bolopa-align-middle bolopa-nowrap">Nomor
                            <span class="bolopa-tabel-sort-wrap">
                                <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                            </span>
                        </th>
                        <th class="bolopa-align-center bolopa-align-middle">Gambar</th>
                        <th data-sort="nama" class="bolopa-align-left bolopa-align-middle">Nama
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
                        <th data-sort="grade" class="bolopa-align-left bolopa-align-middle">Grade
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
                        <th data-sort="stok" class="bolopa-align-center bolopa-align-middle">Stok
                            <span class="bolopa-tabel-sort-wrap">
                                <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                            </span>
                        </th>
                        <th class="bolopa-align-center bolopa-align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($masterProduks ?? collect() as $produk)
                        <tr data-search="{{ strtolower($produk->nama_produk.' '.$produk->kategori.' '.$produk->satuan.' '.$produk->grade_display.' '.(($produk->total_operasional_stok ?? 0) > 0 ? 'ada stok' : 'habis')) }}" data-produk-id="{{ $produk->id }}">
                            <td data-sort-value="{{ $loop->iteration }}" class="bolopa-align-center bolopa-align-middle">
                                {{ $loop->iteration }}
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
                            <td data-sort-value="{{ strtolower($produk->nama_produk) }}" class="bolopa-align-left bolopa-align-middle">
                                <span class="bolopa-tabel-status-indicator bolopa-tabel-status-active"></span>
                                {{ $produk->nama_produk }}
                            </td>
                            <td data-sort-value="{{ strtolower($produk->kategori) }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->kategori }}</td>
                            <td data-sort-value="{{ strtolower($produk->satuan) }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->satuan }}</td>
                            <td data-sort-value="{{ strtolower($produk->grade_kualitas ?? '') }}" class="bolopa-align-left bolopa-align-middle">{{ $produk->grade_display }}</td>
                            <td data-sort-value="{{ $produk->harga_jual }}" class="bolopa-align-right bolopa-align-middle">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td data-sort-value="{{ $produk->total_operasional_stok ?? $produk->stokProduks->sum('sisa_stok') }}" class="bolopa-align-center bolopa-align-middle">
                                @php
                                    $totalStok = $produk->total_operasional_stok ?? $produk->stokProduks->sum('sisa_stok');
                                @endphp
                                <span class="{{ $totalStok > 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                    {{ $totalStok == floor($totalStok) ? number_format($totalStok, 0, ',', '.') : number_format($totalStok, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="bolopa-tabel-actions bolopa-align-center bolopa-align-middle" style="display: flex; align-items: center; justify-content: center; padding: 15px 12px;">
                                <button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action"
                                    onclick="viewMasterProductDetails({{ $produk->id }})"
                                    aria-label="Lihat detail {{ $produk->nama_produk }}">
                                    <x-admin.icon name="view" alt="Detail" size="16" />
                                </button>
                                @if(Auth::check() && Auth::user()->role === 'super_admin')
                                <button class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action"
                                    onclick="window.location.href='{{ route('backoffice.master-produk.edit', $produk->id) }}'"
                                    aria-label="Edit {{ $produk->nama_produk }}">
                                    <x-admin.icon name="edit" alt="Edit" size="16" />
                                </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="bolopa-align-center bolopa-align-middle" style="padding:40px;">
                                <x-admin.icon name="product" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
                                <br>
                                Tidak ada data produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="table" id="dataTable">
                @if($show === 'stok')
                    <thead>
                        <tr>
                            <th data-sort="no" class="bolopa-align-center bolopa-align-middle bolopa-nowrap">No
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="tanggal" class="bolopa-align-center bolopa-align-middle">Tanggal
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="nama" class="bolopa-align-left bolopa-align-middle">Nama
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="jumlah" class="bolopa-align-right bolopa-align-middle">Jumlah
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
                            <th data-sort="grade" class="bolopa-align-center bolopa-align-middle">Grade
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th class="bolopa-align-center bolopa-align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokProduks ?? collect() as $sp)
                            <tr data-stok-id="{{ $sp->id }}">
                                <td class="bolopa-align-center bolopa-align-middle">{{ $loop->iteration }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">{{ optional($sp->tanggal)->format('d/m/Y') ?? '-' }}</td>
                                <td class="bolopa-align-left bolopa-align-middle">{{ optional($sp->produk)->nama_produk ?? '-' }}</td>
                                <td class="bolopa-align-right bolopa-align-middle">{{ $sp->sisa_stok == floor($sp->sisa_stok) ? number_format($sp->sisa_stok, 0, ',', '.') : number_format($sp->sisa_stok, 2, ',', '.') }}</td>
                                <td class="bolopa-align-right bolopa-align-middle">{{ $sp->harga_satuan ? 'Rp ' . number_format($sp->harga_satuan, 0, ',', '.') : '-' }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">{{ $sp->grade_display ?? $sp->grade_kualitas ?? '-' }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">
                                    <div class="actions">
                                        <button type="button" class="btn btn-info btn-action" onclick="viewDetails({{ $sp->id }}, 'stok')" title="Lihat Detail">
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
                                <td colspan="7" class="bolopa-align-center bolopa-align-middle" style="padding: 40px; color: #6c757d;">
                                    <i class="fas fa-box-open" style="font-size:3rem; margin-bottom:10px;"></i><br>
                                    Belum ada data stok produk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th data-sort="no" class="bolopa-align-center bolopa-align-middle bolopa-nowrap">No
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="tanggal" class="bolopa-align-center bolopa-align-middle">Tanggal
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="nama" class="bolopa-align-left bolopa-align-middle">Nama
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="jumlah" class="bolopa-align-right bolopa-align-middle">Jumlah
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th data-sort="grade" class="bolopa-align-center bolopa-align-middle">Grade
                                <span class="bolopa-tabel-sort-wrap">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" alt="sort up">
                                    <img class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" alt="sort down">
                                </span>
                            </th>
                            <th class="bolopa-align-center bolopa-align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksis ?? collect() as $p)
                            <tr data-produksi-id="{{ $p->id }}">
                                <td class="bolopa-align-center bolopa-align-middle">{{ $loop->iteration }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">{{ optional($p->tanggal_produksi)->format('d/m/Y') ?? '-' }}</td>
                                <td class="bolopa-align-left bolopa-align-middle">{{ optional($p->produk)->nama_produk ?? '-' }}</td>
                                <td class="bolopa-align-right bolopa-align-middle">{{ ($p->jumlah_hasil ?? 0) == floor(($p->jumlah_hasil ?? 0)) ? number_format($p->jumlah_hasil ?? 0, 0, ',', '.') : number_format($p->jumlah_hasil ?? 0, 2, ',', '.') }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">{{ $p->grade_display ?? $p->grade_kualitas ?? '-' }}</td>
                                <td class="bolopa-align-center bolopa-align-middle">
                                    <div class="actions">
                                        <button type="button" class="btn btn-info btn-action" onclick="viewDetails({{ $p->id }}, 'produksi')" title="Lihat Detail">
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
                                <td colspan="6" class="bolopa-align-center bolopa-align-middle" style="padding: 40px; color: #6c757d;">
                                    <i class="fas fa-box-open" style="font-size:3rem; margin-bottom:10px;"></i><br>
                                    Belum ada data produksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        @endif
    </x-slot>
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="produkOperasionalToast"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('bolopa/back/js/bolopa-export-print.js') }}"></script>
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

        const notify = function (message, type) {
            showToast(message, type);
        };

        window.initBolopaExportPrint({
            tableSelector: '#dataTable',
            exportButtonSelector: '#btnExport',
            printButtonSelector: '#btnPrint',
            filenamePrefix: 'produk-operasional-export',
            printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
            printBrandTitle: 'Cocofarma — Produk Operasional',
            printBrandSubtitle: 'Laporan data produk operasional',
            printNotes: 'Catatan: Kolom aksi dihilangkan untuk keperluan cetak. Data mencakup produk master, stok, dan produksi.',
            totalLabel: 'Total Data',
            notify: notify,
            messages: {
                exportSuccess: 'Data produk operasional berhasil diekspor.',
                exportError: 'Gagal export data produk operasional.',
                printInfo: 'Membuka tampilan print...',
                printError: 'Gagal membuka tampilan print.'
            }
        });

        // Initial run
        document.addEventListener('DOMContentLoaded', filterTable);
    })();

    // Action functions
    function viewDetails(id, type) {
        console.log('viewDetails called with:', id, type);
        if (type === 'stok') {
            showOperationalDetail(id, 'stok');
        } else if (type === 'produksi') {
            showOperationalDetail(id, 'produksi');
        }
    }

    function showOperationalDetail(id, type) {
        console.log('showOperationalDetail called with:', id, type);
        try {
            const selector = type === 'stok' ? `tr[data-stok-id="${id}"]` : `tr[data-produksi-id="${id}"]`;
            const row = document.querySelector(selector);

            if (!row) {
                Swal.fire({
                    title: 'Detail Tidak Ditemukan',
                    text: `Detail ${type} dengan ID ${id} tidak ditemukan.`,
                    icon: 'info'
                });
                return;
            }

            // Extract data from table row
            const cols = row.querySelectorAll('td');
            let data = {};

            if (type === 'stok') {
                // columns: no(0), tanggal(1), nama(2), jumlah(3), harga(4), grade(5), aksi(6)
                data = {
                    tanggal: cols[1] ? cols[1].textContent.trim() : '-',
                    nama: cols[2] ? cols[2].textContent.trim() : '-',
                    jumlah: cols[3] ? cols[3].textContent.trim() : '-',
                    harga: cols[4] ? cols[4].textContent.trim() : '-',
                    grade: cols[5] ? cols[5].textContent.trim() : '-'
                };
            } else if (type === 'produksi') {
                // columns: no(0), tanggal(1), nama(2), jumlah(3), grade(4), aksi(5)
                data = {
                    tanggal: cols[1] ? cols[1].textContent.trim() : '-',
                    nama: cols[2] ? cols[2].textContent.trim() : '-',
                    jumlah: cols[3] ? cols[3].textContent.trim() : '-',
                    grade: cols[4] ? cols[4].textContent.trim() : '-'
                };
            }

            const title = type === 'stok' ? 'Detail Stok Operasional' : 'Detail Produksi';
            const typeLabel = type === 'stok' ? 'Stok' : 'Produksi';

            // Simple HTML first
            let html = `
                <div style="text-align: left; padding: 20px;">
                    <h3>${title}</h3>
                    <p><strong>Tanggal:</strong> ${data.tanggal}</p>
                    <p><strong>Nama Produk:</strong> ${data.nama}</p>
                    <p><strong>${type === 'stok' ? 'Jumlah Sisa Stok' : 'Jumlah Hasil'}:</strong> ${data.jumlah}</p>
                    ${type === 'stok' ? `<p><strong>Harga Satuan:</strong> ${data.harga}</p>` : ''}
                    <p><strong>Grade Kualitas:</strong> ${data.grade}</p>
                </div>
            `;

            Swal.fire({
                title: title,
                html: html,
                width: 600,
                showCloseButton: true,
                confirmButtonText: 'Tutup'
            });
        } catch (error) {
            console.error('Error showing detail:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat menampilkan detail.',
                icon: 'error'
            });
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
            reverseButtons: true,
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
            reverseButtons: true,
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

    function viewMasterProductDetails(id) {
        // For operational users, show master product details in popup
        // since they don't have access to master product show page
        showMasterProductDetail(id);
    }

    function showMasterProductDetail(id) {
        console.log('showMasterProductDetail called with:', id);

        // Find the product row in the master products table
        const row = document.querySelector(`tr[data-produk-id="${id}"]`);
        if (!row) {
            Swal.fire({
                title: 'Detail Tidak Ditemukan',
                text: 'Detail produk master tidak ditemukan.',
                icon: 'info'
            });
            return;
        }

        // Extract data from table row
        const cols = row.querySelectorAll('td');
        const imgElement = row.querySelector('img');
        const imageSrc = imgElement ? imgElement.src : null;
        const imageAlt = imgElement ? imgElement.alt : 'Tidak ada foto';

        // columns: no(0), gambar(1), nama(2), kategori(3), satuan(4), harga(5), stok(6), aksi(7)
        const data = {
            nama: cols[2] ? cols[2].textContent.trim().replace('●', '').trim() : '-',
            kategori: cols[3] ? cols[3].textContent.trim() : '-',
            satuan: cols[4] ? cols[4].textContent.trim() : '-',
            grade: cols[5] ? cols[5].textContent.trim() : '-',
            harga: cols[6] ? cols[6].textContent.trim() : '-',
            stok: cols[7] ? cols[7].textContent.trim() : '0',
            foto: imageSrc,
            alt: imageAlt
        };

        // Create HTML for the popup
        let html = `
            <div style="text-align: left; padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
        `;

        if (data.foto) {
            html += `<img src="${data.foto}" alt="${data.alt}" style="max-width: 150px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">`;
        } else {
            html += `
                <div style="width: 100px; height: 100px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: #6c757d;">
                    <i class="fas fa-image" style="font-size: 2rem;"></i>
                </div>
            `;
        }

        html += `
                </div>
                <div style="border-top: 1px solid #e9ecef; padding-top: 20px;">
                    <p><strong>Nama Produk:</strong> ${data.nama}</p>
                    <p><strong>Kategori:</strong> ${data.kategori}</p>
                    <p><strong>Satuan:</strong> ${data.satuan}</p>
                    <p><strong>Grade:</strong> ${data.grade}</p>
                    <p><strong>Harga Jual:</strong> ${data.harga}</p>
                    <p><strong>Stok Tersedia:</strong> <span style="color: ${parseFloat(data.stok.replace(/,/g, '')) > 0 ? '#28a745' : '#dc3545'}; font-weight: 600;">${data.stok} ${data.satuan}</span></p>
                </div>
            </div>
        `;

        Swal.fire({
            title: 'Detail Produk Master',
            html: html,
            width: 500,
            showCloseButton: true,
            confirmButtonText: 'Tutup'
        });
    }

    // Helper function to show toast
    function showToast(message, type = 'info') {
        const toast = document.getElementById('produkOperasionalToast');
        if (toast) {
            toast.textContent = message;
            toast.className = `bolopa-tabel-toast ${type}`;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    }
</script>
@endpush

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

    /* Table column alignments sesuai master produk */
    #dataTable th:nth-child(1), #dataTable td:nth-child(1) { text-align: center; } /* No */
    #dataTable th:nth-child(2), #dataTable td:nth-child(2) { text-align: center; } /* Gambar */
    #dataTable th:nth-child(3), #dataTable td:nth-child(3) { text-align: left; } /* Nama */
    #dataTable th:nth-child(4), #dataTable td:nth-child(4) { text-align: left; } /* Kategori */
    #dataTable th:nth-child(5), #dataTable td:nth-child(5) { text-align: left; } /* Satuan */
    #dataTable th:nth-child(6), #dataTable td:nth-child(6) { text-align: left; } /* Grade */
    #dataTable th:nth-child(7), #dataTable td:nth-child(7) { text-align: right; } /* Harga */
    #dataTable th:nth-child(8), #dataTable td:nth-child(8) { text-align: center; } /* Stok */
    #dataTable th:nth-child(9), #dataTable td:nth-child(9) { text-align: center; } /* Aksi */

    /* Stock status colors */
    .text-success { color: #28a745 !important; }
    .text-danger { color: #dc3545 !important; }
    .font-weight-bold { font-weight: 600 !important; }
    #dataTable th:nth-child(8), #dataTable td:nth-child(8) { text-align: center; } /* Aksi */

</style>
@endpush



