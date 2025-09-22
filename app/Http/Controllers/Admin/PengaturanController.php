<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengaturan;
use App\Models\BahanBaku;
use App\Models\StokBahanBaku;
use App\Models\Produk;
use App\Models\StokProduk;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaturans = Pengaturan::orderBy('nama_pengaturan')->get();
        return view('admin.pages.pengaturan.index-pengaturan', compact('pengaturans'));
    }

    /**
     * Trigger a database backup (creates a SQL dump in storage/app/backups).
     */
    public function backupDatabase(Request $request)
    {
        // Ensure directory exists
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $fullPath = $backupPath . DIRECTORY_SEPARATOR . $filename;

        // Try using mysqldump if available
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbName = config('database.connections.mysql.database');

        $command = "mysqldump --host={$dbHost} --port={$dbPort} --user={$dbUser} --password=\"{$dbPass}\" {$dbName} > " . escapeshellarg($fullPath);
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'Backup gagal. Pastikan mysqldump tersedia di sistem.');
        }

        return redirect()->back()->with('success', "Backup berhasil: {$filename}");
    }

    /**
     * Display alerts and notifications
     */
    public function alerts()
    {
        $stockAlerts = $this->getStockAlerts();
        $productionAlerts = $this->getProductionAlerts();
        $expiryAlerts = $this->getExpiryAlerts();

        return view('admin.pages.pengaturan.alerts', compact('stockAlerts', 'productionAlerts', 'expiryAlerts'));
    }

    /**
     * Get stock alerts for low inventory
     */
    private function getStockAlerts()
    {
        $alerts = [];

        // Check bahan baku stock
        $bahanBakus = BahanBaku::aktif()->get();
        foreach ($bahanBakus as $bahan) {
            $totalStok = StokBahanBaku::where('bahan_baku_id', $bahan->id)
                ->sum('sisa_stok');

            $minStock = $bahan->stok_minimum ?? 10; // Default minimum stock

            if ($totalStok <= $minStock) {
                $alerts[] = [
                    'type' => 'bahan_baku',
                    'item' => $bahan,
                    'current_stock' => $totalStok,
                    'min_stock' => $minStock,
                    'severity' => $totalStok <= 0 ? 'critical' : 'warning',
                    'message' => "Stok {$bahan->nama_bahan} rendah"
                ];
            }
        }

        // Check produk stock
        $produks = Produk::aktif()->get();
        foreach ($produks as $produk) {
            $totalStok = StokProduk::where('produk_id', $produk->id)
                ->sum('sisa_stok');

            $minStock = $produk->minimum_stok ?? 5; // Default minimum stock

            if ($totalStok <= $minStock) {
                $alerts[] = [
                    'type' => 'produk',
                    'item' => $produk,
                    'current_stock' => $totalStok,
                    'min_stock' => $minStock,
                    'severity' => $totalStok <= 0 ? 'critical' : 'warning',
                    'message' => "Stok {$produk->nama_produk} rendah"
                ];
            }
        }

        return $alerts;
    }

    /**
     * Get production alerts
     */
    private function getProductionAlerts()
    {
        $alerts = [];

        // Check for pending productions that are overdue
        $overdueProductions = \App\Models\Produksi::where('status', 'pending')
            ->where('tanggal_produksi', '<', now()->subDays(1))
            ->with('produk')
            ->get();

        foreach ($overdueProductions as $produksi) {
            $alerts[] = [
                'type' => 'production',
                'item' => $produksi,
                'severity' => 'warning',
                'message' => "Produksi {$produksi->nomor_produksi} terlambat"
            ];
        }

        // Check for furnace utilization
        $activeFurnaces = \App\Models\Tungku::whereHas('batchProduksis', function($query) {
            $query->where('status', 'proses');
        })->count();

        $totalFurnaces = \App\Models\Tungku::aktif()->count();

        if ($activeFurnaces == 0 && $totalFurnaces > 0) {
            $alerts[] = [
                'type' => 'furnace',
                'severity' => 'info',
                'message' => 'Tidak ada tungku yang sedang digunakan'
            ];
        } elseif ($activeFurnaces == $totalFurnaces && $totalFurnaces > 0) {
            $alerts[] = [
                'type' => 'furnace',
                'severity' => 'warning',
                'message' => 'Semua tungku sedang digunakan'
            ];
        }

        return $alerts;
    }

    /**
     * Get expiry alerts
     */
    private function getExpiryAlerts()
    {
        $alerts = [];

        // Check bahan baku expiry
        $expiringBahanBaku = StokBahanBaku::where('tanggal_kadaluarsa', '<=', now()->addDays(30))
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where('sisa_stok', '>', 0)
            ->with('bahanBaku')
            ->get();

        foreach ($expiringBahanBaku as $stok) {
            $daysLeft = now()->diffInDays($stok->tanggal_kadaluarsa);
            $alerts[] = [
                'type' => 'expiry_bahan',
                'item' => $stok,
                'days_left' => $daysLeft,
                'severity' => $daysLeft <= 7 ? 'critical' : 'warning',
                'message' => "Batch {$stok->nomor_batch} {$stok->bahanBaku->nama_bahan} akan kadaluarsa dalam {$daysLeft} hari"
            ];
        }

        // Check produk expiry
        $expiringProduk = StokProduk::where('tanggal_kadaluarsa', '<=', now()->addDays(30))
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where('sisa_stok', '>', 0)
            ->with('produk')
            ->get();

        foreach ($expiringProduk as $stok) {
            $daysLeft = now()->diffInDays($stok->tanggal_kadaluarsa);
            $alerts[] = [
                'type' => 'expiry_produk',
                'item' => $stok,
                'days_left' => $daysLeft,
                'severity' => $daysLeft <= 7 ? 'critical' : 'warning',
                'message' => "{$stok->produk->nama_produk} grade {$stok->grade_kualitas} akan kadaluarsa dalam {$daysLeft} hari"
            ];
        }

        return $alerts;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.pengaturan.create-pengaturan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('backoffice.pengaturan.index')->with('success', 'Pengaturan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.pages.pengaturan.show-pengaturan', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.pages.pengaturan.edit-pengaturan', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update logic
        return redirect()->route('backoffice.pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Save dashboard goal values (from the Pengaturan -> Target Dashboard form).
     */
    public function saveDashboardGoal(Request $request)
    {
        $data = $request->validate([
            'monthly_sales_goal' => 'nullable|numeric|min:0',
            'monthly_production_goal' => 'nullable|integer|min:0',
            'monthly_order_goal' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0|max:100',
        ]);

        // Use helper set_setting to persist
        if (isset($data['monthly_sales_goal'])) {
            set_setting('monthly_sales_goal', (string) $data['monthly_sales_goal'], 'decimal');
        }
        if (isset($data['monthly_production_goal'])) {
            set_setting('monthly_production_goal', (string) $data['monthly_production_goal'], 'integer');
        }
        if (isset($data['monthly_order_goal'])) {
            set_setting('monthly_order_goal', (string) $data['monthly_order_goal'], 'integer');
        }
        if (isset($data['low_stock_threshold'])) {
            set_setting('low_stock_threshold', (string) $data['low_stock_threshold'], 'integer');
        }

        // Clear cached settings (the helper caches in static variable)
        if (function_exists('cache')) {
            // best-effort: clear any cache key used for settings if implemented
            try { cache()->forget('app_settings'); } catch (\Exception $e) { /* ignore */ }
        }

        return redirect()->route('backoffice.pengaturan.index')->with('success', 'Target dashboard berhasil disimpan.');
    }

    /**
     * Show form to manage a list of dashboard goals (stored as JSON in pengaturans.dashboard_goals)
     */
    public function goals()
    {
        // Stored as JSON in 'dashboard_goals'
        $raw = Pengaturan::where('nama_pengaturan', 'dashboard_goals')->first();
        $goals = [];
        if ($raw) {
            $decoded = json_decode($raw->nilai, true);
            if (is_array($decoded)) $goals = $decoded;
        }

        return view('admin.pages.pengaturan.goals', compact('goals'));
    }

    /**
     * Save the whole list of dashboard goals (JSON payload)
     */
    public function saveGoalsList(Request $request)
    {
        $data = $request->validate([
            'goals' => 'nullable|array',
            'goals.*.label' => 'required_with:goals|string',
            'goals.*.key' => 'required_with:goals|string',
            'goals.*.target' => 'required_with:goals|numeric|min:0',
            'goals.*.color' => 'nullable|string'
        ]);

        $goals = $data['goals'] ?? [];
        // Persist as JSON string
        set_setting('dashboard_goals', json_encode(array_values($goals)), 'json');

        // Clear helper cache if needed (best-effort)
        if (function_exists('cache')) {
            try { cache()->forget('app_settings'); } catch (\Exception $e) { }
        }

        return redirect()->route('backoffice.pengaturan.goals')->with('success', 'Daftar goals berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement destroy logic
        return redirect()->route('backoffice.pengaturan.index')->with('success', 'Pengaturan berhasil dihapus.');
    }
}
