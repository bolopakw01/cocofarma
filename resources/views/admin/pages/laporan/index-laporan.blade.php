@extends('admin.layouts.app')

@section('pageTitle', 'Laporan')

@push('styles')
<style>
	:root { --card-bg: #fff; --muted: #64748b; --accent: #2563eb; --text: #0f172a; }

	.report-page { display:flex; flex-direction:column; gap:24px; }

	.report-header { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
	.report-header h1 { margin:0; font-size:28px; font-weight:700; color:var(--text); }
	.report-header p { margin:4px 0 0; color:var(--muted); font-size:14px; }

	/* Header card */
	.report-header-card {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		padding: 18px;
		background: var(--card-bg);
		border-radius: 14px;
		border: 1px solid rgba(15,23,42,0.04);
		box-shadow: 0 8px 20px rgba(2,6,23,0.04);
	}

	.report-header-card .left {
		display:flex; flex-direction:column; gap:4px; min-width:0;
	}

	.report-header-card .left h1 { margin:0; font-size:24px; color:var(--text); }
	.report-header-card .left p { margin:0; color:var(--muted); }

	.report-header-card .right { display:flex; gap:12px; align-items:center; }

	.report-actions { display:flex; align-items:center; gap:12px; }

	.export-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:10px; background:var(--accent); color:#fff; font-weight:600; text-decoration:none; border:none; cursor:pointer; }

	.report-filter-form { display:flex; align-items:center; gap:12px; padding:8px; background:transparent; }
	.report-filter-form label { font-size:13px; color:var(--muted); font-weight:600; }
	.report-filter-form select { padding:8px 10px; border-radius:8px; border:1px solid rgba(148,163,184,0.6); background:#fff; font-weight:600; }

	.report-stats-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px; }

	.report-card { background:var(--card-bg); border-radius:14px; padding:18px; border:1px solid rgba(15,23,42,0.04); box-shadow:0 10px 30px rgba(2,6,23,0.04); display:flex; flex-direction:column; gap:10px; }
	.report-card-header { display:flex; align-items:center; justify-content:space-between; gap:12px; }
	.card-title-left { display:flex; flex-direction:column; }
	.report-card .title { font-size:13px; text-transform:uppercase; color:var(--muted); margin:0; font-weight:700; letter-spacing:0.06em; }
	.report-card .badge { font-size:12px; padding:4px 8px; border-radius:999px; color:var(--accent); background:rgba(37,99,235,0.08); font-weight:700; }

	.card-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg, rgba(37,99,235,0.06), rgba(37,99,235,0.02)); color:var(--accent); font-size:20px; }

	.metric-value { font-size:26px; font-weight:800; color:var(--text); margin:0; }
	.metric-subtext { font-size:13px; color:var(--muted); margin:0; }

	.chart-card { padding:18px; border-radius:14px; background:var(--card-bg); border:1px solid rgba(15,23,42,0.04); }
	.chart-card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
	.chart-card h2 { margin:0; font-size:16px; font-weight:700; color:#102a43; }

	.history-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap:18px; }
	.history-card { padding:14px; border-radius:12px; background:var(--card-bg); border:1px solid rgba(15,23,42,0.04); }
	.history-card h3 { margin:0 0 12px; font-size:16px; font-weight:700; color:#102a43; }

	.history-table { width:100%; border-collapse:collapse; font-size:13px; }
	.history-table thead { background:#fbfdff; }
	.history-table th, .history-table td { padding:10px 12px; border-bottom:1px solid rgba(226,232,240,0.9); color:#475569; }
	.history-table th { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; }
	.history-table tbody tr:nth-child(even) { background:#ffffff; }
	.history-table tbody tr:hover { background:#f8fbff; transform:translateY(-1px); }
	.history-table td small { color:#94a3b8; }

	.status-chip { padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; display:inline-flex; gap:8px; align-items:center; }
	.status-chip.success { background:rgba(16,185,129,0.08); color:#059669; }
	.status-chip.warning { background:rgba(234,179,8,0.08); color:#b45309; }
	.status-chip.info { background:rgba(37,99,235,0.08); color:#1d4ed8; }

	.timeline-card { padding:18px; border-radius:12px; background:var(--card-bg); }
	.timeline-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:12px; }
	.timeline-item { display:flex; gap:12px; align-items:flex-start; }
	.timeline-marker { width:10px; height:10px; border-radius:50%; background:var(--accent); box-shadow:0 0 0 6px rgba(37,99,235,0.08); margin-top:6px; }

	.timeline-title { margin:0; font-size:14px; font-weight:700; color:#102a43; }
	.timeline-amount { font-weight:700; color:var(--accent); }

	.empty-state { padding:20px; text-align:center; color:#94a3b8; }

	@media (max-width:768px) {
		.report-stats-grid { grid-template-columns: 1fr; }
		.history-grid { grid-template-columns: 1fr; }
	}
</style>
@endpush

@section('content')
	<div class="report-page">
		<div class="report-header-card">
			<div class="left">
				<h1>Laporan Operasional</h1>
				<p>Ringkasan performa produksi dan penjualan untuk periode <strong>{{ $periodLabel }}</strong>.</p>
			</div>

			<div class="right">
				<div class="report-actions">
					<a href="{{ route('backoffice.laporan.export-excel', ['type' => 'full']) }}" class="export-btn">
						<i class="bx bx-download"></i> Export CSV
					</a>
				</div>

				<form method="GET" action="{{ route('backoffice.laporan.index') }}" class="report-filter-form">
					<label for="period">Periode</label>
					<select id="period" name="period" onchange="this.form.submit()">
						<option value="hari" {{ $period === 'hari' ? 'selected' : '' }}>Harian</option>
						<option value="bulan" {{ $period === 'bulan' ? 'selected' : '' }}>Bulanan</option>
						<option value="tahun" {{ $period === 'tahun' ? 'selected' : '' }}>Tahunan</option>
					</select>
				</form>
			</div>
		</div>

		<div class="chart-card">
			<div class="chart-card-header">
				<h2>
					@if($period === 'hari')
						Perbandingan Produksi & Penjualan (7 Hari Terakhir)
					@elseif($period === 'bulan')
						Perbandingan Produksi & Penjualan (6 Bulan Terakhir)
					@else
						Perbandingan Produksi & Penjualan (12 Bulan Terakhir)
					@endif
				</h2>
				<span class="badge">Trend</span>
			</div>
			<div id="report-performance-chart" style="min-height: 320px;"></div>
		</div>

		<div class="history-grid">
			<div class="history-card">
				<h3>Riwayat Produksi</h3>
				@if($productionHistory->isEmpty())
					<div class="empty-state">Belum ada aktivitas produksi pada periode ini.</div>
				@else
					<div class="table-responsive">
						<table class="history-table">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Produk</th>
									<th>Hasil</th>
									<th>Biaya</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($productionHistory as $production)
									<tr>
										<td>{{ optional($production->tanggal_produksi)->format('d M Y') }}</td>
										<td>
											<strong>{{ $production->produk->nama_produk ?? 'Produk' }}</strong><br>
											<small>Batch {{ $production->nomor_produksi ?? '-' }}</small>
										</td>
										<td>{{ number_format((float) $production->jumlah_hasil, 0, ',', '.') }} {{ $production->produk->satuan ?? 'unit' }}</td>
										<td>Rp {{ number_format((float) $production->biaya_produksi, 0, ',', '.') }}</td>
										<td>
											@php
												$statusClass = match ($production->status) {
													'selesai' => 'success',
													'proses' => 'info',
													default => 'warning'
												};
											@endphp
											<span class="status-chip {{ $statusClass }}">{{ $production->status_label }}</span>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>

			<div class="history-card">
				<h3>Riwayat Penjualan</h3>
				@if($salesHistory->isEmpty())
					<div class="empty-state">Belum ada transaksi penjualan pada periode ini.</div>
				@else
					<div class="table-responsive">
						<table class="history-table">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Kode Transaksi</th>
									<th>Keterangan</th>
									<th>Total</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($salesHistory as $sale)
									<tr>
										<td>{{ optional($sale->tanggal_transaksi)->format('d M Y') }}</td>
										<td><strong>{{ $sale->kode_transaksi }}</strong></td>
										<td>{{ $sale->keterangan ?? 'Penjualan' }}</td>
										<td>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
										<td>
											@php
												$saleStatusClass = $sale->status === 'selesai' ? 'success' : ($sale->status === 'pending' ? 'info' : 'warning');
											@endphp
											<span class="status-chip {{ $saleStatusClass }}">{{ $sale->status_label }}</span>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</div>


	</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const rawChartData = @json($chartData);
		const categories = rawChartData.map(item => item.label ?? item.month);
		const produksiSeries = rawChartData.map(item => Number(item.produksi || 0));
		const penjualanSeries = rawChartData.map(item => Number(item.penjualan || 0));

		if (!document.querySelector('#report-performance-chart')) {
			return;
		}

		const chartOptions = {
			chart: {
				type: 'area',
				height: 320,
				toolbar: { show: false },
				fontFamily: 'Poppins, sans-serif'
			},
			colors: ['#2563eb', '#f97316'],
			dataLabels: { enabled: false },
			stroke: {
				curve: 'smooth',
				width: 3
			},
			grid: {
				borderColor: 'rgba(226, 232, 240, 0.6)',
				strokeDashArray: 4,
				padding: {
					left: 12,
					right: 12,
					top: 12,
					bottom: 0
				}
			},
			series: [
				{
					name: 'Produksi (unit)',
					data: produksiSeries
				},
				{
					name: 'Penjualan (Rp)',
					data: penjualanSeries
				}
			],
			xaxis: {
				categories,
				axisBorder: { color: 'rgba(203, 213, 225, 0.8)' },
				axisTicks: { color: 'rgba(203, 213, 225, 0.8)' },
				labels: {
					style: {
						colors: '#94a3b8'
					}
				}
			},
			yaxis: [
				{
					labels: {
						formatter: val => Math.round(val),
						style: { colors: '#94a3b8' }
					},
					title: {
						text: 'Produksi',
						style: { color: '#64748b', fontSize: '12px' }
					}
				},
				{
					opposite: true,
					labels: {
						formatter: val => {
							if (val >= 1000000) {
								return 'Rp ' + (val / 1000000).toFixed(1) + 'JT';
							}
							if (val >= 1000) {
								return 'Rp ' + (val / 1000).toFixed(1) + 'K';
							}
							return 'Rp ' + Math.round(val);
						},
						style: { colors: '#94a3b8' }
					},
					title: {
						text: 'Penjualan',
						style: { color: '#64748b', fontSize: '12px' }
					}
				}
			],
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				labels: {
					colors: '#475569'
				}
			},
			tooltip: {
				shared: true,
				intersect: false,
				y: {
					formatter: (value, { seriesIndex }) => {
						if (seriesIndex === 0) {
							return `${Math.round(value)} unit`;
						}
						return `Rp ${new Intl.NumberFormat('id-ID').format(Math.round(value))}`;
					}
				}
			}
		};

		const chart = new ApexCharts(document.querySelector('#report-performance-chart'), chartOptions);
		chart.render();
	});
</script>
@endpush
