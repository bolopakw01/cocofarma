<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Produk;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['transaksiItems.produk', 'transaksiItems.bahanBaku']);

        // Filter berdasarkan jenis transaksi
        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan kode transaksi atau keterangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

    // Handle per-page entries (support 'all' => 1000). Default to 5 for transaksi page.
    $perPage = $request->get('per_page', 5);
        if ($perPage === 'all') {
            $perPage = 1000;
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 15;
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);

        return view('admin.pages.transaksi.index-transaksi', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jenis = $request->get('jenis', 'penjualan'); // default penjualan

        if ($jenis === 'penjualan') {
            $items = Produk::where('status', 1)->orderBy('nama_produk')->get();
        } else {
            $items = BahanBaku::orderBy('nama_bahan')->get();
        }

        return view('admin.pages.transaksi.create-transaksi', compact('jenis', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:penjualan,pembelian',
            'keterangan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate kode transaksi
            $prefix = $request->jenis_transaksi === 'penjualan' ? 'TRJ' : 'TRB';
            $date = date('Ymd');
            $lastTransaction = Transaksi::where('kode_transaksi', 'like', "{$prefix}-{$date}%")
                                       ->orderBy('kode_transaksi', 'desc')
                                       ->first();

            if ($lastTransaction) {
                $lastNumber = intval(substr($lastTransaction->kode_transaksi, -3));
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $kodeTransaksi = "{$prefix}-{$date}-{$newNumber}";

            // Hitung total
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['jumlah'] * $item['harga_satuan'];
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'jenis_transaksi' => $request->jenis_transaksi,
                'total' => $total,
                'keterangan' => $request->keterangan,
                'status' => 'selesai' // langsung selesai karena ini pencatatan
            ]);

            // Buat transaksi items
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];

                if ($request->jenis_transaksi === 'penjualan') {
                    // Update stok produk
                    $produk = Produk::findOrFail($item['item_id']);
                    if ($produk->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi");
                    }
                    $produk->decrement('stok', $item['jumlah']);

                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item['item_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal
                    ]);
                } else {
                    // Untuk pembelian, update stok bahan baku
                    $bahanBaku = BahanBaku::findOrFail($item['item_id']);
                    $bahanBaku->increment('stok', $item['jumlah']);

                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'bahan_baku_id' => $item['item_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('backoffice.transaksi.index')
                           ->with('success', 'Transaksi berhasil dibuat dengan kode: ' . $kodeTransaksi);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                       ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['transaksiItems.produk', 'transaksiItems.bahanBaku'])
                             ->findOrFail($id);

        return view('admin.pages.transaksi.show-transaksi', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = Transaksi::with(['transaksiItems.produk', 'transaksiItems.bahanBaku'])
                             ->findOrFail($id);

        $jenis = $transaksi->jenis_transaksi;

        if ($jenis === 'penjualan') {
            $items = Produk::where('status', 1)->orderBy('nama_produk')->get();
        } else {
            $items = BahanBaku::orderBy('nama_bahan')->get();
        }

        return view('admin.pages.transaksi.edit-transaksi', compact('transaksi', 'jenis', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Kembalikan stok lama
            foreach ($transaksi->transaksiItems as $oldItem) {
                if ($transaksi->jenis_transaksi === 'penjualan') {
                    // Kembalikan stok produk
                    $produk = Produk::findOrFail($oldItem->produk_id);
                    $produk->increment('stok', $oldItem->jumlah);
                } else {
                    // Kurangi stok bahan baku
                    $bahanBaku = BahanBaku::findOrFail($oldItem->bahan_baku_id);
                    $bahanBaku->decrement('stok', $oldItem->jumlah);
                }
            }

            // Hapus item lama
            $transaksi->transaksiItems()->delete();

            // Hitung total baru
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['jumlah'] * $item['harga_satuan'];
            }

            // Update transaksi
            $transaksi->update([
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'total' => $total,
                'keterangan' => $request->keterangan,
            ]);

            // Buat transaksi items baru
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];

                if ($transaksi->jenis_transaksi === 'penjualan') {
                    // Kurangi stok produk baru
                    $produk = Produk::findOrFail($item['item_id']);
                    if ($produk->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi");
                    }
                    $produk->decrement('stok', $item['jumlah']);

                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item['item_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal
                    ]);
                } else {
                    // Tambah stok bahan baku baru
                    $bahanBaku = BahanBaku::findOrFail($item['item_id']);
                    $bahanBaku->increment('stok', $item['jumlah']);

                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'bahan_baku_id' => $item['item_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $subtotal
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('backoffice.transaksi.index')
                           ->with('success', 'Transaksi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                       ->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        try {
            DB::beginTransaction();

            // Kembalikan stok
            foreach ($transaksi->transaksiItems as $item) {
                if ($transaksi->jenis_transaksi === 'penjualan') {
                    // Kembalikan stok produk
                    $produk = Produk::findOrFail($item->produk_id);
                    $produk->increment('stok', $item->jumlah);
                } else {
                    // Kurangi stok bahan baku
                    $bahanBaku = BahanBaku::findOrFail($item->bahan_baku_id);
                    $bahanBaku->decrement('stok', $item->jumlah);
                }
            }

            // Hapus transaksi dan items
            $transaksi->transaksiItems()->delete();
            $transaksi->delete();

            DB::commit();

            return redirect()->route('backoffice.transaksi.index')
                           ->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
