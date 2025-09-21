<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $produks = Produk::where('status', 1)->orderBy('nama_produk')->get();
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
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            // Generate kode pesanan
            $tanggal = date('ymd', strtotime($request->tanggal_pesanan));
            $count = Pesanan::whereDate('tanggal_pesanan', $request->tanggal_pesanan)->count() + 1;
            $kode_pesanan = 'PSN' . $tanggal . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Hitung total harga
            $total_harga = 0;
            foreach ($request->items as $item) {
                $total_harga += $item['jumlah'] * $item['harga_satuan'];
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'kode_pesanan' => $kode_pesanan,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'status' => 'pending',
                'total_harga' => $total_harga
            ]);

            // Buat pesanan items
            foreach ($request->items as $item) {
                PesananItem::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['jumlah'] * $item['harga_satuan']
                ]);
            }
        });

        return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil dibuat.');
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
        $produks = Produk::where('status', 1)->orderBy('nama_produk')->get();
        return view('admin.pages.pesanan.edit-pesanan', compact('pesanan', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $request->validate([
            'tanggal_pesanan' => 'required|date',
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'status' => 'required|in:pending,diproses,selesai,dibatalkan',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $pesanan) {
            // Hitung total harga
            $total_harga = 0;
            foreach ($request->items as $item) {
                $total_harga += $item['jumlah'] * $item['harga_satuan'];
            }

            // Update pesanan
            $pesanan->update([
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'status' => $request->status,
                'total_harga' => $total_harga
            ]);

            // Hapus pesanan items lama
            $pesanan->pesananItems()->delete();

            // Buat pesanan items baru
            foreach ($request->items as $item) {
                PesananItem::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['jumlah'] * $item['harga_satuan']
                ]);
            }
        });

        return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Hapus pesanan items terlebih dahulu
        $pesanan->pesananItems()->delete();

        // Hapus pesanan
        $pesanan->delete();

        return redirect()->route('backoffice.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
