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
        $produks = Produk::aktif()->get();
        $batchProduksis = BatchProduksi::where('status', 'aktif')->get();
        $tungkus = Tungku::aktif()->get();
        $bahanBakus = BahanBaku::aktif()->get();

        return view('admin.pages.produksi.create-produksi', compact('produks', 'batchProduksis', 'tungkus', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_produksi_id' => 'required|exists:batch_produksis,id',
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_target' => 'required|numeric|min:0.01',
            'bahan_digunakan' => 'required|array|min:1',
            'bahan_digunakan.*.bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'bahan_digunakan.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor produksi
            $nomorProduksi = $this->generateNomorProduksi();

            // Hitung biaya produksi menggunakan FIFO
            $totalBiaya = $this->calculateProductionCost($request->bahan_digunakan);

            // Buat produksi
            $produksi = Produksi::create([
                'nomor_produksi' => $nomorProduksi,
                'batch_produksi_id' => $request->batch_produksi_id,
                'produk_id' => $request->produk_id,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_target' => $request->jumlah_target,
                'biaya_produksi' => $totalBiaya,
                'status' => 'pending',
                'catatan' => $request->catatan,
                'user_id' => Auth::id(),
            ]);

            // Simpan bahan yang digunakan dengan FIFO costing
            $this->saveProductionMaterials($produksi->id, $request->bahan_digunakan);

            DB::commit();

            return redirect()->route('produksi.index')
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
        // Hanya bisa edit jika status masih pending
        if ($produksi->status !== 'pending') {
            return back()->with('error', 'Produksi yang sudah diproses tidak dapat diedit');
        }

        $produks = Produk::aktif()->get();
        $batchProduksis = BatchProduksi::where('status', 'aktif')->get();
        $tungkus = Tungku::aktif()->get();
        $bahanBakus = BahanBaku::aktif()->get();

        return view('admin.pages.produksi.edit-produksi', compact('produksi', 'produks', 'batchProduksis', 'tungkus', 'bahanBakus'));
    }

    public function update(Request $request, Produksi $produksi)
    {
        // Hanya bisa update jika status masih pending
        if ($produksi->status !== 'pending') {
            return back()->with('error', 'Produksi yang sudah diproses tidak dapat diupdate');
        }

        $request->validate([
            'batch_produksi_id' => 'required|exists:batch_produksis,id',
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_target' => 'required|numeric|min:0.01',
            'bahan_digunakan' => 'required|array|min:1',
            'bahan_digunakan.*.bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'bahan_digunakan.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Hitung biaya produksi menggunakan FIFO
            $totalBiaya = $this->calculateProductionCost($request->bahan_digunakan);

            // Update produksi
            $produksi->update([
                'batch_produksi_id' => $request->batch_produksi_id,
                'produk_id' => $request->produk_id,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_target' => $request->jumlah_target,
                'biaya_produksi' => $totalBiaya,
                'catatan' => $request->catatan,
            ]);

            // Hapus bahan lama dan simpan yang baru
            $produksi->produksiBahans()->delete();
            $this->saveProductionMaterials($produksi->id, $request->bahan_digunakan);

            DB::commit();

            return redirect()->route('produksi.index')
                ->with('success', 'Produksi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Produksi $produksi)
    {
        // Hanya bisa hapus jika status masih pending
        if ($produksi->status !== 'pending') {
            return back()->with('error', 'Produksi yang sudah diproses tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            // Hapus bahan yang digunakan (akan mengembalikan stok)
            $produksi->produksiBahans()->delete();

            // Hapus produksi
            $produksi->delete();

            DB::commit();

            return redirect()->route('produksi.index')
                ->with('success', 'Produksi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function startProduction(Produksi $produksi)
    {
        if ($produksi->status !== 'pending') {
            return back()->with('error', 'Status produksi tidak valid');
        }

        $produksi->update(['status' => 'diproses']);

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

            return redirect()->route('produksi.show', $produksi)
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

            foreach ($stokBahanBakus as $stok) {
                if ($sisaDigunakan <= 0) break;

                $ambilDariStok = min($sisaDigunakan, $stok->sisa_stok);
                $biayaBahan += $ambilDariStok * $stok->harga_satuan;
                $sisaDigunakan -= $ambilDariStok;
            }

            if ($sisaDigunakan > 0) {
                throw new \Exception("Stok bahan baku '{$bahanBakuId}' tidak mencukupi. Kekurangan: " . number_format($sisaDigunakan, 2));
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
                    'harga_satuan' => $stok->harga_satuan,
                    'total_biaya' => $ambilDariStok * $stok->harga_satuan,
                ]);

                // Kurangi stok
                $stok->decrement('sisa_stok', $ambilDariStok);
                $sisaDigunakan -= $ambilDariStok;
            }
        }
    }
}
