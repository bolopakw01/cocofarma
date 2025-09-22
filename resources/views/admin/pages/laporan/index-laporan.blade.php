@extends('admin.layouts.app')

@php
    $pageTitle = 'Laporan';
@endphp

@section('title', 'Laporan - Cocofarma')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    * { box-sizing: border-box; font-family: inherit; }

    .container { max-width: 1200px; margin: 0 auto; background: #fff; border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 20px; }
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom: 18px; padding-bottom: 8px; border-bottom: 1px solid var(--light-gray); }
    h1 { font-size: 1.4rem; font-weight:600; color: #222; display:flex; gap:10px; align-items:center; }
    .controls { display:flex; justify-content:space-between; gap:12px; margin-bottom:16px; padding:12px; background:#f8f9fb; border-radius:8px; }
    .left-controls { display:flex; gap:12px; align-items:center; }
    .right-controls { display:flex; gap:8px; align-items:center; }
    .search-box { position:relative; }
    .search-box input { padding:8px 12px 8px 36px; border-radius:8px; border:1px solid var(--light-gray); width:260px; }
    .search-box i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777; }
    .cards { display:flex; gap:12px; margin-bottom:18px; }
    .card { flex:1; padding:14px; background:white; border-radius:8px; box-shadow: var(--box-shadow); }
    .card h3 { margin:0; font-size:0.9rem; color:#666; }
    .card p { margin-top:8px; font-size:1.3rem; font-weight:600; }
    .chart-wrapper { background:white; padding:14px; border-radius:8px; box-shadow: var(--box-shadow); margin-bottom:16px; }
    .table-responsive { background:white; padding:12px; border-radius:8px; box-shadow: var(--box-shadow); }

    /* Table styles copied/adapted from produksi page to match UI */
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

    th, td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid var(--light-gray);
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 0;
    }

    th:nth-child(3), td:nth-child(3) {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
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

    th:hover { background-color: #e9ecef; }
    th i { margin-left: 5px; font-size: 0.8rem; opacity: 0.6; }
    th.active i { opacity: 1; }

    tr { transition: var(--transition); }
    tr:hover { background-color: #f8f9fa; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,0.05); }

    .badge { padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-danger { background: #f8d7da; color: #721c24; }

    .actions { display:flex; justify-content:center; gap:5px; min-width:120px; }

    .pagination { display:flex; justify-content:space-between; align-items:center; margin-top:20px; flex-wrap:wrap; gap:10px; }
    .pagination-info { color: var(--gray); font-size:0.9rem; flex-shrink:0; white-space:nowrap; max-width:30%; overflow:hidden; text-overflow:ellipsis; }
    .pagination-buttons { display:flex; gap:5px; flex-wrap:wrap; justify-content:flex-end; flex-shrink:0; max-width:70%; }
    .pagination-buttons button { padding:6px 10px; border:1px solid var(--light-gray); background:white; border-radius:var(--border-radius); cursor:pointer; display:flex; align-items:center; justify-content:center; min-width:34px; height:34px; font-size:0.9rem; transition:var(--transition); }
    .pagination-buttons button:hover { background:var(--light); }
    .pagination-buttons button.active { background:var(--primary); color:white; border-color:var(--primary); }
    .pagination-buttons button:disabled { opacity:0.5; cursor:not-allowed; }

    .filter-group { display:flex; align-items:center; gap:8px; padding:8px 12px; background:#f8f9fb; border-radius:8px; }
    .filter-group label { margin:0; font-weight:500; color:#555; }
    .filter-group select { padding:6px 12px; border:1px solid var(--light-gray); border-radius:6px; background:white; cursor:pointer; font-size:0.9rem; }

    .table th .sort-icons i { margin: 0; padding: 0; height: 12px; }
    .table th .sort-icons i.sort-up { margin-bottom: -5px; }
    .table th .sort-icons i.sort-down { margin-top: -5px; }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Laporan</h1>
        <div class="page-actions">
            <div class="filter-group">
                <label for="periodFilter" style="margin-right:8px; font-weight:500;">Periode:</label>
                <select id="periodFilter" style="padding:6px 12px; border:1px solid var(--light-gray); border-radius:6px; background:white; cursor:pointer;">
                    <option value="hari" {{ request('period') == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu" {{ request('period') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ request('period') == 'bulan' || !request('period') ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('period') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Produksi ({{ $periodLabel }})</h3>
            <p id="totalProduksi">{{ number_format($totalProduksi, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Total Penjualan ({{ $periodLabel }})</h3>
            <p id="totalPenjualan">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Stok Tersisa</h3>
            <p id="totalStok">{{ number_format($totalStok, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="chart-wrapper">
        <div id="laporanChart" style="height:320px;"></div>
    </div>

    <div class="controls" style="margin-top:16px;">
        <div class="left-controls">
            <div class="entries-select">
                <label for="entriesSelect">Tampilkan</label>
                <select id="entriesSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="all">Semua</option>
                </select>
                <span>entri</span>
            </div>
        </div>

        <div class="right-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari laporan..." />
            </div>

            @if(Auth::check() && in_array(Auth::user()->role, ['super_admin','admin']))
            <div class="dropdown-export" style="position:relative;">
                <button class="btn btn-success" id="btnExportToggle"><i class="fas fa-file-export"></i> Export <i class="fas fa-caret-down" style="margin-left:6px"></i></button>
                <div id="exportMenu" style="display:none; position:absolute; right:0; top:38px; background:white; border:1px solid var(--light-gray); border-radius:6px; box-shadow:0 6px 18px rgba(0,0,0,0.08);">
                    <a href="{{ route('backoffice.laporan.export-excel', ['type' => 'all']) }}" class="dropdown-item" style="display:block;padding:8px 12px;color:#222;text-decoration:none;">Export Excel</a>
                    <a href="{{ route('backoffice.laporan.export-pdf', ['type' => 'all']) }}" class="dropdown-item" style="display:block;padding:8px 12px;color:#222;text-decoration:none;">Export PDF</a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" id="laporanTable">
            <thead>
                <tr>
                    <th data-sort="no" style="width:6%">No <span class="sort-icons" style="display:inline-flex;flex-direction:column;margin-left:6px"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                    <th data-sort="type">Tipe Laporan <span class="sort-icons" style="display:inline-flex;flex-direction:column;margin-left:6px"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                    <th data-sort="tanggal">Tanggal <span class="sort-icons" style="display:inline-flex;flex-direction:column;margin-left:6px"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                    <th data-sort="keterangan">Keterangan <span class="sort-icons" style="display:inline-flex;flex-direction:column;margin-left:6px"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                    <th data-sort="jumlah">Jumlah <span class="sort-icons" style="display:inline-flex;flex-direction:column;margin-left:6px"><i class="fas fa-sort-up sort-up"></i><i class="fas fa-sort-down sort-down"></i></span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report['type'] }}</td>
                    <td>{{ $report['tanggal']->format('d/m/Y') }}</td>
                    <td>{{ $report['keterangan'] }}</td>
                    <td>{{ $report['jumlah'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        <i class="fas fa-chart-line" style="font-size: 3rem; color: #6c757d; margin-bottom: 10px;"></i>
                        <br>
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){

        // Initialize ApexCharts if available
        if (typeof ApexCharts !== 'undefined') {
            const chartData = @json($chartData);
            const categories = chartData.map(item => item.month);
            const produksiData = chartData.map(item => item.produksi);
            const penjualanData = chartData.map(item => item.penjualan);

            const options = {
                chart: { type: 'area', height: 320 },
                series: [
                    { name: 'Produksi', data: produksiData },
                    { name: 'Penjualan', data: penjualanData }
                ],
                xaxis: { categories: categories },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, { seriesIndex }) {
                            if (seriesIndex === 0) {
                                return value + ' unit';
                            } else {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            };
            const chart = new ApexCharts(document.querySelector('#laporanChart'), options);
            chart.render();
        }

        const btnExportToggle = document.getElementById('btnExportToggle');
        const exportMenu = document.getElementById('exportMenu');
        if (btnExportToggle && exportMenu) {
            btnExportToggle.addEventListener('click', function(e){
                e.stopPropagation();
                exportMenu.style.display = exportMenu.style.display === 'block' ? 'none' : 'block';
            });

            // close when clicking outside
            document.addEventListener('click', function(){
                exportMenu.style.display = 'none';
            });
        }

        // Simple client-side table header sort UI (visual only) - matches produksi behaviour
        const table = document.getElementById('laporanTable');
        if (table) {
            const headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(h => {
                    h.addEventListener('click', function(){
                        // set active class and reset others
                        headers.forEach(x => {
                            x.classList.remove('active');
                            const upx = x.querySelector('.sort-up');
                            const downx = x.querySelector('.sort-down');
                            if (upx && downx) { upx.style.opacity = '0.6'; downx.style.opacity = '0.6'; }
                            x.dataset.sortDir = '';
                        });

                        this.classList.add('active');
                        // toggle asc/desc explicitly
                        const up = this.querySelector('.sort-up');
                        const down = this.querySelector('.sort-down');
                        const current = this.dataset.sortDir === 'asc' ? 'asc' : (this.dataset.sortDir === 'desc' ? 'desc' : '');
                        const next = current === 'asc' ? 'desc' : 'asc';
                        this.dataset.sortDir = next;
                        if (up && down) {
                            if (next === 'asc') { up.style.opacity = '1'; down.style.opacity = '0.6'; }
                            else { up.style.opacity = '0.6'; down.style.opacity = '1'; }
                        }
                        // TODO: hook to server-side sort via query params if needed
                    });
            });
        }

        // Handle period filter change
        const periodFilter = document.getElementById('periodFilter');
        if (periodFilter) {
            periodFilter.addEventListener('change', function() {
                const selectedPeriod = this.value;
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('period', selectedPeriod);
                window.location.href = currentUrl.toString();
            });
        }
    });
</script>

@endsection

