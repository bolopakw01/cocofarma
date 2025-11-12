<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 15);

        if ($perPage === 'all') {
            $allItems = Pesanan::with('pesananItems.produk')->orderBy('tanggal_pesanan', 'desc')->get();
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPageCount = $allItems->count() ?: 1;
            $currentItems = $allItems->slice(($currentPage - 1) * $perPageCount, $perPageCount)->values();

            $pesanans = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $allItems->count(), $perPageCount, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => request()->query()
            ]);
        } else {
            $pesanans = Pesanan::with('pesananItems.produk')
                ->orderBy('tanggal_pesanan', 'desc')
                ->paginate((int) $perPage)
                ->appends(request()->query());
        }

        return view('admin.pages.pesanan.index-pesanan', compact('pesanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch operational stock grouped by product with summary data
        $produkStoks = StokProduk::with('produk')
            ->selectRaw('produk_id, SUM(sisa_stok) as total_stok')
            ->where('sisa_stok', '>', 0)
            ->groupBy('produk_id')
            ->having('total_stok', '>', 0)
            ->get();

        // Get all active products from master produk
        $activeProduks = Produk::where('status', 'aktif')
            ->orderBy('nama_produk')
            ->get();

        // Map operational stock to view-friendly structure
        $produks = $produkStoks->map(function ($stok) {
            return (object) [
                'id' => $stok->produk_id,
                'nama_produk' => $stok->produk->nama_produk,
                'satuan' => $stok->produk->satuan,
                'harga_jual' => $stok->produk->harga_jual,
                'stok_tersedia' => $stok->total_stok,
            ];
        });

        // Add active products that don't have operational stock yet
        $produkIdsDenganStok = $produkStoks->pluck('produk_id');
        $produkTanpaStok = $activeProduks->whereNotIn('id', $produkIdsDenganStok)->map(function ($produk) {
            return (object) [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'satuan' => $produk->satuan,
                'harga_jual' => $produk->harga_jual,
                'stok_tersedia' => 0,
            ];
        });

        // Combine products with stock and products without stock
        $produks = $produks->concat($produkTanpaStok);

        return view('admin.pages.pesanan.create-pesanan', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pesanan' => 'required|date',
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:0.01'
        ]);

        // Generate unique kode pesanan before transaction
        $tanggal = date('ymd', strtotime($request->tanggal_pesanan));
        $timestamp = date('His') . substr(microtime(), 2, 3); // HHMMSS + milliseconds
        $random = rand(10, 99); // 2 digit random
        $kode_pesanan = 'PSN' . $tanggal . $timestamp . $random;

        DB::transaction(function () use ($request, $kode_pesanan) {
            $itemsData = [];
            $totalHarga = 0;

            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $jumlah = (float) $item['jumlah'];
                $subtotal = $jumlah * $produk->harga_jual;

                $totalHarga += $subtotal;
                $itemsData[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $pesanan = Pesanan::create([
                'kode_pesanan' => $kode_pesanan,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'status' => 'pending',
                'total_harga' => $totalHarga,
            ]);

            foreach ($itemsData as $itemData) {
                $pesanan->pesananItems()->create($itemData);
            }
        });

        return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil dibuat dan menunggu proses.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('pesananItems.produk')->findOrFail($id);
        return view('admin.pages.pesanan.show-pesanan', compact('pesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pesanan = Pesanan::with('pesananItems.produk')->findOrFail($id);

        // Fetch operational stock grouped by product with summary data
        $produkStoks = StokProduk::with('produk')
            ->selectRaw('produk_id, SUM(sisa_stok) as total_stok')
            ->where('sisa_stok', '>', 0)
            ->groupBy('produk_id')
            ->having('total_stok', '>', 0)
            ->get();

        // Get all active products from master produk
        $activeProduks = Produk::where('status', 'aktif')
            ->orderBy('nama_produk')
            ->get();

        // Map operational stock to view-friendly structure
        $produks = $produkStoks->map(function ($stok) {
            return (object) [
                'id' => $stok->produk_id,
                'nama_produk' => $stok->produk->nama_produk,
                'satuan' => $stok->produk->satuan,
                'harga_jual' => $stok->produk->harga_jual,
                'stok_tersedia' => $stok->total_stok,
            ];
        });

        // Add active products that don't have operational stock yet
        $produkIdsDenganStok = $produkStoks->pluck('produk_id');
        $produkTanpaStok = $activeProduks->whereNotIn('id', $produkIdsDenganStok)->map(function ($produk) {
            return (object) [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'satuan' => $produk->satuan,
                'harga_jual' => $produk->harga_jual,
                'stok_tersedia' => 0,
            ];
        });

        // Combine products with stock and products without stock
        $produks = $produks->concat($produkTanpaStok);

        // Ensure products already in the order remain selectable even if stock is zero
        $produkIdsDalamPesanan = $pesanan->pesananItems->pluck('produk_id')->unique();
        $produkIdsOperasional = $produks->pluck('id');
        $produkIdsTambahan = $produkIdsDalamPesanan->diff($produkIdsOperasional);

        if ($produkIdsTambahan->isNotEmpty()) {
            $produkTambahan = Produk::whereIn('id', $produkIdsTambahan)->get()->map(function ($produk) {
                return (object) [
                    'id' => $produk->id,
                    'nama_produk' => $produk->nama_produk,
                    'satuan' => $produk->satuan,
                    'harga_jual' => $produk->harga_jual,
                    'stok_tersedia' => 0,
                ];
            });

            $produks = $produks->concat($produkTambahan);
        }

        return view('admin.pages.pesanan.edit-pesanan', compact('pesanan', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pesanan = Pesanan::with('pesananItems')->findOrFail($id);
        $originalStatus = $pesanan->status;

        $request->validate([
            'tanggal_pesanan' => 'required|date',
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:pending,diproses,selesai,dibatalkan',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:0.01'
        ]);

        DB::transaction(function () use ($request, $pesanan, $originalStatus) {
            $pesanan->load('pesananItems');

            if (in_array($originalStatus, ['diproses', 'selesai'])) {
                $produkIds = $pesanan->pesananItems->pluck('produk_id')->unique()->toArray();
                $this->returnStock($produkIds, $pesanan->pesananItems);
            }

            $pesanan->pesananItems()->delete();

            $totalHarga = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $jumlah = (float) $item['jumlah'];
                $subtotal = $jumlah * $produk->harga_jual;

                $totalHarga += $subtotal;
                $itemsData[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $pesanan->update([
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'status' => $request->status,
                'total_harga' => $totalHarga,
            ]);

            foreach ($itemsData as $itemData) {
                $pesanan->pesananItems()->create($itemData);
            }

            $pesanan->load('pesananItems');

            if (in_array($request->status, ['diproses', 'selesai'])) {
                $produkIdsBaru = $pesanan->pesananItems->pluck('produk_id')->unique()->toArray();
                $this->reduceStock($produkIdsBaru, $pesanan->pesananItems);
            }
        });

        if ($request->status === 'selesai' && $originalStatus !== 'selesai') {
            $this->createSalesTransaction($pesanan->fresh('pesananItems'));
        }

        return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        DB::transaction(function () use ($pesanan) {
            if (in_array($pesanan->status, ['diproses', 'selesai'])) {
                foreach ($pesanan->pesananItems as $item) {
                    $produkId = $item->produk_id;
                    $jumlahDikembalikan = $item->jumlah;

                    $stokEntries = StokProduk::where('produk_id', $produkId)
                        ->where('jumlah_keluar', '>', 0)
                        ->orderBy('tanggal', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();

                    $sisaDikembalikan = $jumlahDikembalikan;

                    foreach ($stokEntries as $stokEntry) {
                        if ($sisaDikembalikan <= 0) {
                            break;
                        }

                        $stokDikeluarkan = $stokEntry->jumlah_keluar;

                        if ($stokDikeluarkan >= $sisaDikembalikan) {
                            $stokEntry->decrement('jumlah_keluar', $sisaDikembalikan);
                            $stokEntry->increment('sisa_stok', $sisaDikembalikan);
                            $sisaDikembalikan = 0;
                        } else {
                            $stokEntry->decrement('jumlah_keluar', $stokDikeluarkan);
                            $stokEntry->increment('sisa_stok', $stokDikeluarkan);
                            $sisaDikembalikan -= $stokDikeluarkan;
                        }
                    }
                }
            }

            $pesanan->pesananItems()->delete();
            $pesanan->delete();
        });

    return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    /**
     * Update status pesanan.
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            Log::info('updateStatus called', [
                'id' => $id,
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Check authentication
            if (!Auth::check()) {
                Log::warning('Unauthenticated request to updateStatus', [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi login telah berakhir. Silakan login kembali.'
                ], 401);
            }

            // Eager load pesananItems untuk mengurangi N+1 queries
            $pesanan = Pesanan::with('pesananItems')->findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,diproses,selesai,dibatalkan'
            ]);

            $oldStatus = $pesanan->status;
            $newStatus = $request->status;

            // Check if status actually changed
            if ($oldStatus === $newStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pesanan sudah sama. Tidak ada perubahan yang dilakukan.'
                ], 422);
            }

            DB::transaction(function () use ($pesanan, $oldStatus, $newStatus) {
                // Check if stock update is required
                if ($this->requiresStockUpdate($oldStatus, $newStatus)) {
                    $this->updateStockLevels($pesanan, $oldStatus, $newStatus);
                }

                // Create sales transaction if status changed to 'selesai'
                if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
                    $this->createSalesTransaction($pesanan);
                }

                // Update status pesanan
                $pesanan->update(['status' => $newStatus]);
            });

            $statusLabel = [
                'pending' => 'Pending',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan'
            ];

            $message = 'Status pesanan berhasil diubah menjadi ' . $statusLabel[$newStatus];

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $newStatus,
                    'status_label' => $statusLabel[$newStatus]
                ]);
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Check if stock update is required for status transition
     */
    private function requiresStockUpdate(string $oldStatus, string $newStatus): bool
    {
        $stockTransitions = [
            'pending' => ['diproses', 'selesai'],
            'diproses' => ['dibatalkan'],
            'selesai' => ['dibatalkan']
        ];

        return isset($stockTransitions[$oldStatus]) && in_array($newStatus, $stockTransitions[$oldStatus]);
    }

    /**
     * Update stock levels based on status transition
     */
    private function updateStockLevels($pesanan, string $oldStatus, string $newStatus): void
    {
        // Collect all product IDs first to optimize queries
        $produkIds = $pesanan->pesananItems->pluck('produk_id')->unique()->toArray();

        if ($newStatus === 'diproses' && $oldStatus === 'pending') {
            // Reduce stock (FIFO)
            $this->reduceStock($produkIds, $pesanan->pesananItems);
        }
        elseif ($newStatus === 'selesai' && $oldStatus === 'pending') {
            // Reduce stock (FIFO)
            $this->reduceStock($produkIds, $pesanan->pesananItems);
        }
        elseif ($newStatus === 'dibatalkan' && $oldStatus === 'diproses') {
            // Return stock (LIFO)
            $this->returnStock($produkIds, $pesanan->pesananItems);
        }
        elseif ($newStatus === 'dibatalkan' && $oldStatus === 'selesai') {
            // Return stock (LIFO)
            $this->returnStock($produkIds, $pesanan->pesananItems);
        }
    }

    /**
     * Reduce stock using FIFO method
     */
    private function reduceStock(array $produkIds, $pesananItems): void
    {
        $allStokEntries = StokProduk::whereIn('produk_id', $produkIds)
            ->where('sisa_stok', '>', 0)
            ->orderBy('produk_id')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get()
            ->groupBy('produk_id');

        foreach ($pesananItems as $item) {
            $produkId = $item->produk_id;
            $jumlahDibutuhkan = $item->jumlah;
            $stokEntries = $allStokEntries->get($produkId, collect());

            $sisaDibutuhkan = $jumlahDibutuhkan;

            // Only reduce available stock, allow ordering even if stock is insufficient
            foreach ($stokEntries as $stokEntry) {
                if ($sisaDibutuhkan <= 0) break;

                $stokTersedia = $stokEntry->sisa_stok;

                if ($stokTersedia >= $sisaDibutuhkan) {
                    $stokEntry->increment('jumlah_keluar', $sisaDibutuhkan);
                    $stokEntry->decrement('sisa_stok', $sisaDibutuhkan);
                    $sisaDibutuhkan = 0;
                } else {
                    $stokEntry->increment('jumlah_keluar', $stokTersedia);
                    $stokEntry->decrement('sisa_stok', $stokTersedia);
                    $sisaDibutuhkan -= $stokTersedia;
                }
            }

            // Note: We no longer throw exception for insufficient stock
            // Products can be ordered even without available stock
        }
    }

    /**
     * Return stock using LIFO method
     */
    private function returnStock(array $produkIds, $pesananItems): void
    {
        $allStokEntries = StokProduk::whereIn('produk_id', $produkIds)
            ->where('jumlah_keluar', '>', 0)
            ->orderBy('produk_id')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('produk_id');

        foreach ($pesananItems as $item) {
            $produkId = $item->produk_id;
            $jumlahDikembalikan = $item->jumlah;
            $stokEntries = $allStokEntries->get($produkId, collect());

            $sisaDikembalikan = $jumlahDikembalikan;

            foreach ($stokEntries as $stokEntry) {
                if ($sisaDikembalikan <= 0) break;

                $stokDikeluarkan = $stokEntry->jumlah_keluar;

                if ($stokDikeluarkan >= $sisaDikembalikan) {
                    $stokEntry->decrement('jumlah_keluar', $sisaDikembalikan);
                    $stokEntry->increment('sisa_stok', $sisaDikembalikan);
                    $sisaDikembalikan = 0;
                } else {
                    $stokEntry->decrement('jumlah_keluar', $stokDikeluarkan);
                    $stokEntry->increment('sisa_stok', $stokDikeluarkan);
                    $sisaDikembalikan -= $stokDikeluarkan;
                }
            }
        }
    }

    /**
     * Create sales transaction when order status changes to 'selesai'
     */
    private function createSalesTransaction($pesanan): void
    {
        // Check if transaction already exists for this order
        $existingTransaction = Transaksi::where('keterangan', 'like', '%Penjualan dari pesanan ' . $pesanan->kode_pesanan . '%')->first();
        if ($existingTransaction) {
            Log::info('Sales transaction already exists for order', [
                'pesanan_id' => $pesanan->id,
                'existing_transaksi_id' => $existingTransaction->id
            ]);
            return;
        }

        // Generate unique transaction code
        $tanggal = date('ymd', strtotime($pesanan->tanggal_pesanan));
        $timestamp = date('His') . substr(microtime(), 2, 3);
        $random = rand(10, 99);
        $kode_transaksi = 'TRX' . $tanggal . $timestamp . $random;

        // Create transaction
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kode_transaksi,
            'tanggal_transaksi' => now(),
            'jenis_transaksi' => 'penjualan',
            'total' => $pesanan->total_harga,
            'keterangan' => 'Penjualan dari pesanan ' . $pesanan->kode_pesanan . ' - ' . $pesanan->nama_pelanggan,
            'status' => 'selesai'
        ]);

        // Create transaction items
        foreach ($pesanan->pesananItems as $item) {
            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item->produk_id,
                'jumlah' => $item->jumlah,
                'harga_satuan' => $item->harga_satuan,
                'subtotal' => $item->subtotal
            ]);
        }

        Log::info('Sales transaction created', [
            'transaksi_id' => $transaksi->id,
            'kode_transaksi' => $kode_transaksi,
            'pesanan_id' => $pesanan->id,
            'total' => $pesanan->total_harga
        ]);
    }
}
