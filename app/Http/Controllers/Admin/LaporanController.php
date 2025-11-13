<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi;
use App\Models\BatchProduksi;
use App\Models\StokProduk;
use App\Models\StokBahanBaku;
use App\Models\ProduksiBahan;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'bulan');

        // Determine date range based on period
        switch ($period) {
            case 'hari':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $periodLabel = 'Hari Ini';
                break;
            case 'tahun':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $periodLabel = 'Tahun Ini';
                break;
            case 'bulan':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodLabel = 'Bulan Ini';
                break;
        }

        // Total Production for selected period
        $totalProduksi = Produksi::whereBetween('tanggal_produksi', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->sum('jumlah_hasil');

        // Total Sales for selected period
        $totalPenjualan = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('jenis_transaksi', 'penjualan')
            ->where('status', 'selesai')
            ->sum('total');

        // Total Stock Remaining (this doesn't change with period)
        $totalStokProduk = StokProduk::sum('sisa_stok');
        $totalStokBahanBaku = StokBahanBaku::sum('sisa_stok');
        $totalStok = $totalStokProduk + $totalStokBahanBaku;

        // Chart data aligned with selected period
        $chartData = $this->buildChartData($period);

        $productionHistory = Produksi::with('produk')
            ->whereBetween('tanggal_produksi', [$startDate, $endDate])
            ->orderBy('tanggal_produksi', 'desc')
            ->limit(10)
            ->get();

    $salesHistory = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('jenis_transaksi', 'penjualan')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        $recentReports = $productionHistory->map(function($p) {
                $productName = $p->produk->nama_produk ?? 'Produk';
                $unit = $p->produk->satuan ?? 'unit';
                $batchCode = $p->nomor_produksi ?: '-';
                return [
                    'type' => 'Produksi',
                    'tanggal' => $p->tanggal_produksi,
                    'keterangan' => "Batch {$batchCode} - {$productName}",
                    'jumlah' => number_format((float) $p->jumlah_hasil, 0, ',', '.') . ' ' . $unit,
                ];
            })
            ->concat($salesHistory->map(function($t) {
                return [
                    'type' => 'Penjualan',
                    'tanggal' => $t->tanggal_transaksi,
                    'keterangan' => $t->keterangan ?? 'Transaksi ' . $t->kode_transaksi,
                    'jumlah' => 'Rp ' . number_format((float) $t->total, 0, ',', '.'),
                ];
            }))
            ->sortByDesc('tanggal')
            ->take(10);

        return view('admin.pages.laporan.index-laporan', compact(
            'totalProduksi',
            'totalPenjualan',
            'totalStok',
            'chartData',
            'recentReports',
            'periodLabel',
            'period',
            'productionHistory',
            'salesHistory'
        ));
    }

    public function produksi(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Production Summary
        $productions = Produksi::with(['produk', 'batchProduksi.tungku', 'user'])
            ->whereBetween('tanggal_produksi', [$startDate, $endDate])
            ->orderBy('tanggal_produksi', 'desc')
            ->get();

        // Production Statistics
        $stats = [
            'total_productions' => $productions->count(),
            'completed_productions' => $productions->where('status', 'selesai')->count(),
            'total_target' => $productions->sum('jumlah_target'),
            'total_hasil' => $productions->sum('jumlah_hasil'),
            'total_cost' => $productions->sum('biaya_produksi'),
            'efficiency' => $productions->sum('jumlah_target') > 0 ?
                ($productions->sum('jumlah_hasil') / $productions->sum('jumlah_target')) * 100 : 0,
        ];

        // Grade Distribution
        $gradeStats = $productions->where('status', 'selesai')
            ->groupBy('grade_kualitas')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total_quantity' => $group->sum('jumlah_hasil'),
                    'total_cost' => $group->sum('biaya_produksi'),
                ];
            });

        // Batch Performance
        $batchPerformance = BatchProduksi::with(['produksis', 'tungku'])
            ->whereHas('produksis', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_produksi', [$startDate, $endDate]);
            })
            ->get()
            ->map(function($batch) {
                $productions = $batch->produksis;
                return [
                    'batch' => $batch,
                    'total_productions' => $productions->count(),
                    'total_cost' => $productions->sum('biaya_produksi'),
                    'total_output' => $productions->sum('jumlah_hasil'),
                    'efficiency' => $productions->sum('jumlah_target') > 0 ?
                        ($productions->sum('jumlah_hasil') / $productions->sum('jumlah_target')) * 100 : 0,
                ];
            });

        return view('admin.pages.laporan.produksi-laporan', compact(
            'productions', 'stats', 'gradeStats', 'batchPerformance', 'startDate', 'endDate'
        ));
    }

    public function stok(Request $request)
    {
        $type = $request->get('type', 'produk'); // produk or bahan_baku

        if ($type === 'produk') {
            // Product Stock Report
            $stocks = StokProduk::with(['produk', 'batchProduksi'])
                ->where('sisa_stok', '>', 0)
                ->orderBy('produk_id')
                ->orderBy('tanggal')
                ->get();

            // Group by product
            $productSummary = $stocks->groupBy('produk_id')->map(function($group) {
                $product = $group->first()->produk;
                return [
                    'produk' => $product,
                    'total_stok' => $group->sum('sisa_stok'),
                    'total_nilai' => $group->sum(function($item) {
                        return $item->sisa_stok * $item->harga_satuan;
                    }),
                    'batches' => $group->count(),
                    'grade_distribution' => $group->groupBy('grade_kualitas')->map->count(),
                ];
            });

            return view('admin.pages.laporan.stok-laporan', compact('stocks', 'productSummary', 'type'));
        } else {
            // Raw Material Stock Report
            $stocks = StokBahanBaku::with(['bahanBaku'])
                ->where('sisa_stok', '>', 0)
                ->orderBy('bahan_baku_id')
                ->orderBy('tanggal', 'asc')
                ->get();

            // Group by material
            $materialSummary = $stocks->groupBy('bahan_baku_id')->map(function($group) {
                $material = $group->first()->bahanBaku;
                return [
                    'bahan_baku' => $material,
                    'total_stok' => $group->sum('sisa_stok'),
                    'total_nilai' => $group->sum(function($item) {
                        return $item->sisa_stok * $item->harga_satuan;
                    }),
                    'batches' => $group->count(),
                    'oldest_batch' => $group->min('tanggal'),
                    'newest_batch' => $group->max('tanggal'),
                ];
            });

            return view('admin.pages.laporan.stok-laporan', compact('stocks', 'materialSummary', 'type'));
        }
    }

    public function costAnalysis(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Cost Analysis by Product
        $costByProduct = Produksi::with('produk')
            ->whereBetween('tanggal_produksi', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->selectRaw('produk_id, SUM(biaya_produksi) as total_cost, SUM(jumlah_hasil) as total_output')
            ->groupBy('produk_id')
            ->get()
            ->map(function($item) {
                return [
                    'produk' => $item->produk,
                    'total_cost' => $item->total_cost,
                    'total_output' => $item->total_output,
                    'avg_cost_per_unit' => $item->total_output > 0 ? $item->total_cost / $item->total_output : 0,
                ];
            });

        // Cost Analysis by Batch
        $costByBatch = BatchProduksi::with(['produksis', 'tungku'])
            ->whereHas('produksis', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_produksi', [$startDate, $endDate])
                      ->where('status', 'selesai');
            })
            ->get()
            ->map(function($batch) {
                $productions = $batch->produksis->where('status', 'selesai');
                $totalCost = $productions->sum('biaya_produksi');
                $totalOutput = $productions->sum('jumlah_hasil');

                return [
                    'batch' => $batch,
                    'total_productions' => $productions->count(),
                    'total_cost' => $totalCost,
                    'total_output' => $totalOutput,
                    'avg_cost_per_unit' => $totalOutput > 0 ? $totalCost / $totalOutput : 0,
                    'cost_breakdown' => $this->getCostBreakdown($batch),
                ];
            });

        // Material Cost Trends
        $materialCostTrend = ProduksiBahan::with(['produksi', 'bahanBaku'])
            ->whereHas('produksi', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_produksi', [$startDate, $endDate])
                      ->where('status', 'selesai');
            })
            ->selectRaw('bahan_baku_id, AVG(harga_satuan) as avg_price, SUM(total_biaya) as total_cost')
            ->groupBy('bahan_baku_id')
            ->get()
            ->map(function($item) {
                return [
                    'bahan_baku' => $item->bahanBaku,
                    'avg_price' => $item->avg_price,
                    'total_cost' => $item->total_cost,
                ];
            });

        return view('admin.pages.laporan.cost-analysis', compact(
            'costByProduct', 'costByBatch', 'materialCostTrend', 'startDate', 'endDate'
        ));
    }

    private function getCostBreakdown(BatchProduksi $batch)
    {
        $productions = $batch->produksis->where('status', 'selesai');

        $materialCost = 0;
        $operationalCost = $batch->total_biaya_operasional ?? 0;

        foreach ($productions as $production) {
            $materialCost += $production->produksiBahans->sum('total_biaya');
        }

        return [
            'material' => $materialCost,
            'operational' => $operationalCost,
            'total' => $materialCost + $operationalCost,
        ];
    }

    private function buildChartData(string $period): array
    {
        if ($period === 'hari') {
            $chartData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $rangeStart = $date->copy()->startOfDay();
                $rangeEnd = $date->copy()->endOfDay();

                $chartData[] = [
                    'label' => $date->format('d M'),
                    'produksi' => (float) Produksi::whereBetween('tanggal_produksi', [$rangeStart, $rangeEnd])
                        ->where('status', 'selesai')
                        ->sum('jumlah_hasil'),
                    'penjualan' => (float) Transaksi::whereBetween('tanggal_transaksi', [$rangeStart, $rangeEnd])
                        ->where('jenis_transaksi', 'penjualan')
                        ->where('status', 'selesai')
                        ->sum('total'),
                ];
            }

            return $chartData;
        }

        $chartData = [];
        $monthsRange = $period === 'tahun' ? 11 : 5;

        for ($i = $monthsRange; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $rangeStart = $date->copy()->startOfMonth();
            $rangeEnd = $date->copy()->endOfMonth();

            $chartData[] = [
                'label' => $date->format('M Y'),
                'produksi' => (float) Produksi::whereBetween('tanggal_produksi', [$rangeStart, $rangeEnd])
                    ->where('status', 'selesai')
                    ->sum('jumlah_hasil'),
                'penjualan' => (float) Transaksi::whereBetween('tanggal_transaksi', [$rangeStart, $rangeEnd])
                    ->where('jenis_transaksi', 'penjualan')
                    ->where('status', 'selesai')
                    ->sum('total'),
            ];
        }

        return $chartData;
    }

    private function buildFullReportExportData(): array
    {
        $productions = Produksi::with('produk')
            ->orderBy('tanggal_produksi', 'desc')
            ->get();

    $sales = Transaksi::with('transaksiItems')
            ->where('jenis_transaksi', 'penjualan')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $generatedAt = Carbon::now();

        $productionTotals = [
            'count' => $productions->count(),
            'completed' => $productions->where('status', 'selesai')->count(),
            'total_target' => (float) $productions->sum('jumlah_target'),
            'total_output' => (float) $productions->sum('jumlah_hasil'),
            'total_cost' => (float) $productions->sum('biaya_produksi'),
        ];

        $salesTotals = [
            'count' => $sales->count(),
            'completed' => $sales->where('status', 'selesai')->count(),
            'total_value' => (float) $sales->sum('total'),
        ];

        $rangeStartValue = collect([
            $productions->min('tanggal_produksi'),
            $sales->min('tanggal_transaksi'),
        ])->filter()->min();

        $rangeEndValue = collect([
            $productions->max('tanggal_produksi'),
            $sales->max('tanggal_transaksi'),
        ])->filter()->max();

        $rangeStart = $rangeStartValue ? Carbon::parse($rangeStartValue) : null;
        $rangeEnd = $rangeEndValue ? Carbon::parse($rangeEndValue) : null;

        return [
            'productions' => $productions,
            'sales' => $sales,
            'generatedAt' => $generatedAt,
            'productionTotals' => $productionTotals,
            'salesTotals' => $salesTotals,
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd,
        ];
    }

    public function exportPdf($type)
    {
        abort(404, 'Export PDF tidak tersedia untuk laporan operasional.');
    }

    public function exportExcel($type)
    {
        $data = $this->buildFullReportExportData();
        $timestamp = $data['generatedAt']->format('Ymd_His');
        $filename = 'laporan-operasional-' . $timestamp . '.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for spreadsheet compatibility
            fprintf($handle, "\xEF\xBB\xBF");

            $columns = [
                'dataset',
                'sub_dataset',
                'date',
                'reference',
                'entity',
                'target_quantity',
                'result_quantity',
                'grade',
                'production_cost',
                'transaction_total',
                'status',
                'notes',
            ];
            fputcsv($handle, $columns);

            fputcsv($handle, [
                'metadata',
                'generated_at',
                $data['generatedAt']->toIso8601String(),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Waktu pembuatan laporan (ISO8601)'
            ]);

            if ($data['rangeStart'] && $data['rangeEnd']) {
                fputcsv($handle, [
                    'metadata',
                    'data_range',
                    $data['rangeStart']->format('Y-m-d') . '|' . $data['rangeEnd']->format('Y-m-d'),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Rentang tanggal data produksi & penjualan'
                ]);
            }

            fputcsv($handle, [
                'summary',
                'production_total',
                '',
                '',
                '',
                $data['productionTotals']['total_target'],
                $data['productionTotals']['total_output'],
                '',
                $data['productionTotals']['total_cost'],
                '',
                'selesai:' . $data['productionTotals']['completed'] . '/' . $data['productionTotals']['count'],
                'Ringkasan agregat produksi'
            ]);

            fputcsv($handle, [
                'summary',
                'sales_total',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $data['salesTotals']['total_value'],
                'selesai:' . $data['salesTotals']['completed'] . '/' . $data['salesTotals']['count'],
                'Ringkasan agregat penjualan'
            ]);

            foreach ($data['productions'] as $production) {
                fputcsv($handle, [
                    'production_detail',
                    'record',
                    optional($production->tanggal_produksi)->format('Y-m-d'),
                    $production->nomor_produksi,
                    optional($production->produk)->nama_produk,
                    $production->jumlah_target !== null ? (float) $production->jumlah_target : null,
                    $production->jumlah_hasil !== null ? (float) $production->jumlah_hasil : null,
                    $production->grade_kualitas,
                    $production->biaya_produksi !== null ? (float) $production->biaya_produksi : null,
                    null,
                    $production->status,
                    $production->status_label,
                ]);
            }

            foreach ($data['sales'] as $sale) {
                fputcsv($handle, [
                    'sales_detail',
                    'record',
                    optional($sale->tanggal_transaksi)->format('Y-m-d'),
                    $sale->kode_transaksi,
                    $sale->keterangan ?? 'Penjualan',
                    null,
                    null,
                    null,
                    null,
                    $sale->total !== null ? (float) $sale->total : null,
                    $sale->status,
                    $sale->status_label,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
