<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\MasterBahanBaku;
use App\Models\StokBahanBaku;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $viewPath = $isMaster ? 'admin.pages.master-bahan.index-master-bahan' : 'admin.pages.bahanbaku.index-bahanbaku';

        // Debug logging
        Log::info('BahanBakuController index called', [
            'isMaster' => $isMaster,
            'route' => request()->route() ? request()->route()->getName() : 'no route',
            'viewPath' => $viewPath
        ]);

        if ($isMaster) {
            // Untuk master bahan, gunakan MasterBahanBaku model
            $query = MasterBahanBaku::query();
            Log::info('Using MasterBahanBaku model');
        } else {
            // Untuk operasional, gunakan BahanBaku model
            $query = BahanBaku::query();
            Log::info('Using BahanBaku model');
        }

        $perPage = request('per_page', 5); // Default 5, bisa diubah via parameter
        Log::info('Per page: ' . $perPage);

        if ($perPage === 'all') {
            // Return all results but wrap them in a paginator so the view stays compatible
            $allItems = $query->get();
            $currentPage = Paginator::resolveCurrentPage();
            $perPageCount = $allItems->count() ?: 1; // avoid zero
            $currentItems = $allItems->slice(($currentPage - 1) * $perPageCount, $perPageCount)->values();

            $bahanBakus = new LengthAwarePaginator($currentItems, $allItems->count(), $perPageCount, $currentPage, [
                'path' => Paginator::resolveCurrentPath(),
                'query' => request()->query()
            ]);
        } else {
            $bahanBakus = $query->paginate((int) $perPage)->appends(request()->query());
        }

        Log::info('Query result', [
            'count' => $bahanBakus->count(),
            'total' => $bahanBakus->total()
        ]);

        return view($viewPath, compact('bahanBakus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $viewPath = $isMaster ? 'admin.pages.master-bahan.create-master-bahan' : 'admin.pages.bahanbaku.create-bahanbaku';

        if (!$isMaster) {
            // Untuk operasional, load master bahan untuk dropdown
            $masterBahans = MasterBahanBaku::aktif()->get();
            return view($viewPath, compact('masterBahans'));
        }

        // For master create, provide a preview based on optional 'nama_bahan' query
        $preview = $this->generateUniqueKodeMaster(null, request()->query('nama_bahan'));
        return view($viewPath, compact('preview'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $routeName = $isMaster ? 'backoffice.master-bahan.index' : 'backoffice.bahanbaku.index';

        if ($isMaster) {
            // Ensure kode_bahan is unique server-side even if client generated one
            $generatedKode = $this->generateUniqueKodeMaster($request->input('kode_bahan'), $request->input('nama_bahan'));
            // Merge back into request so validation sees final kode
            $request->merge(['kode_bahan' => $generatedKode]);

            // Validation untuk master bahan
            $request->validate([
                'kode_bahan' => 'nullable|string|max:50|unique:master_bahan_baku,kode_bahan',
                'nama_bahan' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok_minimum' => 'nullable|numeric|min:0',
                'deskripsi' => 'nullable|string',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            $master = MasterBahanBaku::create([
                'kode_bahan' => $request->kode_bahan,
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'harga_per_satuan' => $request->harga_per_satuan,
                'stok_minimum' => $request->stok_minimum,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status ?? 'aktif'
            ]);

            // Propagate harga_per_satuan to any existing operational bahan and their stock entries
            try {
                $affectedBahanIds = $master->bahanBakus()->pluck('id')->toArray();
                if (!empty($affectedBahanIds)) {
                    // Update operational bahan prices
                    BahanBaku::whereIn('id', $affectedBahanIds)->update(['harga_per_satuan' => $master->harga_per_satuan]);

                    // Update active stok detail prices where there is remaining stock
                    StokBahanBaku::whereIn('bahan_baku_id', $affectedBahanIds)
                        ->where('sisa_stok', '>', 0)
                        ->update(['harga_satuan' => $master->harga_per_satuan]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to propagate master bahan harga to operasional: ' . $e->getMessage());
            }

            return redirect()->route($routeName)->with('success', 'Master bahan baku berhasil dibuat.');
        } else {
            // Validation untuk operasional bahan
            $request->validate([
                'master_bahan_id' => 'required|exists:master_bahan_baku,id',
                'kode_bahan' => 'required|string|max:50|unique:bahan_baku,kode_bahan',
                'nama_bahan' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'tanggal_kadaluarsa' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            BahanBaku::create([
                'master_bahan_id' => $request->master_bahan_id,
                'kode_bahan' => $request->kode_bahan,
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'harga_per_satuan' => $request->harga_per_satuan,
                'stok' => $request->stok,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'status' => $request->status ?? 'aktif'
            ]);

            return redirect()->route($routeName)->with('success', 'Bahan baku berhasil dibuat.');
        }
    }

    /**
     * AJAX/preview endpoint for master bahan kode
     */
    public function previewKode(Request $request)
    {
        $kode = $this->generateUniqueKodeMaster($request->input('kode_bahan'), $request->input('nama_bahan'));
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['kode' => $kode]);
        }

        return $kode;
    }

    /**
     * Generate a unique kode for MasterBahanBaku.
     * Always auto-generate based on name, ignore requested kode.
     * Format: BB + YYMMDD + first 3 letters of name (uppercased, padded with X) + 3-digit sequence (001, 002...)
     * Example: BB250924KOP001
     */
    private function generateUniqueKodeMaster(?string $requestedKode, ?string $nama)
    {
        // Always generate based on name, ignore requestedKode
        $today = now();
        $dateString = $today->format('ymd'); // YYMMDD

        // Clean name to letters only and take first 3 characters (pad with X if needed)
        $cleanName = $nama ? strtoupper(preg_replace('/[^A-Z]/', '', $nama)) : '';
        $prefixName = substr($cleanName, 0, 3);
        $prefixName = str_pad($prefixName, 3, 'X');

        $base = 'BB' . $dateString . $prefixName; // e.g. BB250924KOP

        // Find existing codes that start with this base and extract the numeric suffix
        $existing = \App\Models\MasterBahanBaku::where('kode_bahan', 'like', $base . '%')->pluck('kode_bahan')->toArray();

        $max = 0;
        foreach ($existing as $code) {
            if (preg_match('/(\d{3})$/', $code, $m)) {
                $num = intval($m[1]);
                if ($num > $max) $max = $num;
            }
        }

        $next = $max + 1;
        if ($next > 999) {
            // safety if sequence grows beyond 999, use 4 digits
            $suffix = str_pad($next, 4, '0', STR_PAD_LEFT);
        } else {
            $suffix = str_pad($next, 3, '0', STR_PAD_LEFT);
        }

        $candidate = $base . $suffix; // e.g. BB250924KOP001

        // Final uniqueness check (loop just in case)
        $safety = 0;
        while (\App\Models\MasterBahanBaku::where('kode_bahan', $candidate)->exists()) {
            $next++;
            $suffix = $next > 999 ? str_pad($next, 4, '0', STR_PAD_LEFT) : str_pad($next, 3, '0', STR_PAD_LEFT);
            $candidate = $base . $suffix;
            $safety++;
            if ($safety > 10000) break; // avoid infinite loop
        }

        return $candidate;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $viewPath = $isMaster ? 'admin.pages.master-bahan.show-master-bahan' : 'admin.pages.bahanbaku.show-bahanbaku';

        if ($isMaster) {
            $bahanBaku = MasterBahanBaku::findOrFail($id);
        } else {
            $bahanBaku = BahanBaku::findOrFail($id);
        }

        return view($viewPath, compact('bahanBaku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $viewPath = $isMaster ? 'admin.pages.master-bahan.edit-master-bahan' : 'admin.pages.bahanbaku.edit-bahanbaku';

        if ($isMaster) {
            $bahanBaku = MasterBahanBaku::findOrFail($id);
        } else {
            $bahanBaku = BahanBaku::findOrFail($id);
            // Load master bahan untuk dropdown
            $masterBahans = MasterBahanBaku::aktif()->get();
            return view($viewPath, compact('bahanBaku', 'masterBahans'));
        }

        return view($viewPath, compact('bahanBaku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $routeName = $isMaster ? 'backoffice.master-bahan.index' : 'backoffice.bahanbaku.index';

        if ($isMaster) {
            // Validation untuk master bahan
            $request->validate([
                'kode_bahan' => 'required|string|max:50|unique:master_bahan_baku,kode_bahan,' . $id,
                'nama_bahan' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok_minimum' => 'nullable|numeric|min:0',
                'deskripsi' => 'nullable|string',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            $bahanBaku = MasterBahanBaku::findOrFail($id);

            $bahanBaku->update([
                'kode_bahan' => $request->kode_bahan,
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'harga_per_satuan' => $request->harga_per_satuan,
                'stok_minimum' => $request->stok_minimum,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status ?? 'aktif'
            ]);

            // Propagate updated price to operational bahan and stock details
            try {
                $affectedBahanIds = $bahanBaku->bahanBakus()->pluck('id')->toArray();
                if (!empty($affectedBahanIds)) {
                    BahanBaku::whereIn('id', $affectedBahanIds)
                        ->update(['harga_per_satuan' => $bahanBaku->harga_per_satuan]);

                    StokBahanBaku::whereIn('bahan_baku_id', $affectedBahanIds)
                        ->where('sisa_stok', '>', 0)
                        ->update(['harga_satuan' => $bahanBaku->harga_per_satuan]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to propagate updated master bahan harga to operasional: ' . $e->getMessage());
            }

            return redirect()->route($routeName)->with('success', 'Master bahan baku berhasil diperbarui.');
        } else {
            // Validation untuk operasional bahan
            $request->validate([
                'master_bahan_id' => 'required|exists:master_bahan_baku,id',
                'kode_bahan' => 'required|string|max:50|unique:bahan_baku,kode_bahan,' . $id,
                'nama_bahan' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'tanggal_kadaluarsa' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            $bahanBaku = BahanBaku::findOrFail($id);

            $bahanBaku->update([
                'master_bahan_id' => $request->master_bahan_id,
                'kode_bahan' => $request->kode_bahan,
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'harga_per_satuan' => $request->harga_per_satuan,
                'stok' => $request->stok,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'status' => $request->status ?? 'aktif'
            ]);

            return redirect()->route($routeName)->with('success', 'Bahan baku berhasil diperbarui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isMaster = request()->routeIs('backoffice.master-bahan.*');
        $routeName = $isMaster ? 'backoffice.master-bahan.index' : 'backoffice.bahanbaku.index';

        if ($isMaster) {
            $bahanBaku = MasterBahanBaku::findOrFail($id);
            $bahanBaku->delete();
            $message = 'Master bahan baku berhasil dihapus.';
        } else {
            $bahanBaku = BahanBaku::findOrFail($id);
            $bahanBaku->delete();
            $message = 'Bahan baku berhasil dihapus.';
        }

        return redirect()->route($routeName)->with('success', $message);
    }
}
