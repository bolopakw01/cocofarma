<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        // Allow optional preview via query param 'nama_produk'
        $preview = $this->generatePreviewKode(null, request()->query('nama_produk'));
        $gradeOptions = Pengaturan::getProductGrades();

        return view('admin.pages.master-produk.create-master-produk', [
            'preview' => $preview,
            'gradeOptions' => $gradeOptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Kode produk akan di-generate otomatis di model, tidak perlu merge atau validate unique
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'grade_kualitas' => 'nullable|string|max:25',
            'harga_jual' => 'required|numeric|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_cropped' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'grade_kualitas' => $request->grade_kualitas,
            'harga_jual' => $request->harga_jual,
            'stok' => 0, // Default stok 0 untuk produk baru
            'minimum_stok' => $request->minimum_stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status === 'active' ? 'aktif' : 'nonaktif'
        ];

        // Handle foto_cropped (base64) first -- if present it takes precedence over uploaded file
        if ($request->filled('foto_cropped')) {
            $base64 = $request->input('foto_cropped');
            if (preg_match('/^data:(image\/\w+);base64,/', $base64, $type)) {
                $dataBase64 = substr($base64, strpos($base64, ',') + 1);
                $dataBase64 = base64_decode($dataBase64);
                $extension = explode('/', $type[1])[1];
                $extension = $extension === 'jpeg' ? 'jpg' : $extension;
                $filename = 'produk_' . Str::random(12) . '.' . $extension;
                Storage::disk('produk_foto')->put($filename, $dataBase64);
                $data['foto'] = $filename;
            }
        } elseif ($request->hasFile('foto')) {
            // Fallback to normal file upload
            $fotoPath = $request->file('foto')->store('', 'produk_foto');
            $data['foto'] = basename($fotoPath);
        }

        Produk::create($data);

        return redirect()->route('backoffice.master-produk.index')
                        ->with('success', 'Produk berhasil dibuat.');
    }

    /**
     * Preview/generate a candidate kode_produk without reserving/incrementing the counter.
     * Returns JSON when used as AJAX, or raw code when called internally.
     */
    public function previewKode(Request $request)
    {
        $kode = $this->generatePreviewKode($request->input('kode_produk'), $request->input('nama_produk'));
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['kode' => $kode]);
        }

        return $kode;
    }

    /**
     * Generate a preview code for produk based on name only (always auto-generate).
     * Format: MP + DDMMYY + 3 letters from name + global sequence per day
     * Example: MP240925BOL001
     */
    private function generatePreviewKode(?string $requestedKode, ?string $nama)
    {
        // Always generate based on name
        $date = now()->format('dmy'); // DDMMYY
        $base = 'MP' . $date; // key for counter

        $name = $nama ?? '';
        $abbr = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        if ($abbr === '') {
            $abbr = 'XXX';
        }

        // Inspect CodeCounter to compute next number but do not modify it
        $current = \App\Models\CodeCounter::where('key', $base)->value('counter');
        $next = ($current ? intval($current) : 0) + 1;
        $nextNumber = str_pad((string) $next, 3, '0', STR_PAD_LEFT);

        return $base . $abbr . $nextNumber;
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
        $gradeOptions = Pengaturan::getProductGrades();

        return view('admin.pages.master-produk.edit-master-produk', [
            'produk' => $produk,
            'gradeOptions' => $gradeOptions
        ]);
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
            'grade_kualitas' => 'nullable|string|max:25',
            'harga_jual' => 'required|numeric|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_cropped' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'grade_kualitas' => $request->grade_kualitas,
            'harga_jual' => $request->harga_jual,
            'minimum_stok' => $request->minimum_stok,
            'deskripsi' => $request->deskripsi,
            // Normalize incoming status (same mapping as store)
            'status' => $request->status === 'active' ? 'aktif' : 'nonaktif'
        ];

        // Handle foto_cropped (base64) first -- if present it takes precedence over uploaded file
        if ($request->filled('foto_cropped')) {
            // Delete old foto if exists
            if ($produk->foto && Storage::disk('produk_foto')->exists($produk->foto)) {
                Storage::disk('produk_foto')->delete($produk->foto);
            }

            $base64 = $request->input('foto_cropped');
            if (preg_match('/^data:(image\/\w+);base64,/', $base64, $type)) {
                $dataBase64 = substr($base64, strpos($base64, ',') + 1);
                $dataBase64 = base64_decode($dataBase64);
                $extension = explode('/', $type[1])[1];
                $extension = $extension === 'jpeg' ? 'jpg' : $extension;
                $filename = 'produk_' . Str::random(12) . '.' . $extension;
                Storage::disk('produk_foto')->put($filename, $dataBase64);
                $data['foto'] = $filename;
            }
        } elseif ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($produk->foto && Storage::disk('produk_foto')->exists($produk->foto)) {
                Storage::disk('produk_foto')->delete($produk->foto);
            }

            $fotoPath = $request->file('foto')->store('', 'produk_foto');
            $data['foto'] = basename($fotoPath);
        }

        // Handle foto removal
        if ($request->remove_foto == '1') {
            if ($produk->foto && Storage::disk('produk_foto')->exists($produk->foto)) {
                Storage::disk('produk_foto')->delete($produk->foto);
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
        // Only super_admin can delete products
        if (!(Auth::check() && Auth::user()->role === 'super_admin')) {
            return redirect()->route('backoffice.master-produk.index')
                        ->with('error', 'Hanya Super Admin yang dapat menghapus produk.');
        }

        // Delete foto if exists
        if ($produk->foto && Storage::disk('produk_foto')->exists($produk->foto)) {
            Storage::disk('produk_foto')->delete($produk->foto);
        }

        // Deleting product will cascade to produksis and stok_produks per DB foreign keys
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
                        ->where('status', 'aktif')
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
                 ->where('status', 'aktif')
                 ->get();

        return response()->json([
            'low_stock_count' => $lowStockProducts->count(),
            'low_stock_products' => $lowStockProducts
        ]);
    }
}
