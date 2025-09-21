<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 15); // Default 15, bisa diubah via parameter

        if ($perPage === 'all') {
            // Return all results but wrap them in a paginator so the view stays compatible
            $allItems = Produk::orderBy('nama_produk')->get();
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPageCount = $allItems->count() ?: 1; // avoid zero
            $currentItems = $allItems->slice(($currentPage - 1) * $perPageCount, $perPageCount)->values();

            $produks = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $allItems->count(), $perPageCount, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => request()->query()
            ]);
        } else {
            $produks = Produk::orderBy('nama_produk')->paginate((int) $perPage)->appends(request()->query());
        }

        return view('admin.pages.master-produk.index-master-produk', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.master-produk.create-master-produk');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|string|max:50|unique:produks,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1'
        ]);

        $data = [
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga_jual' => $request->harga_jual,
            'stok' => 0, // Default stok 0 untuk produk baru
            'minimum_stok' => $request->minimum_stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status
        ];

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
            $data['foto'] = basename($fotoPath);
        }

        Produk::create($data);

        return redirect()->route('backoffice.master-produk.index')
                        ->with('success', 'Produk berhasil dibuat dengan kode: ' . $request->kode_produk);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return view('admin.pages.master-produk.show-master-produk', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('admin.pages.master-produk.edit-master-produk', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1'
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga_jual' => $request->harga_jual,
            'minimum_stok' => $request->minimum_stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status
        ];

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($produk->foto && Storage::disk('public')->exists('produk/' . $produk->foto)) {
                Storage::disk('public')->delete('produk/' . $produk->foto);
            }

            $fotoPath = $request->file('foto')->store('produk', 'public');
            $data['foto'] = basename($fotoPath);
        }

        // Handle foto removal
        if ($request->remove_foto == '1') {
            if ($produk->foto && Storage::disk('public')->exists('produk/' . $produk->foto)) {
                Storage::disk('public')->delete('produk/' . $produk->foto);
            }
            $data['foto'] = null;
        }

        $produk->update($data);

        return redirect()->route('backoffice.master-produk.index')
                        ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        // Delete foto if exists
        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('backoffice.master-produk.index')
                        ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Get products by category for AJAX
     */
    public function getByCategory(Request $request)
    {
        $kategori = $request->query('kategori');
        $produks = Produk::where('kategori', $kategori)
                        ->where('status', true)
                        ->orderBy('nama_produk')
                        ->get();

        return response()->json($produks);
    }

    /**
     * Check stock status for dashboard
     */
    public function checkStockStatus()
    {
        $lowStockProducts = Produk::whereColumn('stok', '<=', 'minimum_stok')
                                 ->where('status', true)
                                 ->get();

        return response()->json([
            'low_stock_count' => $lowStockProducts->count(),
            'low_stock_products' => $lowStockProducts
        ]);
    }
}
