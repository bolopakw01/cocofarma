<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\BatchProduksi;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\StokBahanBaku;
use App\Models\ProduksiBahan;
use App\Models\StokProduk;
use App\Models\Tungku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;

class ProduksiController extends Controller
{
    public function index()
    {
        $query = Produksi::with(['produk', 'batchProduksi', 'user']);

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nomor_produksi', 'like', '%' . $search . '%')
                  ->orWhereHas('produk', function($produkQuery) use ($search) {
                      $produkQuery->where('nama_produk', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('batchProduksi', function($batchQuery) use ($search) {
                      $batchQuery->where('nomor_batch', 'like', '%' . $search . '%');
                  });
            });
        }

        $query->orderBy('tanggal_produksi', 'desc');

        // Sorting functionality
        if (request('sort')) {
            $sortColumn = request('sort');
            $sortDirection = request('direction', 'asc');

            switch ($sortColumn) {
                case 'nomor_produksi':
                    $query->orderBy('nomor_produksi', $sortDirection);
                    break;
                case 'tanggal':
                    $query->orderBy('tanggal_produksi', $sortDirection);
                    break;
                case 'target':
                    $query->orderBy('jumlah_target', $sortDirection);
                    break;
                case 'hasil':
                    $query->orderBy('jumlah_hasil', $sortDirection);
                    break;
                case 'status':
                    $query->orderBy('status', $sortDirection);
                    break;
                default:
                    $query->orderBy('tanggal_produksi', 'desc');
            }
        } else {
            $query->orderBy('tanggal_produksi', 'desc');
        }

        $perPage = request('per_page', 15);
        if ($perPage === 'all') {
            $produksis = $query->get();
        } else {
            $produksis = $query->paginate((int)$perPage);
        }

        return view('admin.pages.produksi.index-produksi', compact('produksis'));
    }

    public function create()
    {
        $produks = Produk::with('produkBahans.masterBahan')->get();
        $batchProduksis = BatchProduksi::where('status', 'aktif')->get();
        $tungkus = Tungku::aktif()->get();
        
        // Get bahan baku with calculated total stock from stok_bahan_baku
        $bahanBakus = BahanBaku::aktif()->with(['stokBahanBaku' => function($query) {
            $query->where('sisa_stok', '>', 0);
        }])->get()->map(function($bahan) {
            $bahan->total_stok = $bahan->stokBahanBaku->sum('sisa_stok');
            return $bahan;
        });

        // Prepare produk -> komposisi mapping for the view (JSON)
        $produkCompositions = [];
        foreach ($produks as $produk) {
            $items = [];
            foreach ($produk->produkBahans as $pb) {
                $items[] = [
                    'master_bahan_id' => $pb->master_bahan_id,
                    'jumlah_per_unit' => (float)$pb->jumlah_per_unit,
                    'master_name' => $pb->masterBahan->nama_bahan ?? null,
                    'satuan' => $pb->masterBahan->satuan ?? null,
                ];
            }
            $produkCompositions[$produk->id] = $items;
        }

        return view('admin.pages.produksi.create-produksi', compact('produks', 'batchProduksis', 'tungkus', 'bahanBakus', 'produkCompositions'));
    }

    /**
     * Return current stok for given bahan IDs (AJAX)
     */
    public function apiBahanStok(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid ids'], 422);
        }

        // Get total available stock from stok_bahan_baku (FIFO batches)
        $rows = BahanBaku::whereIn('id', $ids)->with(['stokBahanBaku' => function($query) {
            $query->where('sisa_stok', '>', 0);
        }])->get();

        $map = [];
        foreach ($rows as $bahanBaku) {
            $totalStok = $bahanBaku->stokBahanBaku->sum('sisa_stok');
            $map[$bahanBaku->id] = $totalStok;
        }

        return response()->json(['stok' => $map]);
    }

    public function store(Request $request)
    {
        // Ensure bahan_baku table exists (support both naming conventions)
        if (!Schema::hasTable('bahan_bakus') && !Schema::hasTable('bahan_baku')) {
            return back()->withInput()->with('error', 'Tabel bahan baku tidak ditemukan di database. Jalankan "php artisan migrate" atau periksa skema database.');
        }

        $bahanTable = Schema::hasTable('bahan_bakus') ? 'bahan_bakus' : 'bahan_baku';

        $request->validate([
            'batch_produksi_id' => 'nullable|exists:batch_produksis,id',
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_target' => 'required|numeric|min:0.01',
            'bahan_digunakan' => 'required|array|min:1',
            'bahan_digunakan.*.bahan_baku_id' => 'required|exists:' . $bahanTable . ',id',
            'bahan_digunakan.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor produksi
            $nomorProduksi = $this->generateNomorProduksi();

            // Jika batch_produksi_id tidak diberikan, buat Batch otomatis
            $batchId = $request->batch_produksi_id;
            if (empty($batchId)) {
                $now = now();
                $nomorBatch = 'BATCH-' . $now->format('Ymd-His') . '-' . strtoupper(substr(Str::random(6), 0, 4));
                $batch = BatchProduksi::create([
                    'nomor_batch' => $nomorBatch,
                    'produk_id' => $request->produk_id ?? null,
                    'tungku_id' => $request->tungku_id ?? null,
                    'tanggal_produksi' => $request->tanggal_produksi ?? $now->toDateString(),
                    'status' => 'rencana',
                    'catatan' => 'Auto-generated',
                    'user_id' => Auth::id(),
                ]);
                $batchId = $batch->id;
            }

            // Hitung biaya produksi menggunakan FIFO
            $totalBiaya = $this->calculateProductionCost($request->bahan_digunakan);

            // Buat produksi
            $produksi = Produksi::create([
                'nomor_produksi' => $nomorProduksi,
                'batch_produksi_id' => $batchId,
                'produk_id' => $request->produk_id,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_target' => $request->jumlah_target,
                'biaya_produksi' => $totalBiaya,
                'status' => 'rencana',
                'catatan' => $request->catatan,
                'user_id' => Auth::id(),
            ]);

            // Simpan bahan yang digunakan dengan FIFO costing
            $this->saveProductionMaterials($produksi->id, $request->bahan_digunakan);

            DB::commit();

            return redirect()->route('backoffice.produksi.index')
                ->with('success', 'Produksi berhasil dibuat dengan nomor: ' . $nomorProduksi);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Produksi $produksi)
    {
        $produksi->load(['produk', 'batchProduksi.tungku', 'produksiBahans.bahanBaku', 'produksiBahans.stokBahanBaku', 'user']);

        return view('admin.pages.produksi.show-produksi', compact('produksi'));
    }

    public function edit(Produksi $produksi)
    {
        // Load data tambahan jika status belum selesai/gagal
        $data = ['produksi' => $produksi];

        if ($produksi->status !== 'selesai' && $produksi->status !== 'gagal') {
            $data['produks'] = Produk::aktif()->get();
            $data['batchProduksis'] = BatchProduksi::where('status', 'aktif')->get();
            $data['bahanBakus'] = BahanBaku::aktif()->get();
        }

        return view('admin.pages.produksi.edit-produksi', $data);
    }

    public function update(Request $request, Produksi $produksi)
    {
        // Validasi dasar
        $rules = [
            'status' => 'required|in:rencana,proses,selesai,gagal',
            'catatan' => 'nullable|string|max:1000',
        ];

        // Jika status belum selesai/gagal, tambahkan validasi untuk field produksi
        if ($produksi->status !== 'selesai' && $produksi->status !== 'gagal') {
            $rules['jumlah_target'] = 'required|numeric|min:0.01';
            $rules['bahan_digunakan'] = 'required|array|min:1';
            $rules['bahan_digunakan.*.bahan_baku_id'] = 'required|exists:bahan_baku,id';
            $rules['bahan_digunakan.*.jumlah'] = 'required|numeric|min:0.01';
        }

        // Jika status diubah ke selesai, tambahkan validasi untuk field tambahan
        if ($request->status === 'selesai') {
            $rules['jumlah_hasil'] = 'required|numeric|min:0';
            $rules['grade_kualitas'] = 'required|in:A,B,C';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $updateData = [
                'status' => $request->status,
                'catatan' => $request->catatan,
            ];

            // Jika status belum selesai/gagal, update field produksi
            if ($produksi->status !== 'selesai' && $produksi->status !== 'gagal') {
                // Hitung biaya produksi menggunakan FIFO
                $totalBiaya = $this->calculateProductionCost($request->bahan_digunakan);

                $updateData['jumlah_target'] = $request->jumlah_target;
                $updateData['biaya_produksi'] = $totalBiaya;

                // Kembalikan stok bahan lama terlebih dahulu
                $this->returnProductionMaterials($produksi);

                // Hapus bahan lama
                $produksi->produksiBahans()->delete();

                // Simpan bahan baru (akan mengurangi stok)
                $this->saveProductionMaterials($produksi->id, $request->bahan_digunakan);
            }

            // Jika status diubah ke selesai, tambahkan field tambahan
            if ($request->status === 'selesai') {
                $updateData['jumlah_hasil'] = $request->jumlah_hasil;
                $updateData['grade_kualitas'] = $request->grade_kualitas;

                // Buat stok produk hasil produksi jika belum ada
                if (!$produksi->stokProduk) {
                    StokProduk::create([
                        'produk_id' => $produksi->produk_id,
                        'batch_produksi_id' => $produksi->batch_produksi_id,
                        'jumlah_masuk' => $request->jumlah_hasil,
                        'sisa_stok' => $request->jumlah_hasil,
                        'harga_satuan' => $produksi->biaya_produksi / $produksi->jumlah_target,
                        'grade_kualitas' => $request->grade_kualitas,
                        'tanggal' => $produksi->tanggal_produksi,
                        'keterangan' => 'Hasil produksi ' . $produksi->nomor_produksi,
                    ]);
                }
            }

            // Update produksi
            $produksi->update($updateData);

            DB::commit();

            return redirect()->route('backoffice.produksi.index')
                ->with('success', 'Produksi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Produksi $produksi)
    {
        // Hanya bisa hapus jika status masih rencana
        if ($produksi->status !== 'rencana') {
            return back()->with('error', 'Produksi yang sudah diproses tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok bahan baku sebelum menghapus
            $this->returnProductionMaterials($produksi);

            // Hapus bahan yang digunakan
            $produksi->produksiBahans()->delete();

            // Hapus produksi
            $produksi->delete();

            DB::commit();

            return redirect()->route('backoffice.produksi.index')
                ->with('success', 'Produksi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function startProduction(Produksi $produksi)
    {
        if ($produksi->status !== 'rencana') {
            return back()->with('error', 'Status produksi tidak valid');
        }

        $produksi->update(['status' => 'proses']);

        return back()->with('success', 'Produksi dimulai');
    }

    public function completeProduction(Request $request, Produksi $produksi)
    {
        $request->validate([
            'jumlah_hasil' => 'required|numeric|min:0',
            'grade_kualitas' => 'required|in:A,B,C',
        ]);

        DB::beginTransaction();
        try {
            // Update produksi
            $produksi->update([
                'jumlah_hasil' => $request->jumlah_hasil,
                'grade_kualitas' => $request->grade_kualitas,
                'status' => 'selesai',
            ]);

            // Buat stok produk hasil produksi
            StokProduk::create([
                'produk_id' => $produksi->produk_id,
                'batch_produksi_id' => $produksi->batch_produksi_id,
                'jumlah_masuk' => $request->jumlah_hasil,
                'sisa_stok' => $request->jumlah_hasil,
                'harga_satuan' => $produksi->biaya_produksi / $produksi->jumlah_target,
                'grade_kualitas' => $request->grade_kualitas,
                'tanggal' => $produksi->tanggal_produksi,
                'keterangan' => 'Hasil produksi ' . $produksi->nomor_produksi,
            ]);

            DB::commit();

            return redirect()->route('backoffice.produksi.show', $produksi)
                ->with('success', 'Produksi berhasil diselesaikan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateNomorProduksi()
    {
        $date = now()->format('Ymd');
        $lastProduksi = Produksi::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastProduksi ? intval(substr($lastProduksi->nomor_produksi, -3)) + 1 : 1;

        return 'PRD-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    private function calculateProductionCost($bahanDigunakan)
    {
        $totalBiaya = 0;

        foreach ($bahanDigunakan as $bahan) {
            $bahanBakuId = $bahan['bahan_baku_id'];
            $jumlahDigunakan = $bahan['jumlah'];

            // Ambil stok bahan baku dengan FIFO (First In First Out)
            $stokBahanBakus = StokBahanBaku::where('bahan_baku_id', $bahanBakuId)
                ->where('sisa_stok', '>', 0)
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            $sisaDigunakan = $jumlahDigunakan;
            $biayaBahan = 0;

            $sisaDigunakan = $jumlahDigunakan;
            $biayaBahan = 0;

            foreach ($stokBahanBakus as $stok) {
                if ($sisaDigunakan <= 0) break;

                $ambilDariStok = min($sisaDigunakan, $stok->sisa_stok);
                $biayaBahan += $ambilDariStok * $stok->harga_satuan;
                $sisaDigunakan -= $ambilDariStok;
            }

            if ($sisaDigunakan > 0) {
                $totalStokTersedia = $stokBahanBakus->sum('sisa_stok');
                throw new \Exception("Stok bahan baku '{$bahanBakuId}' tidak mencukupi. Dibutuhkan: " . number_format($jumlahDigunakan, 2) . ", Tersedia: " . number_format($totalStokTersedia, 2) . ", Kekurangan: " . number_format($sisaDigunakan, 2));
            }

            $totalBiaya += $biayaBahan;
        }

        return $totalBiaya;
    }

    private function saveProductionMaterials($produksiId, $bahanDigunakan)
    {
        foreach ($bahanDigunakan as $bahan) {
            $bahanBakuId = $bahan['bahan_baku_id'];
            $jumlahDigunakan = $bahan['jumlah'];
            $hargaOverride = isset($bahan['harga_satuan']) && $bahan['harga_satuan'] !== '' ? $bahan['harga_satuan'] : null;

            // Ambil bahan baku untuk mengurangi stok total
            $bahanBaku = BahanBaku::find($bahanBakuId);
            if (!$bahanBaku) {
                throw new \Exception("Bahan baku dengan ID {$bahanBakuId} tidak ditemukan");
            }

            // Ambil stok bahan baku dengan FIFO
            $stokBahanBakus = StokBahanBaku::where('bahan_baku_id', $bahanBakuId)
                ->where('sisa_stok', '>', 0)
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            $sisaDigunakan = $jumlahDigunakan;

            foreach ($stokBahanBakus as $stok) {
                if ($sisaDigunakan <= 0) break;

                $ambilDariStok = min($sisaDigunakan, $stok->sisa_stok);

                // Simpan penggunaan bahan
                ProduksiBahan::create([
                    'produksi_id' => $produksiId,
                    'bahan_baku_id' => $bahanBakuId,
                    'stok_bahan_baku_id' => $stok->id,
                    'jumlah_digunakan' => $ambilDariStok,
                    // harga_satuan tetap dari FIFO stok untuk per-unit cost
                    'harga_satuan' => $stok->harga_satuan,
                    'total_biaya' => $ambilDariStok * $stok->harga_satuan,
                    // harga_override menyimpan nilai yang mungkin diinput user di form (editable)
                    'harga_override' => $hargaOverride,
                ]);

                // Kurangi stok detail (FIFO)
                $stok->decrement('sisa_stok', $ambilDariStok);
                $sisaDigunakan -= $ambilDariStok;
            }

            // Kurangi stok total di tabel bahan_baku
            $bahanBaku->decrement('stok', $jumlahDigunakan);
        }
    }

    private function returnProductionMaterials(Produksi $produksi)
    {
        // Ambil semua bahan yang digunakan dalam produksi ini
        $produksiBahans = $produksi->produksiBahans;

        foreach ($produksiBahans as $produksiBahan) {
            $bahanBakuId = $produksiBahan->bahan_baku_id;
            $jumlahDigunakan = $produksiBahan->jumlah_digunakan;
            $stokBahanBakuId = $produksiBahan->stok_bahan_baku_id;

            // Ambil bahan baku untuk menambah stok total
            $bahanBaku = BahanBaku::find($bahanBakuId);
            if (!$bahanBaku) {
                throw new \Exception("Bahan baku dengan ID {$bahanBakuId} tidak ditemukan");
            }

            // Kembalikan stok detail (FIFO)
            $stokBahanBaku = StokBahanBaku::find($stokBahanBakuId);
            if ($stokBahanBaku) {
                $stokBahanBaku->increment('sisa_stok', $jumlahDigunakan);
            }

            // Kembalikan stok total di tabel bahan_baku
            $bahanBaku->increment('stok', $jumlahDigunakan);
        }
    }
}
