<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Produk;

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

    public function dashboard()
    {
        // Dashboard metrics mapped to cocofarma domain
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // New Orders today and percent change vs yesterday
        $newOrdersToday = Pesanan::whereDate('tanggal_pesanan', $today)->count();
        $newOrdersYesterday = Pesanan::whereDate('tanggal_pesanan', $yesterday)->count();
        $newOrdersPct = $this->computePctChange($newOrdersToday, $newOrdersYesterday);
        $newOrdersDir = $this->directionFromChange($newOrdersToday, $newOrdersYesterday);

    // Production total over last 7 days and percent change vs previous 7-day window
    $periodEnd = Carbon::now()->endOfDay();
    $periodStart = Carbon::now()->subDays(6)->startOfDay();
    $produksiLast7 = \App\Models\Produksi::whereBetween('tanggal_produksi', [$periodStart, $periodEnd])->where('status','selesai')->sum('jumlah_hasil');

    // previous 7-day window for comparison
    $prevStart = Carbon::now()->subDays(13)->startOfDay();
    $prevEnd = Carbon::now()->subDays(7)->endOfDay();
    $produksiPrev7 = \App\Models\Produksi::whereBetween('tanggal_produksi', [$prevStart, $prevEnd])->where('status','selesai')->sum('jumlah_hasil');

    $produksiPct = $this->computePctChange($produksiLast7, $produksiPrev7);
    $produksiDir = $this->directionFromChange($produksiLast7, $produksiPrev7);

        // New user registrations today
        $usersToday = User::whereDate('created_at', $today)->count();
        $usersYesterday = User::whereDate('created_at', $yesterday)->count();
        $usersPct = $this->computePctChange($usersToday, $usersYesterday);
        $usersDir = $this->directionFromChange($usersToday, $usersYesterday);

        // Unique customers (distinct nama_pelanggan) in last 30 days vs previous 30 days
        $uniqueNowStart = Carbon::now()->subDays(29)->startOfDay();
        $uniqueNowEnd = Carbon::now()->endOfDay();
        $uniqueVisitors = Pesanan::whereBetween('tanggal_pesanan', [$uniqueNowStart, $uniqueNowEnd])->distinct('nama_pelanggan')->count('nama_pelanggan');

        $uniquePrevStart = Carbon::now()->subDays(59)->startOfDay();
        $uniquePrevEnd = Carbon::now()->subDays(30)->endOfDay();
        $uniquePrev = Pesanan::whereBetween('tanggal_pesanan', [$uniquePrevStart, $uniquePrevEnd])->distinct('nama_pelanggan')->count('nama_pelanggan');
        $uniquePct = $this->computePctChange($uniqueVisitors, $uniquePrev);
        $uniqueDir = $this->directionFromChange($uniqueVisitors, $uniquePrev);

        // Chart data: last 6 months produksi and penjualan
        $chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            $monthName = $date->format('M');

            $produksiSum = \App\Models\Produksi::whereBetween('tanggal_produksi', [$monthStart, $monthEnd])->where('status','selesai')->sum('jumlah_hasil');
            $penjualanSum = Transaksi::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])->where('jenis_transaksi','penjualan')->where('status','selesai')->sum('total');

            $chart[] = [
                'month' => $monthName,
                'produksi' => (int)$produksiSum,
                'penjualan' => (float)$penjualanSum
            ];
        }

        // Goals: attempt to read a JSON list from Pengaturan 'dashboard_goals'.
        // Each goal in list should have: key, label, target, color (optional)
        $rawGoals = setting('dashboard_goals', null);
        $goals = [];

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Helper to compute current value by known key
        $computeValueByKey = function ($key) use ($monthStart, $monthEnd) {
            $key = strtolower($key);
            switch ($key) {
                case 'produksi':
                case 'production':
                    return (int) \App\Models\Produksi::whereBetween('tanggal_produksi', [$monthStart, $monthEnd])->where('status','selesai')->sum('jumlah_hasil');
                case 'penjualan':
                case 'sales':
                    return (float) Transaksi::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])->where('jenis_transaksi','penjualan')->where('status','selesai')->sum('total');
                case 'pesanan':
                case 'orders':
                    return (int) Pesanan::whereBetween('tanggal_pesanan', [$monthStart, $monthEnd])->count();
                case 'users':
                case 'registrations':
                    return (int) User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
                default:
                    // Unknown key: attempt to read a setting with that key as fallback
                    $val = setting($key, 0);
                    return is_numeric($val) ? $val : 0;
            }
        };

        if ($rawGoals) {
            $decoded = null;
            if (is_string($rawGoals)) {
                $decoded = json_decode($rawGoals, true);
            } elseif (is_array($rawGoals)) {
                $decoded = $rawGoals;
            }

            if (is_array($decoded)) {
                foreach ($decoded as $g) {
                    $key = $g['key'] ?? ($g['label'] ?? 'unknown');
                    $label = $g['label'] ?? ucfirst($key);
                    $target = isset($g['target']) ? (float)$g['target'] : 0;
                    $color = $g['color'] ?? 'green';
                    $value = $computeValueByKey($key);
                    $pct = $target > 0 ? (int) round(($value / $target) * 100) : 0;

                    $goals[] = [
                        'key' => $key,
                        'label' => $label,
                        'value' => $value,
                        'target' => $target,
                        'pct' => $pct,
                        'color' => $color
                    ];
                }
            }
        }

        // Fallback: if no goals persisted, keep default three goals for backwards compatibility
        if (empty($goals)) {
            $monthlyProductionTarget = (int) setting('monthly_production_goal', 200);
            $monthlySalesTarget = (float) setting('monthly_sales_goal', 50000000);
            $monthlyOrderTarget = (int) setting('monthly_order_goal', 50);

            $produksiThisMonth = (int) \App\Models\Produksi::whereBetween('tanggal_produksi', [$monthStart, $monthEnd])->where('status','selesai')->sum('jumlah_hasil');
            $salesThisMonth = (float) Transaksi::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])->where('jenis_transaksi','penjualan')->where('status','selesai')->sum('total');
            $ordersThisMonth = (int) Pesanan::whereBetween('tanggal_pesanan', [$monthStart, $monthEnd])->count();

            $goals = [
                [
                    'key' => 'produksi',
                    'label' => 'Produksi',
                    'value' => $produksiThisMonth,
                    'target' => $monthlyProductionTarget,
                    'pct' => $monthlyProductionTarget > 0 ? (int) round(($produksiThisMonth / $monthlyProductionTarget) * 100) : 0,
                    'color' => 'green'
                ],
                [
                    'key' => 'penjualan',
                    'label' => 'Penjualan',
                    'value' => $salesThisMonth,
                    'target' => $monthlySalesTarget,
                    'pct' => $monthlySalesTarget > 0 ? (int) round(($salesThisMonth / $monthlySalesTarget) * 100) : 0,
                    'color' => 'yellow'
                ],
                [
                    'key' => 'pesanan',
                    'label' => 'Pesanan',
                    'value' => $ordersThisMonth,
                    'target' => $monthlyOrderTarget,
                    'pct' => $monthlyOrderTarget > 0 ? (int) round(($ordersThisMonth / $monthlyOrderTarget) * 100) : 0,
                    'color' => 'red'
                ]
            ];
        }

        // Last orders: fetch recent Pesanan and Transaksi (mixed) limited to 10 for brevity
        $recentProductions = \App\Models\Produksi::with('produk')->orderBy('tanggal_produksi','desc')->limit(5)->get()->map(function($p){
            return [
                'type' => 'Produksi',
                'label' => $p->produk->nama_produk ?? 'Unknown',
                'date' => $p->tanggal_produksi->format('Y-m-d'),
                'meta' => $p->jumlah_hasil
            ];
        });

        $recentOrders = Pesanan::orderBy('tanggal_pesanan','desc')->limit(5)->get()->map(function($o){
            return [
                'type' => 'Pesanan',
                'label' => $o->kode_pesanan ?? ('Order#'.$o->id),
                'date' => $o->tanggal_pesanan->format('Y-m-d'),
                'meta' => $o->total_harga
            ];
        });

        $lastActivities = $recentProductions->concat($recentOrders)->sortByDesc('date')->values()->all();

        return view('admin.dashboard', compact(
            'newOrdersToday', 'newOrdersPct', 'newOrdersDir',
            // production metrics (replaces previous cancelRate variables)
            'produksiLast7', 'produksiPct', 'produksiDir',
            'usersToday', 'usersPct', 'usersDir',
            'uniqueVisitors', 'uniquePct', 'uniqueDir',
            'chart', 'goals', 'lastActivities'
        ));
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
