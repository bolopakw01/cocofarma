<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProdukController;
use App\Models\StokProduk;
use App\Models\Produksi;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProduksiController as MainProduksiController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PengaturanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Backoffice authentication routes
Route::get('/mimin', [AdminController::class, 'showLogin'])->name('backoffice.login');
Route::post('/mimin', [AdminController::class, 'login']);
Route::post('/backoffice/logout', [AdminController::class, 'logout'])->name('backoffice.logout');

// Backoffice routes with role-based access
Route::middleware(['admin.auth'])->prefix('backoffice')->name('backoffice.')->group(function () {
    
    // Dashboard (accessible by both super_admin and admin)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Operasional routes (accessible by both super_admin and admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::prefix('pesanan')->name('pesanan.')->group(function () {
            Route::get('/', [PesananController::class, 'index'])->name('index');
            Route::get('/create', [PesananController::class, 'create'])->name('create');
            Route::post('/', [PesananController::class, 'store'])->name('store');
            Route::get('/{pesanan}', [PesananController::class, 'show'])->name('show');
            Route::get('/{pesanan}/edit', [PesananController::class, 'edit'])->name('edit');
            Route::put('/{pesanan}', [PesananController::class, 'update'])->name('update');
            Route::post('/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{pesanan}', [PesananController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('bahanbaku')->name('bahanbaku.')->group(function () {
            Route::get('/', [BahanBakuController::class, 'index'])->name('index');
            // AJAX detail endpoint for operational bahan (rich SweetAlert)
            Route::get('/{bahanbaku}/detail', [BahanBakuController::class, 'detail'])->name('detail');
            Route::get('/create', [BahanBakuController::class, 'create'])->name('create');
            Route::post('/', [BahanBakuController::class, 'store'])->name('store');
            Route::get('/{bahanbaku}', [BahanBakuController::class, 'show'])->name('show');
            Route::get('/{bahanbaku}/edit', [BahanBakuController::class, 'edit'])->name('edit');
            Route::put('/{bahanbaku}', [BahanBakuController::class, 'update'])->name('update');
            Route::delete('/{bahanbaku}', [BahanBakuController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('produksi')->name('produksi.')->group(function () {
            Route::get('/', [MainProduksiController::class, 'index'])->name('index');
            Route::get('/create', [MainProduksiController::class, 'create'])->name('create');
            Route::post('/', [MainProduksiController::class, 'store'])->name('store');
            Route::get('/{produksi}', [MainProduksiController::class, 'show'])->name('show');
            Route::get('/{produksi}/edit', [MainProduksiController::class, 'edit'])->name('edit');
            Route::put('/{produksi}', [MainProduksiController::class, 'update'])->name('update');
            Route::delete('/{produksi}', [MainProduksiController::class, 'destroy'])->name('destroy');
            Route::post('/{produksi}/start', [MainProduksiController::class, 'startProduction'])->name('start');
            Route::post('/{produksi}/complete', [MainProduksiController::class, 'completeProduction'])->name('complete');
            Route::post('/{produksi}/transfer', [MainProduksiController::class, 'transferToProduk'])->name('transfer');
            // AJAX: get current stok for a list of bahan IDs
            Route::post('/api/bahan-stok', [MainProduksiController::class, 'apiBahanStok'])->name('api.bahan-stok');
        });

        // Operational Produk page (simple index view)
        Route::prefix('produk')->name('produk.')->group(function () {
            // Operational Produk index: show master produk list alongside stok operasional dan histori produksi
            Route::get('/', function () {
                $masterProduks = \App\Models\Produk::aktif()
                    ->with(['stokProduks' => function ($query) {
                        $query->orderByDesc('tanggal')->orderByDesc('id');
                    }])
                    ->orderBy('nama_produk')
                    ->get()
                    ->map(function ($produk) {
                        $produk->total_operasional_stok = $produk->stokProduks->sum(function ($stok) {
                            return max($stok->sisa_stok, 0);
                        });
                        return $produk;
                    });

                $stokProduks = \App\Models\StokProduk::with(['produk', 'batchProduksi'])
                    ->orderByDesc('tanggal')
                    ->orderByDesc('id')
                    ->limit(50)
                    ->get();

                $produksis = \App\Models\Produksi::with(['produk', 'batchProduksi'])
                    ->orderByDesc('tanggal_produksi')
                    ->orderByDesc('id')
                    ->limit(50)
                    ->get();

                return view('admin.pages.produk.index-produk', [
                    'stokProduks' => $stokProduks,
                    'produksis' => $produksis,
                    'masterProduks' => $masterProduks,
                ]);
            })->name('index');

            // Stok produk management (super_admin only)
            Route::middleware('role:super_admin')->group(function () {
                Route::get('/stok/{stok}/edit', [\App\Http\Controllers\Admin\StokProdukController::class, 'edit'])->name('stok.edit');
                Route::put('/stok/{stok}', [\App\Http\Controllers\Admin\StokProdukController::class, 'update'])->name('stok.update');
                Route::delete('/stok/{stok}', [\App\Http\Controllers\Admin\StokProdukController::class, 'destroy'])->name('stok.destroy');
            });
        });

        // Master produk routes (only accessible by super_admin)
        Route::middleware('role:super_admin')->prefix('master-produk')->name('master-produk.')->group(function () {
            Route::get('/', [ProdukController::class, 'index'])->name('index');
            Route::get('/create', [ProdukController::class, 'create'])->name('create');
            // Preview kode endpoint (AJAX) - no side effects
            Route::get('/preview-kode', [ProdukController::class, 'previewKode'])->name('preview-kode');
            Route::post('/', [ProdukController::class, 'store'])->name('store');
            Route::get('/{produk}', [ProdukController::class, 'show'])->name('show');
            Route::get('/{produk}/edit', [ProdukController::class, 'edit'])->name('edit');
            Route::put('/{produk}', [ProdukController::class, 'update'])->name('update');
            Route::delete('/{produk}', [ProdukController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::resource('/', TransaksiController::class)->parameters(['' => 'transaksi']);
        });

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/produksi', [LaporanController::class, 'produksi'])->name('produksi');
            Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
            Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
            // Sensitive/report export actions - restrict to super_admin only
            Route::get('/cost-analysis', [LaporanController::class, 'costAnalysis'])
                ->middleware('role:super_admin')
                ->name('cost-analysis');

            Route::get('/export-pdf/{type}', [LaporanController::class, 'exportPdf'])
                ->middleware('role:super_admin,admin')
                ->name('export-pdf');

            Route::get('/export-excel/{type}', [LaporanController::class, 'exportExcel'])
                ->middleware('role:super_admin,admin')
                ->name('export-excel');
        });
    });

    // Master routes (only accessible by super_admin)
    Route::middleware('role:super_admin')->group(function () {
        Route::prefix('master-bahan')->name('master-bahan.')->group(function () {
            // Add preview route for AJAX preview of kode
            Route::get('/preview-kode', [BahanBakuController::class, 'previewKode'])->name('preview-kode');
            // AJAX detail endpoint for rich SweetAlert popup
            Route::get('/{bahanbaku}/detail', [BahanBakuController::class, 'detail'])->name('detail');
            Route::resource('/', BahanBakuController::class)->parameters(['' => 'bahanbaku']);
        });

        Route::prefix('master-user')->name('master-user.')->group(function () {
            Route::resource('/', UserController::class)->parameters(['' => 'user']);
        });

        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
                // Additional pengaturan actions (define explicit routes before the resource to avoid parameter conflicts)
                Route::post('/backup-database', [PengaturanController::class, 'backupDatabase'])->name('backup-database');
                Route::post('/save-dashboard-goal', [PengaturanController::class, 'saveDashboardGoal'])->name('save-dashboard-goal');
                Route::get('/goals', [PengaturanController::class, 'goals'])->name('goals');
                Route::post('/save-goals', [PengaturanController::class, 'saveGoalsList'])->name('save-goals');
                Route::post('/save-grades', [PengaturanController::class, 'saveGradesList'])->name('save-grades');
                Route::get('/alerts', [PengaturanController::class, 'alerts'])->name('alerts');
                Route::post('/update-grade', [PengaturanController::class, 'updateGrade'])->name('update-grade');
                Route::get('/grade', [PengaturanController::class, 'grade'])->name('grade');
                Route::post('/grade/store', [PengaturanController::class, 'storeGrade'])->name('grade.store');
                Route::put('/grade/update/{grade}', [PengaturanController::class, 'updateGradeSetting'])->name('grade.update');
                Route::delete('/grade/delete/{grade}', [PengaturanController::class, 'deleteGradeSetting'])->name('grade.delete');
                // Resource controller (last, so /goals etc aren't shadowed by /{pengaturan})
                Route::resource('/', PengaturanController::class)->parameters(['' => 'pengaturan']);
        });
    });
});

require __DIR__.'/auth.php';
