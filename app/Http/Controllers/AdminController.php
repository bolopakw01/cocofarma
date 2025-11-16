<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\MasterBahanBaku;
use App\Models\Pengaturan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            // Simpan role ke session
            $user = Auth::user();
            session(['role' => $user->role]);
            // Redirect to admin dashboard
            return redirect()->route('backoffice.dashboard')->with('success', 'Login berhasil! Selamat datang di Backoffice.');
        }

        return redirect()->back()->withInput()->with('error', 'Login gagal! Username atau password salah.');
    }

    public function chartData(Request $request)
    {
        $range = $request->get('range', 'weekly');
        $range = in_array($range, ['weekly', 'monthly', 'yearly'], true) ? $range : 'weekly';

        $now = Carbon::now();
        Carbon::setLocale(app()->getLocale());

        [$penjualanData, $produksiData, $pesananData, $categories, $chartTitle] = $this->buildChartData($range, $now);

        return response()->json([
            'penjualanData' => $penjualanData,
            'produksiData' => $produksiData,
            'pesananData' => $pesananData,
            'categories' => $categories,
            'chartTitle' => $chartTitle,
        ]);
    }

    public function dashboard(Request $request)
    {
        $pageTitle = 'Dashboard';
        $range = $request->get('range', 'weekly');
        $range = in_array($range, ['weekly', 'monthly', 'yearly'], true) ? $range : 'weekly';

        $now = Carbon::now();
        Carbon::setLocale(app()->getLocale());
        $today = $now->copy()->startOfDay();

        $totalPesananBaru = Pesanan::where('status', 'baru')->count();
        $produkTerjual = (int) round(
            TransaksiItem::whereHas('transaksi', function ($query) {
                $query->where('jenis_transaksi', 'penjualan')->where('status', 'selesai');
            })->sum('jumlah')
        );
        $totalProduksi = Produksi::count();
        $totalUser = User::count();

        $lastMonthDate = $now->copy()->subMonthNoOverflow();
        $lastMonthStart = $lastMonthDate->copy()->startOfMonth();
        $lastMonthEnd = $lastMonthDate->copy()->endOfMonth();

        $prevMonthDate = $lastMonthDate->copy()->subMonthNoOverflow();
        $prevMonthStart = $prevMonthDate->copy()->startOfMonth();
        $prevMonthEnd = $prevMonthDate->copy()->endOfMonth();

        // For testing purposes, use current month data instead of last month
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();

        $totalPendapatanLastMonth = (float) Transaksi::whereBetween('tanggal_transaksi', [$currentMonthStart, $currentMonthEnd])
            ->where('jenis_transaksi', 'penjualan')
            ->where('status', 'selesai')
            ->sum('total');

        $totalBiayaLastMonth = (float) Transaksi::whereBetween('tanggal_transaksi', [$currentMonthStart, $currentMonthEnd])
            ->where('jenis_transaksi', 'pembelian')
            ->where('status', 'selesai')
            ->sum('total');

        if ($totalBiayaLastMonth <= 0) {
            $totalBiayaLastMonth = (float) Produksi::whereBetween('tanggal_produksi', [$currentMonthStart, $currentMonthEnd])
                ->where('status', 'selesai')
                ->sum('biaya_produksi');
        }

        $totalPendapatanPrevMonth = (float) Transaksi::whereBetween('tanggal_transaksi', [$lastMonthStart, $lastMonthEnd])
            ->where('jenis_transaksi', 'penjualan')
            ->where('status', 'selesai')
            ->sum('total');

        $totalBiayaPrevMonth = (float) Transaksi::whereBetween('tanggal_transaksi', [$lastMonthStart, $lastMonthEnd])
            ->where('jenis_transaksi', 'pembelian')
            ->where('status', 'selesai')
            ->sum('total');

        if ($totalBiayaPrevMonth <= 0) {
            $totalBiayaPrevMonth = (float) Produksi::whereBetween('tanggal_produksi', [$lastMonthStart, $lastMonthEnd])
                ->where('status', 'selesai')
                ->sum('biaya_produksi');
        }

        $totalLabaLastMonth = $totalPendapatanLastMonth - $totalBiayaLastMonth;
        $totalLabaPrevMonth = $totalPendapatanPrevMonth - $totalBiayaPrevMonth;

        $pctPendapatan = $this->computePctChange($totalPendapatanLastMonth, $totalPendapatanPrevMonth);
        $pctBiaya = $this->computePctChange($totalBiayaLastMonth, $totalBiayaPrevMonth);
        $pctLaba = $this->computePctChange($totalLabaLastMonth, $totalLabaPrevMonth);

        $dirPendapatan = $this->directionFromChange($totalPendapatanLastMonth, $totalPendapatanPrevMonth);
        $dirBiaya = $this->directionFromChange($totalBiayaLastMonth, $totalBiayaPrevMonth);
        $dirLaba = $this->directionFromChange($totalLabaLastMonth, $totalLabaPrevMonth);

        $targetPendapatan = (float) setting('total_pendapatan_target', 0);
        $targetBiaya = (float) setting('total_biaya_target', 0);
        $targetLaba = (float) setting('total_laba_target', 0);

        $targetDirPendapatan = $this->directionFromTarget($totalPendapatanLastMonth, $targetPendapatan);
        $targetDirBiaya = $this->directionFromTarget($totalBiayaLastMonth, $targetBiaya);
        $targetDirLaba = $this->directionFromTarget($totalLabaLastMonth, $targetLaba);

        $targetPctPendapatan = $this->targetProgressPercentage($totalPendapatanLastMonth, $targetPendapatan);
        $targetPctBiaya = $this->targetProgressPercentage($totalBiayaLastMonth, $targetBiaya);
        $targetPctLaba = $this->targetProgressPercentage($totalLabaLastMonth, $targetLaba);

        $summaryCards = [
            [
                'id' => 'card1-title',
                'value' => $totalPesananBaru,
                'formatted' => number_format($totalPesananBaru, 0, ',', '.'),
                'label' => 'Pesanan Baru',
                'icon' => 'fas fa-shopping-bag',
                'background_class' => 'bolopa-bg-teal',
                'text_class' => 'text-white',
                'icon_wrapper_class' => 'bolopa-card-icon',
                'footer_text_class' => 'text-white-50',
                'link' => route('backoffice.pesanan.index'),
                'aria_label' => number_format($totalPesananBaru, 0, ',', '.') . ' pesanan baru',
            ],
            [
                'id' => 'card2-title',
                'value' => $produkTerjual,
                'formatted' => number_format($produkTerjual, 0, ',', '.'),
                'label' => 'Produk Terjual',
                'icon' => 'fas fa-box',
                'background_class' => 'bolopa-bg-success-variant',
                'text_class' => 'text-white',
                'icon_wrapper_class' => 'bolopa-card-icon',
                'footer_text_class' => 'text-white-50',
                'link' => route('backoffice.transaksi.index'),
                'aria_label' => number_format($produkTerjual, 0, ',', '.') . ' produk terjual',
            ],
            [
                'id' => 'card3-title',
                'value' => $totalProduksi,
                'formatted' => number_format($totalProduksi, 0, ',', '.'),
                'label' => 'Produksi',
                'icon' => 'fas fa-cogs',
                'background_class' => 'bolopa-bg-warning-variant',
                'text_class' => 'text-dark',
                'icon_wrapper_class' => 'bolopa-card-icon text-dark',
                'footer_text_class' => 'text-dark-50',
                'link' => route('backoffice.produksi.index'),
                'aria_label' => number_format($totalProduksi, 0, ',', '.') . ' produksi',
            ],
            [
                'id' => 'card4-title',
                'value' => $totalUser,
                'formatted' => number_format($totalUser, 0, ',', '.'),
                'label' => 'User Aktif',
                'icon' => 'fas fa-users',
                'background_class' => 'bolopa-bg-danger-variant',
                'text_class' => 'text-white',
                'icon_wrapper_class' => 'bolopa-card-icon',
                'footer_text_class' => 'text-white-50',
                'link' => route('backoffice.master-user.index'),
                'aria_label' => number_format($totalUser, 0, ',', '.') . ' user aktif',
            ],
        ];

        $financialSummary = [
            [
                'label' => 'TOTAL PENDAPATAN',
                'formatted' => 'Rp ' . number_format($totalPendapatanLastMonth, 0, ',', '.'),
                'pct' => $pctPendapatan,
                'direction' => $dirPendapatan,
                'pct_class' => $this->directionToTextClass($dirPendapatan),
                'card_class' => 'bolopa-bg-success',
                'icon' => 'fas fa-dollar-sign',
            ],
            [
                'label' => 'TOTAL BIAYA',
                'formatted' => 'Rp ' . number_format($totalBiayaLastMonth, 0, ',', '.'),
                'pct' => $pctBiaya,
                'direction' => $dirBiaya,
                'pct_class' => $this->directionToTextClass($dirBiaya),
                'card_class' => 'bolopa-bg-danger',
                'icon' => 'fas fa-money-bill-wave',
            ],
            [
                'label' => 'TOTAL LABA',
                'formatted' => 'Rp ' . number_format($totalLabaLastMonth, 0, ',', '.'),
                'pct' => $pctLaba,
                'direction' => $dirLaba,
                'pct_class' => $this->directionToTextClass($dirLaba),
                'card_class' => 'bolopa-bg-primary',
                'icon' => 'fas fa-chart-line',
            ],
        ];

        [$penjualanData, $produksiData, $pesananData, $categories, $chartTitle] = $this->buildChartData($range, $now);

        $goals = $this->buildGoals($currentMonthStart, $currentMonthEnd, $today);
        $goalCount = count($goals);
        $completedGoals = count(array_filter($goals, function($goal) {
            return ($goal['pct'] ?? 0) >= 100;
        }));
        $goalCompletionRate = $goalCount > 0 ? (int) round(($completedGoals / $goalCount) * 100) : 0;
        $goalDirection = $goalCompletionRate >= 100 ? 'up' : ($goalCompletionRate >= 80 ? 'same' : 'down');
        if ($goalCount === 0) {
            $goalDirection = 'same';
        }

        $statsStrip = [
            [
                'label' => 'TOTAL PENDAPATAN',
                'amount' => 'Rp ' . number_format($totalPendapatanLastMonth, 0, ',', '.'),
                'pct' => $targetPctPendapatan,
                'direction' => $targetDirPendapatan,
                'pct_class' => $this->directionToTextClass($targetDirPendapatan),
            ],
            [
                'label' => 'TOTAL BIAYA',
                'amount' => 'Rp ' . number_format($totalBiayaLastMonth, 0, ',', '.'),
                'pct' => $targetPctBiaya,
                'direction' => $targetDirBiaya,
                'pct_class' => $this->directionToTextClass($targetDirBiaya),
            ],
            [
                'label' => 'TOTAL LABA',
                'amount' => 'Rp ' . number_format($totalLabaLastMonth, 0, ',', '.'),
                'pct' => $targetPctLaba,
                'direction' => $targetDirLaba,
                'pct_class' => $this->directionToTextClass($targetDirLaba),
            ],
            [
                'label' => 'PENYELESAIAN TUJUAN',
                'amount' => $completedGoals . '/' . $goalCount,
                'pct' => $goalCompletionRate,
                'direction' => $goalDirection,
                'pct_class' => $this->directionToTextClass($goalDirection),
            ],
        ];

        $lastOrders = Pesanan::orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function (Pesanan $order) {
                $status = $order->status;
                $badgeClass = match ($status) {
                    'selesai' => 'badge-success',
                    'diproses' => 'badge-warning',
                    'dibatalkan' => 'badge-danger',
                    default => 'badge-secondary',
                };

                return [
                    'id' => $order->kode_pesanan ?? ('#ORD-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT)),
                    'customer' => $order->nama_pelanggan ?? 'N/A',
                    'amount' => 'Rp ' . number_format((float) $order->total_harga, 0, ',', '.'),
                    'time' => $order->created_at ? $order->created_at->diffForHumans() : '-',
                    'status' => $status,
                    'status_label' => $order->status_label,
                    'badge_class' => $badgeClass,
                ];
            })
            ->values()
            ->toArray();

        $bahanBaku = MasterBahanBaku::with(['bahanBakusAktif:id,master_bahan_id,stok'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'pageTitle' => $pageTitle,
            'summaryCards' => $summaryCards,
            'financialSummary' => $financialSummary,
            'statsStrip' => $statsStrip,
            'penjualanData' => $penjualanData,
            'produksiData' => $produksiData,
            'pesananData' => $pesananData,
            'categories' => $categories,
            'chartTitle' => $chartTitle,
            'range' => $range,
            'goals' => $goals,
            'lastOrders' => $lastOrders,
            'bahanBaku' => $bahanBaku,
        ]);
    }

    private function buildChartData(string $range, Carbon $now): array
    {
        $penjualanData = [];
        $produksiData = [];
        $pesananData = [];
        $categories = [];
        $title = '';

        switch ($range) {
            case 'monthly':
                $start = $now->copy()->startOfMonth();
                $days = $start->daysInMonth;

                for ($i = 0; $i < $days; $i++) {
                    $date = $start->copy()->addDays($i);
                    $penjualanData[] = (float) Transaksi::whereDate('tanggal_transaksi', $date)
                        ->where('jenis_transaksi', 'penjualan')
                        ->where('status', 'selesai')
                        ->sum('total');
                    $produksiData[] = (int) Produksi::whereDate('created_at', $date)->count();
                    $pesananData[] = (int) Pesanan::whereDate('created_at', $date)
                        ->where('status', 'baru')
                        ->count();
                    $categories[] = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                }
                $title = 'Ringkasan Bulanan: Penjualan, Produksi & Pesanan';
                break;

            case 'yearly':
                $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                for ($month = 1; $month <= 12; $month++) {
                    $penjualanData[] = (float) Transaksi::whereYear('tanggal_transaksi', $now->year)
                        ->whereMonth('tanggal_transaksi', $month)
                        ->where('jenis_transaksi', 'penjualan')
                        ->where('status', 'selesai')
                        ->sum('total');
                    $produksiData[] = (int) Produksi::whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $month)
                        ->count();
                    $pesananData[] = (int) Pesanan::whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $month)
                        ->where('status', 'baru')
                        ->count();
                }
                $categories = $monthLabels;
                $title = 'Ringkasan Tahunan: Penjualan, Produksi & Pesanan';
                break;

            default:
                $start = $now->copy()->subDays(6);

                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i);
                    $penjualanData[] = (float) Transaksi::whereDate('tanggal_transaksi', $date)
                        ->where('jenis_transaksi', 'penjualan')
                        ->where('status', 'selesai')
                        ->sum('total');
                    $produksiData[] = (int) Produksi::whereDate('created_at', $date)->count();
                    $pesananData[] = (int) Pesanan::whereDate('created_at', $date)
                        ->where('status', 'baru')
                        ->count();
                    $categories[] = ucfirst($date->locale('id')->isoFormat('ddd'));
                }
                $title = 'Ringkasan Harian: Penjualan, Produksi & Pesanan';
                break;
        }

        return [$penjualanData, $produksiData, $pesananData, $categories, $title];
    }

    private function buildGoals(Carbon $lastMonthStart, Carbon $lastMonthEnd, Carbon $today): array
    {
        $goalsSetting = Pengaturan::where('nama_pengaturan', 'dashboard_goals')->value('nilai');

        if (!$goalsSetting) {
            return [];
        }

        $decoded = is_array($goalsSetting) ? $goalsSetting : json_decode($goalsSetting, true);
        if (!is_array($decoded)) {
            return [];
        }

        $goals = [];
        foreach ($decoded as $goal) {
            if (!is_array($goal)) {
                continue;
            }

            $target = isset($goal['target']) ? (float) $goal['target'] : 0.0;
            if ($target <= 0) {
                continue;
            }

            $key = strtolower($goal['key'] ?? ($goal['label'] ?? '')); 
            if ($key === '') {
                continue;
            }

            $label = $goal['label'] ?? ucfirst($key);
            $color = $goal['color'] ?? '#007bff';
            $value = $this->resolveGoalValue($key, $lastMonthStart, $lastMonthEnd, $today);
            $pct = $target > 0 ? (int) round(($value / $target) * 100) : 0;

            $stateClass = '';
            if ($pct >= 100) {
                $stateClass = 'at-target';
            } elseif ($pct >= 80) {
                $stateClass = 'nearing-target';
            }

            $goals[] = [
                'key' => $key,
                'label' => $label,
                'value' => $value,
                'target' => $target,
                'pct' => max(0, $pct),
                'color_class' => $this->mapGoalColorToClass($color),
                'state_class' => $stateClass,
            ];
        }

        return $goals;
    }

    private function resolveGoalValue(string $key, Carbon $lastMonthStart, Carbon $lastMonthEnd, Carbon $today): float
    {
        return match ($key) {
            'produk' => (float) Produk::count(),
            'penjualan' => (float) Transaksi::whereBetween('tanggal_transaksi', [$lastMonthStart, $lastMonthEnd])
                ->where('jenis_transaksi', 'penjualan')
                ->where('status', 'selesai')
                ->sum('total'),
            'bahan_baku' => (float) MasterBahanBaku::count(),
            'produksi' => (float) Produksi::whereBetween('tanggal_produksi', [$lastMonthStart, $lastMonthEnd])
                ->where('status', 'selesai')
                ->sum('jumlah_hasil'),
            'pesanan' => (float) Pesanan::whereBetween('tanggal_pesanan', [$lastMonthStart, $lastMonthEnd])->count(),
            'user' => (float) User::count(),
            'packing' => (float) Produksi::where('status', 'selesai')
                ->whereDate('created_at', $today)
                ->count(),
            'qc' => (float) Produksi::where('status', 'proses')
                ->whereDate('created_at', $today)
                ->count(),
            default => 0.0,
        };
    }

    private function directionToTextClass(string $direction): string
    {
        return match ($direction) {
            'up' => 'text-success',
            'down' => 'text-danger',
            default => 'text-warning',
        };
    }

    private function mapGoalColorToClass(string $color): string
    {
        $normalized = strtolower(trim($color));

        $nameMap = [
            'blue' => 'blue',
            'red' => 'red',
            'green' => 'green',
            'yellow' => 'yellow',
            'purple' => 'purple',
            'orange' => 'orange',
        ];

        if (isset($nameMap[$normalized])) {
            return $nameMap[$normalized];
        }

        if (strpos($normalized, '#') !== 0) {
            return 'blue';
        }

        $colorMap = [
            '#007bff' => 'blue',
            '#ff0000' => 'red',
            '#dc3545' => 'red',
            '#28a745' => 'green',
            '#2ecc71' => 'green',
            '#ffc107' => 'yellow',
            '#f39c12' => 'yellow',
            '#9b59b6' => 'purple',
            '#8e44ad' => 'purple',
            '#e67e22' => 'orange',
            '#d35400' => 'orange',
        ];

        return $colorMap[$normalized] ?? 'blue';
    }

    /**
     * Compute percent change from previous to current value.
     * Returns integer percent (rounded).
     */
    private function computePctChange($current, $previous)
    {
        if ($previous == 0) {
            return $current == 0 ? 0 : 100;
        }

        return (int) round((($current - $previous) / max(1, $previous)) * 100);
    }

    private function directionFromChange($current, $previous)
    {
        if ($current > $previous) return 'up';
        if ($current < $previous) return 'down';
        return 'same';
    }

    private function directionFromTarget($current, $target)
    {
        if ($target <= 0) {
            return 'same';
        }
        if ($current > $target) {
            return 'up';
        }
        if ($current < $target) {
            return 'down';
        }
        return 'same';
    }

    private function targetProgressPercentage($current, $target)
    {
        if ($target <= 0) {
            return 0;
        }

        return (float) round(($current / $target) * 100, 1);
    }

    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->forget('role');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    // Redirect ke form login backoffice
    return redirect()->route('backoffice.login');
    }
}
