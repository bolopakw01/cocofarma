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
use App\Services\DashboardPerformanceService;

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
     * Display the form for editing dashboard metric targets.
     */
    public function dashboardMetrics()
    {
        $metrics = [
            'total_pendapatan' => setting('total_pendapatan_target', ''),
            'total_biaya' => setting('total_biaya_target', ''),
            'total_laba' => setting('total_laba_target', ''),
        ];
        return view('admin.pages.pengaturan.dashboard-metrics', compact('metrics'));
    }

    /**
     * Manage performance configuration used by the dashboard radar chart.
     */
    public function performance()
    {
        $metrics = Pengaturan::getDashboardPerformanceMetrics();
        $defaultMetrics = Pengaturan::defaultPerformanceMetrics();
        $liveMetrics = DashboardPerformanceService::enrich($metrics);
        $totalActual = collect($liveMetrics)->sum('actual');

        return view('admin.pages.pengaturan.performance', compact('metrics', 'defaultMetrics', 'liveMetrics', 'totalActual'));
    }

    /**
     * Persist dashboard metric targets from the pengaturan form.
     */
    public function saveDashboardMetrics(Request $request)
    {
        $data = $request->validate([
            'total_pendapatan' => 'nullable|numeric|min:0',
            'total_biaya' => 'nullable|numeric|min:0',
            'total_laba' => 'nullable|numeric|min:0',
        ]);

        if (array_key_exists('total_pendapatan', $data)) {
            set_setting('total_pendapatan_target', (string) $data['total_pendapatan'], 'decimal');
        }
        if (array_key_exists('total_biaya', $data)) {
            set_setting('total_biaya_target', (string) $data['total_biaya'], 'decimal');
        }
        if (array_key_exists('total_laba', $data)) {
            set_setting('total_laba_target', (string) $data['total_laba'], 'decimal');
        }

        if (function_exists('cache')) {
            try {
                cache()->forget('app_settings');
            } catch (\Exception $e) {
                /* ignore */
            }
        }

        return redirect()->route('backoffice.pengaturan.dashboard-metrics')->with('success', 'Target dashboard metrics berhasil disimpan.');
    }

    /**
     * Store performance indicators for dashboard radar visualization.
     */
    public function savePerformance(Request $request)
    {
        $data = $request->validate([
            'metrics' => 'nullable|array',
            'metrics.*.label' => 'required_with:metrics|string|max:100',
            'metrics.*.key' => 'nullable|string|max:100',
            'metrics.*.target' => 'required_with:metrics|numeric|min:0',
            'metrics.*.benchmark' => 'required_with:metrics|numeric|min:0',
            'metrics.*.description' => 'nullable|string|max:200',
        ]);

        $payload = [];
        if (!empty($data['metrics'])) {
            foreach ($data['metrics'] as $index => $metric) {
                $key = !empty($metric['key']) ? trim($metric['key']) : $this->generatePerformanceKey($metric['label'], $index);
                $payload[] = [
                    'label' => trim($metric['label']),
                    'key' => $key,
                    'target' => (float) $metric['target'],
                    'benchmark' => (float) $metric['benchmark'],
                    'description' => trim($metric['description'] ?? ''),
                ];
            }

            $keys = array_map('strtolower', array_column($payload, 'key'));
            if (count($keys) !== count(array_unique($keys))) {
                return redirect()->back()
                    ->withErrors(['metrics' => 'Setiap nama indikator performance harus unik.'])
                    ->withInput();
            }
        }

        set_setting('dashboard_performance_metrics', json_encode($payload), 'json');

        if (function_exists('cache')) {
            try { cache()->forget('app_settings'); } catch (\Exception $e) { }
        }

        return redirect()->route('backoffice.pengaturan.performance')->with('success', 'Konfigurasi performance berhasil disimpan.');
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

        // Check for duplicate categories
        $categories = array_column($goals, 'key');
        $uniqueCategories = array_unique($categories);
        if (count($categories) !== count($uniqueCategories)) {
            return redirect()->back()->withErrors(['goals' => 'Setiap kategori hanya dapat digunakan untuk satu goal saja.'])->withInput();
        }

        // Persist as JSON string
        set_setting('dashboard_goals', json_encode(array_values($goals)), 'json');

        // Clear helper cache if needed (best-effort)
        if (function_exists('cache')) {
            try { cache()->forget('app_settings'); } catch (\Exception $e) { }
        }

        return redirect()->route('backoffice.pengaturan.goals')->with('success', 'Daftar goals berhasil disimpan.');
    }

    /**
     * Save the whole list of product grades (JSON payload)
     */
    public function saveGradesList(Request $request)
    {
        $data = $request->validate([
            'grades' => 'nullable|array',
            'grades.*.name' => 'required_with:grades|string',
            'grades.*.label' => 'required_with:grades|string'
        ]);

        $newGrades = $data['grades'] ?? [];

        // Get current grades to check for deletions
        $currentGrades = Pengaturan::getProductGrades();

        // Check if any grades are being deleted and if they're still in use
        if (!empty($currentGrades)) {
            $currentGradeNames = array_column($currentGrades, 'name');
            $newGradeNames = array_column($newGrades, 'name');

            // Find deleted grades
            $deletedGrades = array_diff($currentGradeNames, $newGradeNames);

            if (!empty($deletedGrades)) {
                // Check if any deleted grades are still used in production
                foreach ($deletedGrades as $deletedGradeName) {
                    // Find the index of this grade in current grades (A=0, B=1, C=2, etc.)
                    $gradeIndex = array_search($deletedGradeName, $currentGradeNames);
                    if ($gradeIndex !== false) {
                        $gradeValue = chr(65 + $gradeIndex); // Convert to A, B, C, etc.

                        // Check if this grade is used in any production records
                        $productionsUsingGrade = \App\Models\Produksi::where('grade_kualitas', $gradeValue)->count();
                        $stockUsingGrade = \App\Models\StokProduk::where('grade_kualitas', $gradeValue)->count();

                        if ($productionsUsingGrade > 0 || $stockUsingGrade > 0) {
                            return response()->json([
                                'success' => false,
                                'message' => "Grade '{$deletedGradeName}' tidak dapat dihapus karena masih digunakan oleh {$productionsUsingGrade} data produksi dan {$stockUsingGrade} data stok produk."
                            ], 400);
                        }
                    }
                }
            }
        }

        // Persist as JSON string
        set_setting('product_grades', json_encode(array_values($newGrades)), 'json');

        // Clear helper cache if needed (best-effort)
        if (function_exists('cache')) {
            try { cache()->forget('app_settings'); } catch (\Exception $e) { }
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar grade berhasil disimpan.'
        ]);
    }

    /**
     * Generate unique, slug-like key for each performance metric based on label.
     */
    private function generatePerformanceKey(string $label, int $index): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($label)));
        $slug = trim($slug, '_');

        if ($slug === '') {
            $slug = 'indikator_' . ($index + 1);
        }

        return $slug;
    }

    /**
     * Update grade label settings
     */
    public function updateGrade(Request $request)
    {
        $request->validate([
            'setting_name' => 'required|string|in:grade_a_label,grade_b_label,grade_c_label',
            'value' => 'required|string|max:50'
        ]);

        try {
            Pengaturan::setValue($request->setting_name, $request->value, 'string');

            return response()->json([
                'success' => true,
                'message' => 'Label grade berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui label grade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display grade management page
     */
    public function grade()
    {
        // Stored as JSON in 'product_grades'
        $raw = Pengaturan::where('nama_pengaturan', 'product_grades')->first();
        $grades = [];
        if ($raw) {
            $decoded = json_decode($raw->nilai, true);
            if (is_array($decoded)) $grades = $decoded;
        }

        return view('admin.pages.pengaturan.grade', compact('grades'));
    }

    /**
     * Store a new grade setting
     */
    public function storeGrade(Request $request)
    {
        $request->validate([
            'grade_type' => 'required|string|in:grade_a_label,grade_b_label,grade_c_label',
            'label' => 'required|string|max:50'
        ]);

        try {
            Pengaturan::setValue($request->grade_type, $request->label, 'string');

            return response()->json([
                'success' => true,
                'message' => 'Grade berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan grade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update grade setting
     */
    public function updateGradeSetting(Request $request, $grade)
    {
        $request->validate([
            'label' => 'required|string|max:50'
        ]);

        $validGrades = ['grade_a_label', 'grade_b_label', 'grade_c_label'];
        if (!in_array($grade, $validGrades)) {
            return response()->json([
                'success' => false,
                'message' => 'Grade tidak valid.'
            ], 400);
        }

        try {
            Pengaturan::setValue($grade, $request->label, 'string');

            return response()->json([
                'success' => true,
                'message' => 'Grade berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui grade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete grade setting (reset to default)
     */
    public function deleteGradeSetting($grade)
    {
        $validGrades = ['grade_a_label', 'grade_b_label', 'grade_c_label'];
        $defaults = [
            'grade_a_label' => 'Grade A',
            'grade_b_label' => 'Grade B',
            'grade_c_label' => 'Grade C'
        ];

        if (!in_array($grade, $validGrades)) {
            return response()->json([
                'success' => false,
                'message' => 'Grade tidak valid.'
            ], 400);
        }

        try {
            Pengaturan::setValue($grade, $defaults[$grade], 'string');

            return response()->json([
                'success' => true,
                'message' => 'Grade berhasil direset ke default.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset grade: ' . $e->getMessage()
            ], 500);
        }
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
