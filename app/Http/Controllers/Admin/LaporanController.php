<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi;
use App\Models\BatchProduksi;
use App\Models\StokProduk;
use App\Models\StokBahanBaku;
use App\Models\ProduksiBahan;
use Carbon\Carbon;
use PDF;

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
            case 'minggu':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $periodLabel = 'Minggu Ini';
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
        $totalPenjualan = \App\Models\Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('jenis_transaksi', 'penjualan')
            ->where('status', 'selesai')
            ->sum('total');

        // Total Stock Remaining (this doesn't change with period)
        $totalStokProduk = StokProduk::sum('sisa_stok');
        $totalStokBahanBaku = StokBahanBaku::sum('sisa_stok');
        $totalStok = $totalStokProduk + $totalStokBahanBaku;

        // Chart Data - Production and Sales for last 6 months (this stays the same for overview)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            $monthName = $date->format('M');

            $produksi = Produksi::whereBetween('tanggal_produksi', [$monthStart, $monthEnd])
                ->where('status', 'selesai')
                ->sum('jumlah_hasil');

            $penjualan = \App\Models\Transaksi::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])
                ->where('jenis_transaksi', 'penjualan')
                ->where('status', 'selesai')
                ->sum('total');

            $chartData[] = [
                'month' => $monthName,
                'produksi' => (int)$produksi,
                'penjualan' => (float)$penjualan
            ];
        }

        // Recent Reports Summary (last 10 entries from different report types within selected period)
        $recentReports = collect();

        // Recent Productions within period
        $productions = Produksi::with('produk')
            ->whereBetween('tanggal_produksi', [$startDate, $endDate])
            ->orderBy('tanggal_produksi', 'desc')
            ->limit(5)
            ->get()
            ->map(function($p) {
                return [
                    'type' => 'Produksi',
                    'tanggal' => $p->tanggal_produksi,
                    'keterangan' => 'Produksi ' . ($p->produk->nama_produk ?? 'Unknown'),
                    'jumlah' => $p->jumlah_hasil . ' ' . ($p->produk->satuan ?? 'unit')
                ];
            });

        // Recent Transactions within period
        $transactions = \App\Models\Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get()
            ->map(function($t) {
                return [
                    'type' => ucfirst($t->jenis_transaksi),
                    'tanggal' => $t->tanggal_transaksi,
                    'keterangan' => $t->keterangan ?? 'Transaksi ' . $t->kode_transaksi,
                    'jumlah' => 'Rp ' . number_format($t->total, 0, ',', '.')
                ];
            });

        $recentReports = $productions->concat($transactions)->sortByDesc('tanggal')->take(10);

        return view('admin.pages.laporan.index-laporan', compact(
            'totalProduksi',
            'totalPenjualan',
            'totalStok',
            'chartData',
            'recentReports',
            'periodLabel',
            'period'
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

    public function exportPdf($type)
    {
        // TODO: Implement PDF export
        // return response()->download($filePath);
        return back()->with('info', 'PDF export belum diimplementasi.');
    }

    public function exportExcel($type)
    {
        // TODO: Implement Excel export
        // return response()->download($filePath);
        return back()->with('info', 'Excel export belum diimplementasi.');
    }
}
