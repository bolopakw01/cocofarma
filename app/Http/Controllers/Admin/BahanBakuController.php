<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\MasterBahanBaku;
use App\Models\StokBahanBaku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;

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

        $perPage = request('per_page', 10); // Default 10, bisa diubah via parameter
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
            // Untuk operasional, tampilkan seluruh master bahan aktif agar bisa dipakai ulang
            $masterBahans = MasterBahanBaku::aktif()
                ->orderBy('nama_bahan')
                ->get();
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
                'master_bahan_id' => [
                    'required',
                    Rule::exists('master_bahan_baku', 'id')->where(function ($query) {
                        $query->where('status', 'aktif');
                    }),
                ],
                'nama_bahan' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = BahanBaku::where('master_bahan_id', $request->master_bahan_id)
                            ->where('nama_bahan', $value)
                            ->exists();
                        if ($exists) {
                            $masterBahan = MasterBahanBaku::find($request->master_bahan_id);
                            $fail("Nama bahan '{$value}' sudah ada untuk master bahan '{$masterBahan->nama_bahan}'.");
                        }
                    },
                ],
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'tanggal_kadaluarsa' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            // Remove kode_bahan from request so model generates it
            $requestData = $request->except('kode_bahan');
            $requestData['master_bahan_id'] = $request->master_bahan_id;
            $requestData['nama_bahan'] = $request->nama_bahan;
            $requestData['satuan'] = $request->satuan;
            $requestData['harga_per_satuan'] = $request->harga_per_satuan;
            $requestData['stok'] = $request->stok;
            $requestData['tanggal_masuk'] = $request->tanggal_masuk;
            $requestData['tanggal_kadaluarsa'] = $request->tanggal_kadaluarsa;
            $requestData['status'] = $request->status ?? 'aktif';

            BahanBaku::create($requestData);

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
     * Format: MB- + 10 random alphanumeric characters
     * Example: MB-A1B2C3D4E5
     * Ensures uniqueness by checking database for existing codes.
     */
    private function generateUniqueKodeMaster(?string $requestedKode, ?string $nama)
    {
        // Generate random kode bahan (MB-{10 random chars})
        $prefix = 'MB-';
        $maxAttempts = 20;
        $attempt = 0;

        do {
            $randomSegment = strtoupper(Str::random(10));
            $kode = $prefix . $randomSegment;
            $attempt++;
        } while (
            \App\Models\MasterBahanBaku::withTrashed()->where('kode_bahan', $kode)->exists() &&
            $attempt < $maxAttempts
        );

        if (\App\Models\MasterBahanBaku::withTrashed()->where('kode_bahan', $kode)->exists()) {
            throw new \RuntimeException('Gagal menghasilkan kode bahan baku unik setelah beberapa percobaan.');
        }

        return $kode;
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
     * Return JSON detail for a MasterBahanBaku to be used in SweetAlert popup
     */
    public function detail(string $id)
    {
        try {
            // Determine whether the request is for master-bahan or operasional bahan
            $isMaster = request()->routeIs('backoffice.master-bahan.*');

            if ($isMaster) {
                $master = MasterBahanBaku::with(['bahanBakus' => function ($q) {
                    $q->select('id', 'master_bahan_id', 'kode_bahan', 'nama_bahan', 'stok', 'harga_per_satuan');
                }])->withCount('bahanBakus')->findOrFail($id);

                $bahanList = $master->bahanBakus->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'kode_bahan' => $b->kode_bahan,
                        'nama_bahan' => $b->nama_bahan,
                        'stok' => $b->stok,
                        'harga_per_satuan' => $b->harga_per_satuan ?? null,
                    ];
                })->values();

                $data = [
                    'id' => $master->id,
                    'kode_bahan' => $master->kode_bahan,
                    'nama_bahan' => $master->nama_bahan,
                    'satuan' => $master->satuan,
                    'harga_per_satuan' => $master->harga_per_satuan,
                    'stok_minimum' => $master->stok_minimum,
                    'deskripsi' => $master->deskripsi,
                    'status' => $master->status,
                    'bahan_count' => $master->bahan_bakus_count ?? $bahanList->count(),
                    'bahan_list' => $bahanList,
                    'total_stok' => $master->bahanBakus->sum('stok'),
                    'rata_rata_harga' => $bahanList->isNotEmpty() ? $bahanList->avg('harga_per_satuan') : $master->harga_per_satuan,
                    'created_at' => optional($master->created_at)->toDateTimeString(),
                    'updated_at' => optional($master->updated_at)->toDateTimeString(),
                ];

                return response()->json($data);
            }

            // Operasional bahan detail
            $bahan = BahanBaku::with('masterBahan')->findOrFail($id);

            $data = [
                'id' => $bahan->id,
                'kode_bahan' => $bahan->kode_bahan,
                'nama_bahan' => $bahan->nama_bahan,
                'satuan' => $bahan->satuan,
                'stok' => $bahan->stok,
                'harga_per_satuan' => $bahan->harga_per_satuan,
                'deskripsi' => $bahan->deskripsi ?? null,
                'tanggal_masuk' => optional($bahan->tanggal_masuk)->toDateString(),
                'tanggal_kadaluarsa' => optional($bahan->tanggal_kadaluarsa)->toDateString(),
                'status' => $bahan->status,
                'master' => $bahan->masterBahan ? [
                    'id' => $bahan->masterBahan->id,
                    'kode_bahan' => $bahan->masterBahan->kode_bahan,
                    'nama_bahan' => $bahan->masterBahan->nama_bahan,
                ] : null,
                'created_at' => optional($bahan->created_at)->toDateTimeString(),
                'updated_at' => optional($bahan->updated_at)->toDateTimeString(),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            // Log full exception for debugging
            Log::error('Failed to load bahan detail: ' . $e->getMessage(), ['id' => $id, 'exception' => $e]);

            // Return a friendly JSON error message with 500 status
            return response()->json([
                'error' => true,
                'message' => 'Gagal memuat detail bahan. Silakan coba lagi atau hubungi administrator.'
            ], 500);
        }
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
            // Load seluruh master bahan aktif agar operator bisa berpindah template jika diperlukan
            $masterBahans = MasterBahanBaku::aktif()
                ->orderBy('nama_bahan')
                ->get();
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
                'nama_bahan' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request, $id) {
                        $exists = BahanBaku::where('master_bahan_id', $request->master_bahan_id)
                            ->where('nama_bahan', $value)
                            ->where('id', '!=', $id)
                            ->exists();
                        if ($exists) {
                            $masterBahan = MasterBahanBaku::find($request->master_bahan_id);
                            $fail("Nama bahan '{$value}' sudah ada untuk master bahan '{$masterBahan->nama_bahan}'.");
                        }
                    },
                ],
                'satuan' => 'required|string|max:50',
                'harga_per_satuan' => 'required|numeric|min:0',
                'stok' => 'required|numeric|min:0',
                'tanggal_masuk' => 'required|date',
                'tanggal_kadaluarsa' => 'nullable|date',
                'status' => 'nullable|string|in:aktif,nonaktif'
            ]);

            $bahanBaku = BahanBaku::findOrFail($id);

            // Don't update kode_bahan - let the model handle it if needed
            $bahanBaku->update([
                'master_bahan_id' => $request->master_bahan_id,
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
