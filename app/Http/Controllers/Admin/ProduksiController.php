<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\BatchProduksi;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\ProduksiBahan;
use App\Models\StokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Produksi::with(['produk', 'user', 'batchProduksi']);

        // Handle search
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

        $produksis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pages.produksi.index-produksi', compact('produksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::where('status', 'aktif')->get();
        $bahanBakus = BahanBaku::with('stokBahanBaku')->where('status', 'aktif')->get();

    return view('admin.pages.produksi.create-produksi', compact('produks', 'bahanBakus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalize possible incoming input from the view: some templates send 'bahan_digunakan[...]' names
        if ($request->has('bahan_digunakan') && !$request->has('bahan_baku')) {
            $normalized = [];
            foreach ($request->input('bahan_digunakan') as $item) {
                $id = $item['bahan_baku_id'] ?? ($item['id'] ?? null);
                $jumlah = $item['jumlah'] ?? 0;
                if ($id) {
                    $normalized[] = ['id' => $id, 'jumlah' => $jumlah];
                }
            }
            $request->merge(['bahan_baku' => $normalized]);
        }

    $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_target' => 'required|numeric|min:0.01',
            'catatan_produksi' => 'nullable|string',
            'bahan_baku' => 'required|array',
            'bahan_baku.*.id' => 'required|exists:bahan_baku,id',
            'bahan_baku.*.jumlah' => 'required|numeric|min:0.001',
        ]);

        // Perform check and creation inside a DB transaction with row locks to avoid race conditions
        $requestedTotals = [];
        foreach ($request->input('bahan_baku') as $b) {
            $bid = intval($b['id']);
            $j = floatval($b['jumlah']);
            if (!isset($requestedTotals[$bid])) $requestedTotals[$bid] = 0;
            $requestedTotals[$bid] += $j;
        }

        DB::beginTransaction();
        try {
            $ids = array_keys($requestedTotals);
            // lock rows for update
            $bahanRows = BahanBaku::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

            $shortages = [];
            $EPS = 0.01;
            foreach ($requestedTotals as $bid => $totalReq) {
                $avail = isset($bahanRows[$bid]) ? floatval($bahanRows[$bid]->stok) : 0;
                if ($totalReq > $avail + $EPS) {
                    $shortages[] = ['id' => $bid, 'requested' => $totalReq, 'available' => $avail];
                }
            }

            if (!empty($shortages)) {
                DB::rollBack();
                $lines = [];
                foreach ($shortages as $s) {
                    $bahan = $bahanRows[$s['id']] ?? BahanBaku::find($s['id']);
                    $name = $bahan ? $bahan->nama_bahan : ('ID ' . $s['id']);
                    $lines[] = $name . ': Diminta ' . number_format($s['requested'], 4) . ', Tersedia ' . number_format($s['available'], 4);
                }
                $msg = "Stok tidak mencukupi untuk beberapa bahan:\n" . implode("\n", $lines) . "\n\nSilakan sesuaikan jumlah atau perbarui stok.";
                return redirect()->back()->withInput()->withErrors(['bahan_baku' => $msg]);
            }

        // Generate nomor produksi unik per hari dengan mengunci batch terakhir
        $prefix = 'PRD-' . now()->format('Ymd') . '-';
        $latestBatch = BatchProduksi::withTrashed()
            ->where('nomor_batch', 'like', $prefix . '%')
            ->orderByDesc('nomor_batch')
            ->lockForUpdate()
            ->first();

        $nextSequence = 1;
        if ($latestBatch) {
            $lastNumber = (int) substr($latestBatch->nomor_batch, strlen($prefix));
            $nextSequence = $lastNumber + 1;
        }

        // Pastikan juga tidak bertabrakan dengan produksi yang sudah ada
        do {
            $nomorProduksi = $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
            $nextSequence++;
        } while (
            BatchProduksi::withTrashed()->where('nomor_batch', $nomorProduksi)->exists() ||
            Produksi::withTrashed()->where('nomor_produksi', $nomorProduksi)->exists()
        );

        // Create batch produksi with the production code as batch number
        $batchProduksi = BatchProduksi::create([
            'nomor_batch' => $nomorProduksi,
            'produk_id' => $request->produk_id,
            'tanggal_produksi' => $request->tanggal_produksi,
            'status' => 'rencana',
            'user_id' => optional(Auth::user())->id,
        ]);

        $batchProduksiId = $batchProduksi->id;

            // Create produksi record
            $produksi = Produksi::create([
                'nomor_produksi' => $nomorProduksi,
                'batch_produksi_id' => $batchProduksiId,
                'produk_id' => $request->produk_id,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_target' => $request->jumlah_target,
                'catatan_produksi' => $request->input('catatan_produksi'),
                'user_id' => optional(Auth::user())->id,
            ]);

            // Calculate total cost and save bahan usage, decrementing locked rows
            $totalCost = 0;
            foreach ($request->bahan_baku as $bahan) {
                $bId = intval($bahan['id']);
                $bRow = $bahanRows[$bId] ?? BahanBaku::find($bId);
                $hargaSatuan = isset($bRow->harga_per_satuan) ? $bRow->harga_per_satuan : 0;
                $biayaBahan = $hargaSatuan * $bahan['jumlah'];
                $totalCost += $biayaBahan;

                ProduksiBahan::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $bahan['id'],
                    'stok_bahan_baku_id' => null, // Nullable untuk saat ini
                    'jumlah_digunakan' => $bahan['jumlah'],
                    'harga_satuan' => $hargaSatuan,
                    'total_biaya' => $biayaBahan,
                ]);

                // decrement using the locked model to ensure consistency
                if ($bRow) {
                    $bRow->stok = floatval($bRow->stok) - floatval($bahan['jumlah']);
                    $bRow->save();
                } else {
                    BahanBaku::where('id', $bahan['id'])->decrement('stok', $bahan['jumlah']);
                }
            }

            $produksi->update(['biaya_produksi' => $totalCost]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    return redirect()->route('backoffice.produksi.index')->with('success', 'Rencana produksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produksi $produksi)
    {
        $produksi->load(['produk', 'user', 'produksiBahans.bahanBaku']);
        $bahanBakus = BahanBaku::with('stokBahanBaku')->where('status', 'aktif')->get();
        $grades = \App\Models\Pengaturan::getProductGrades();

        return view('admin.pages.produksi.show-produksi', compact('produksi', 'bahanBakus', 'grades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produksi $produksi)
    {
        $produksi->load(['produksiBahans.bahanBaku']);
        $produks = Produk::where('status', 'aktif')->get();
        $bahanBakus = BahanBaku::where('status', 'aktif')->get();

        // Load dynamic grades for the view
        $grades = \App\Models\Pengaturan::getProductGrades();
        // If no grades configured, don't show any grade options
        // Only show fallback grades if grades array is completely empty (not configured yet)

        return view('admin.pages.produksi.edit-produksi', compact('produksi', 'produks', 'bahanBakus', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produksi $produksi)
    {
        // Normalize possible incoming input from the view: some templates send 'bahan_digunakan[...]' names
        if ($request->has('bahan_digunakan') && !$request->has('bahan_baku')) {
            $normalized = [];
            foreach ($request->input('bahan_digunakan') as $item) {
                $id = $item['bahan_baku_id'] ?? ($item['id'] ?? null);
                $jumlah = $item['jumlah'] ?? 0;
                if ($id) {
                    $normalized[] = ['id' => $id, 'jumlah' => $jumlah];
                }
            }
            $request->merge(['bahan_baku' => $normalized]);
        }

        $request->validate([
            'produk_id' => 'sometimes|exists:produks,id',
            'tanggal_produksi' => 'sometimes|date',
            'jumlah_target' => 'sometimes|numeric|min:0.01',
            'status' => 'required|in:rencana,proses,selesai,gagal',
            'jumlah_hasil' => 'nullable|numeric|min:0',
            'catatan_produksi' => 'nullable|string',
            'bahan_baku' => 'nullable|array',
            'bahan_baku.*.id' => 'required_with:bahan_baku|exists:bahan_baku,id',
            'bahan_baku.*.jumlah' => 'required_with:bahan_baku|numeric|min:0.001',
            'transfer_ke_produk' => 'required_if:status,selesai|boolean',
            'grade_kualitas' => [
                function ($attribute, $value, $fail) {
                    // Only validate grade if grades are configured
                    $grades = \App\Models\Pengaturan::getProductGrades();
                    if (!empty($grades)) {
                        // If grades are configured, grade_kualitas is required
                        if (empty($value)) {
                            $fail('Grade kualitas harus dipilih.');
                            return;
                        }

                        $maxGradeIndex = count($grades) - 1;
                        $validGrades = [];
                        for ($i = 0; $i <= $maxGradeIndex; $i++) {
                            $validGrades[] = chr(65 + $i); // A, B, C, D, etc.
                        }

                        if (!in_array($value, $validGrades)) {
                            $fail('Grade kualitas yang dipilih tidak valid.');
                        }
                    }
                    // If no grades configured, allow empty/null values
                },
            ],
        ]);

        // Additional validation: jumlah_hasil is required when status is selesai
        if ($request->status === 'selesai' && (is_null($request->jumlah_hasil) || $request->jumlah_hasil === '')) {
            return redirect()->back()->withInput()->withErrors(['jumlah_hasil' => 'Jumlah hasil produksi harus diisi ketika status diset ke "Selesai".']);
        }

    $transferNow = $request->status === 'selesai' ? $request->boolean('transfer_ke_produk') : null;

    $originalStatus = $produksi->status;
    $originalTransferStatus = $produksi->status_transfer ?? 'pending';
    $originalJumlahHasil = (float) ($produksi->jumlah_hasil ?? 0);
    $originalProductId = (int) $produksi->produk_id;
    $totalCost = (float) ($produksi->biaya_produksi ?? 0);
    $effectiveWasCompleted = $originalStatus === 'selesai' && $originalTransferStatus === 'transferred';
    $effectiveOriginalJumlah = $effectiveWasCompleted ? $originalJumlahHasil : 0;

        try {
            DB::transaction(function() use (
                $request,
                $produksi,
                $originalStatus,
                $originalJumlahHasil,
                $originalProductId,
                $originalTransferStatus,
                $transferNow,
                &$totalCost,
                &$effectiveWasCompleted,
                &$effectiveOriginalJumlah
            ) {
                // Update produksi basic info
                $updateData = [
                    'status' => $request->status,
                    'jumlah_hasil' => $request->jumlah_hasil ?? 0,
                    'catatan_produksi' => $request->input('catatan_produksi'),
                ];

                // Only update these fields if provided
                if ($request->has('produk_id')) {
                    $updateData['produk_id'] = $request->produk_id;
                }
                if ($request->has('tanggal_produksi')) {
                    $updateData['tanggal_produksi'] = $request->tanggal_produksi;
                }
                if ($request->has('jumlah_target')) {
                    $updateData['jumlah_target'] = $request->jumlah_target;
                }

                // Only update grade_kualitas if grades are configured and grade_kualitas is provided
                $grades = \App\Models\Pengaturan::getProductGrades();
                if (!empty($grades) && $request->has('grade_kualitas')) {
                    $updateData['grade_kualitas'] = $request->grade_kualitas;
                }

                if ($request->status === 'selesai') {
                    if ($transferNow) {
                        $updateData['status_transfer'] = 'transferred';
                        $updateData['tanggal_transfer'] = $originalTransferStatus === 'transferred'
                            ? $produksi->tanggal_transfer
                            : now();
                    } else {
                        $updateData['status_transfer'] = 'held';
                        $updateData['tanggal_transfer'] = null;
                    }
                } else {
                    $updateData['status_transfer'] = 'pending';
                    $updateData['tanggal_transfer'] = null;
                }

                $produksi->update($updateData);

                $stokEntry = StokProduk::withTrashed()
                    ->where('batch_produksi_id', $produksi->batch_produksi_id)
                    ->first();

                $productChanged = $request->has('produk_id') && (int) $request->produk_id !== $originalProductId;

                if ($productChanged && $stokEntry) {
                    if ((float) $stokEntry->jumlah_keluar > 0) {
                        throw new \Exception('Tidak dapat mengganti produk karena stok operasional sudah digunakan.');
                    }

                    if ($effectiveWasCompleted) {
                        $originalProduct = Produk::withTrashed()->find($originalProductId);
                        if ($originalProduct && (float) $stokEntry->sisa_stok > 0) {
                            $originalProduct->decrement('stok', (float) $stokEntry->sisa_stok);
                        }
                    }

                    if ($request->status === 'selesai') {
                        $stokEntry->produk_id = $produksi->produk_id;
                        $stokEntry->save();
                        $effectiveWasCompleted = false;
                    }

                    $effectiveOriginalJumlah = 0;
                } elseif ($productChanged) {
                    // Produk berubah tetapi belum ada stok operasional. Jika langsung selesai, perlakukan sebagai produksi baru.
                    if ($request->status === 'selesai') {
                        $effectiveWasCompleted = false;
                    }
                    $effectiveOriginalJumlah = 0;
                }

                // Handle bahan baku changes only if bahan_baku data is provided
                if ($request->has('bahan_baku') && !empty($request->bahan_baku)) {
                    // Get current bahan usage before update
                    $currentBahans = $produksi->produksiBahans->keyBy('bahan_baku_id');

                    // Calculate requested totals for validation
                    $requestedTotals = [];
                    foreach ($request->input('bahan_baku') as $b) {
                        $bid = intval($b['id']);
                        $j = floatval($b['jumlah']);
                        if (!isset($requestedTotals[$bid])) $requestedTotals[$bid] = 0;
                        $requestedTotals[$bid] += $j;
                    }

                    // Check stock availability considering current usage
                    $ids = array_keys($requestedTotals);
                    $bahanRows = BahanBaku::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

                    $shortages = [];
                    $EPS = 0.01;
                    foreach ($requestedTotals as $bid => $totalReq) {
                        $currentUsage = isset($currentBahans[$bid]) ? floatval($currentBahans[$bid]->jumlah_digunakan) : 0;
                        $netReq = $totalReq - $currentUsage; // Net requirement (positive = need more, negative = return some)

                        $avail = isset($bahanRows[$bid]) ? floatval($bahanRows[$bid]->stok) : 0;
                        if ($netReq > $avail + $EPS) {
                            $shortages[] = ['id' => $bid, 'requested' => $totalReq, 'available' => $avail + $currentUsage];
                        }
                    }

                    if (!empty($shortages)) {
                        $lines = [];
                        foreach ($shortages as $s) {
                            $bahan = $bahanRows[$s['id']] ?? BahanBaku::find($s['id']);
                            $name = $bahan ? $bahan->nama_bahan : ('ID ' . $s['id']);
                            $lines[] = $name . ': Diminta ' . number_format($s['requested'], 4) . ', Tersedia ' . number_format($s['available'], 4);
                        }
                        $msg = "Stok tidak mencukupi untuk beberapa bahan:\n" . implode("\n", $lines) . "\n\nSilakan sesuaikan jumlah atau perbarui stok.";
                        throw new \Exception($msg);
                    }

                    // Handle bahan baku changes
                    $totalCost = 0;
                    $newBahanIds = [];

                    // Process new/updated bahan
                    foreach ($request->bahan_baku as $bahan) {
                        $bId = intval($bahan['id']);
                        $newJumlah = floatval($bahan['jumlah']);
                        $newBahanIds[] = $bId;

                        $bRow = $bahanRows[$bId] ?? BahanBaku::find($bId);
                        $hargaSatuan = isset($bRow->harga_per_satuan) ? $bRow->harga_per_satuan : 0;
                        $biayaBahan = $hargaSatuan * $newJumlah;
                        $totalCost += $biayaBahan;

                        if (isset($currentBahans[$bId])) {
                            // Update existing bahan
                            $currentBahan = $currentBahans[$bId];
                            $oldJumlah = floatval($currentBahan->jumlah_digunakan);
                            $diff = $newJumlah - $oldJumlah;

                            $currentBahan->update([
                                'jumlah_digunakan' => $newJumlah,
                                'harga_satuan' => $hargaSatuan,
                                'total_biaya' => $biayaBahan,
                            ]);

                            // Adjust stock based on difference
                            if ($diff != 0) {
                                if ($bRow) {
                                    $bRow->stok = floatval($bRow->stok) - $diff;
                                    $bRow->save();
                                } else {
                                    BahanBaku::where('id', $bId)->decrement('stok', $diff);
                                }
                            }
                        } else {
                            // Add new bahan
                            ProduksiBahan::create([
                                'produksi_id' => $produksi->id,
                                'bahan_baku_id' => $bId,
                                'stok_bahan_baku_id' => null,
                                'jumlah_digunakan' => $newJumlah,
                                'harga_satuan' => $hargaSatuan,
                                'total_biaya' => $biayaBahan,
                            ]);

                            // Decrement stock for new bahan
                            if ($bRow) {
                                $bRow->stok = floatval($bRow->stok) - $newJumlah;
                                $bRow->save();
                            } else {
                                BahanBaku::where('id', $bId)->decrement('stok', $newJumlah);
                            }
                        }
                    }

                    // Remove bahan that are no longer used
                    foreach ($currentBahans as $bId => $currentBahan) {
                        if (!in_array($bId, $newBahanIds)) {
                            $returnAmount = floatval($currentBahan->jumlah_digunakan);

                            // Return stock
                            $bRow = $bahanRows[$bId] ?? BahanBaku::find($bId);
                            if ($bRow) {
                                $bRow->stok = floatval($bRow->stok) + $returnAmount;
                                $bRow->save();
                            } else {
                                BahanBaku::where('id', $bId)->increment('stok', $returnAmount);
                            }

                            // Delete the produksi_bahan record
                            $currentBahan->delete();
                        }
                    }

                    // Update total cost
                    $produksi->update(['biaya_produksi' => $totalCost]);
                }

                $isCompleting = $request->status === 'selesai';
                $hasilBaru = (float) ($request->jumlah_hasil ?? 0);

                if ($isCompleting && $transferNow) {
                    $deltaStok = $effectiveWasCompleted ? ($hasilBaru - $effectiveOriginalJumlah) : $hasilBaru;
                    if (abs($deltaStok) > 0.0001) {
                        if ($deltaStok > 0) {
                            $produksi->produk->increment('stok', $deltaStok);
                        } elseif ($deltaStok < 0) {
                            $produksi->produk->decrement('stok', abs($deltaStok));
                        }
                    }

                    $hppPerUnit = ($hasilBaru > 0 && $totalCost > 0) ? round($totalCost / $hasilBaru, 2) : 0;
                    $hargaSatuan = $hppPerUnit > 0 ? $hppPerUnit : ($produksi->produk->harga_jual ?? 0);

                    if ($stokEntry) {
                        if ($stokEntry->trashed()) {
                            $stokEntry->restore();
                        }

                        $stokEntry->produk_id = $produksi->produk_id;

                        $jumlahKeluar = $effectiveWasCompleted ? (float) $stokEntry->jumlah_keluar : 0;
                        if (!$effectiveWasCompleted) {
                            $stokEntry->jumlah_keluar = 0;
                            $jumlahKeluar = 0;
                        }

                        $stokEntry->jumlah_masuk = $hasilBaru;
                        $stokEntry->sisa_stok = max($hasilBaru - $jumlahKeluar, 0);
                        $stokEntry->harga_satuan = $hargaSatuan;
                        $stokEntry->grade_kualitas = $request->grade_kualitas;
                        $stokEntry->tanggal = $request->tanggal_produksi ?? now()->toDateString();
                        $stokEntry->keterangan = 'Hasil produksi ' . $produksi->nomor_produksi;
                        $stokEntry->save();
                    } else {
                        $stokEntry = StokProduk::create([
                            'produk_id' => $produksi->produk_id,
                            'batch_produksi_id' => $produksi->batch_produksi_id,
                            'jumlah_masuk' => $hasilBaru,
                            'jumlah_keluar' => 0,
                            'sisa_stok' => $hasilBaru,
                            'harga_satuan' => $hargaSatuan,
                            'grade_kualitas' => $request->grade_kualitas,
                            'tanggal' => $request->tanggal_produksi ?? now()->toDateString(),
                            'keterangan' => 'Hasil produksi ' . $produksi->nomor_produksi
                        ]);
                    }
                } elseif ($isCompleting && !$transferNow) {
                    if ($originalTransferStatus === 'transferred' && $stokEntry) {
                        if ((float) $stokEntry->jumlah_keluar > 0) {
                            throw new \Exception('Tidak dapat menahan hasil produksi karena stok operasional sudah digunakan.');
                        }

                        if ((float) $stokEntry->sisa_stok > 0) {
                            $produksi->produk->decrement('stok', (float) $stokEntry->sisa_stok);
                        }

                        $stokEntry->delete();
                        $stokEntry = null;
                        $effectiveWasCompleted = false;
                        $effectiveOriginalJumlah = 0;
                    } elseif ($originalTransferStatus === 'transferred' && $effectiveOriginalJumlah > 0) {
                        $produksi->produk->decrement('stok', $effectiveOriginalJumlah);
                        $effectiveWasCompleted = false;
                        $effectiveOriginalJumlah = 0;
                    }
                } elseif ($effectiveWasCompleted) {
                    if ($stokEntry) {
                        if ((float) $stokEntry->jumlah_keluar > 0) {
                            throw new \Exception('Tidak dapat mengubah status produksi karena stok operasional sudah digunakan.');
                        }

                        if ((float) $stokEntry->sisa_stok > 0) {
                            $targetProductId = $productChanged ? $originalProductId : (int) $produksi->produk_id;
                            $targetProduct = Produk::withTrashed()->find($targetProductId);
                            if ($targetProduct) {
                                $targetProduct->decrement('stok', $stokEntry->sisa_stok);
                            }
                        }

                        $stokEntry->delete();
                    }
                }
            });

            return redirect()->route('backoffice.produksi.index')->with('success', 'Data produksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produksi $produksi)
    {
        // Determine related operational stock for this production
        $relatedStok = StokProduk::where('batch_produksi_id', $produksi->batch_produksi_id)
            ->where('produk_id', $produksi->produk_id)
            ->sum('sisa_stok');

        // Super admin can always delete
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            // allow
        } elseif (Auth::check() && Auth::user()->role === 'admin') {
            // Admin may delete only when operational stock is empty
            if ((float) $relatedStok > 0) {
                return back()->with('error', 'Produksi tidak dapat dihapus karena masih terdapat stok operasional terkait produksi ini (' . $relatedStok . '). Silakan kosongkan stok operasional terlebih dahulu.');
            }
        } else {
            // Other roles are not permitted
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus produksi.');
        }

        // If safe to delete, perform deletion within transaction
        DB::transaction(function() use ($produksi) {
            // If production was completed, remove any zeroed operational stock records
            if ($produksi->status === 'selesai' && $produksi->jumlah_hasil > 0) {
                StokProduk::where('batch_produksi_id', $produksi->batch_produksi_id)
                    ->where('produk_id', $produksi->produk_id)
                    ->delete();
            }

            // Restore bahan baku stock if production is cancelled
            if ($produksi->status !== 'selesai') {
                foreach ($produksi->produksiBahans as $produksiBahan) {
                    $produksiBahan->bahanBaku->increment('stok', $produksiBahan->jumlah_digunakan);
                }
            }

            $produksi->delete();
            // If deleter is super_admin, delete associated product as well
            if (Auth::check() && Auth::user()->role === 'super_admin') {
                if ($produksi->produk) {
                    $produksi->produk->delete();
                }
            }
        });

        return redirect()->route('backoffice.produksi.index')->with('success', 'Data produksi berhasil dihapus.');
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

        $rows = BahanBaku::whereIn('id', $ids)->get(['id', 'stok']);
        $map = [];
        foreach ($rows as $r) {
            $map[$r->id] = $r->stok;
        }

        return response()->json(['stok' => $map]);
    }

    /**
     * Mark production as complete and create stock record for produced items.
     */
    public function completeProduction(Request $request, Produksi $produksi)
    {
        $request->validate([
            'jumlah_hasil' => 'required|numeric|min:0',
            'transfer_ke_produk' => 'required|boolean',
            'grade_kualitas' => [
                'required',
                function ($attribute, $value, $fail) {
                    $grades = \App\Models\Pengaturan::getProductGrades();
                    $maxGradeIndex = count($grades) > 0 ? count($grades) - 1 : 2; // Default to C (index 2) if no grades
                    $validGrades = [];
                    for ($i = 0; $i <= $maxGradeIndex; $i++) {
                        $validGrades[] = chr(65 + $i); // A, B, C, D, etc.
                    }

                    if (!in_array($value, $validGrades)) {
                        $fail('Grade kualitas yang dipilih tidak valid.');
                    }
                },
            ],
        ]);

        $transferNow = $request->boolean('transfer_ke_produk');
        $originalTransferStatus = $produksi->status_transfer ?? 'pending';
        $originalJumlahHasil = (float) ($produksi->jumlah_hasil ?? 0);
        $totalCost = (float) ($produksi->biaya_produksi ?? 0);
        $effectiveWasTransferred = $produksi->status === 'selesai' && $originalTransferStatus === 'transferred';
        $effectiveOriginalJumlah = $effectiveWasTransferred ? $originalJumlahHasil : 0;

        DB::beginTransaction();
        try {
            $produksi->update([
                'jumlah_hasil' => $request->jumlah_hasil,
                'grade_kualitas' => $request->grade_kualitas,
                'status' => 'selesai',
                'status_transfer' => $transferNow ? 'transferred' : 'held',
                'tanggal_transfer' => $transferNow ? now() : null,
            ]);

            $hasilBaru = (float) $request->jumlah_hasil;

            $stokEntry = StokProduk::withTrashed()
                ->where('batch_produksi_id', $produksi->batch_produksi_id)
                ->first();

            if ($transferNow) {
                $deltaStok = $effectiveWasTransferred ? ($hasilBaru - $effectiveOriginalJumlah) : $hasilBaru;
                if (abs($deltaStok) > 0.0001) {
                    if ($deltaStok > 0) {
                        $produksi->produk->increment('stok', $deltaStok);
                    } elseif ($deltaStok < 0) {
                        $produksi->produk->decrement('stok', abs($deltaStok));
                    }
                }

                $hppPerUnit = ($hasilBaru > 0 && $totalCost > 0) ? round($totalCost / $hasilBaru, 2) : 0;
                $hargaSatuan = $hppPerUnit > 0 ? $hppPerUnit : ($produksi->produk->harga_jual ?? 0);

                if ($stokEntry) {
                    if ($stokEntry->trashed()) {
                        $stokEntry->restore();
                    }

                    $jumlahKeluar = $effectiveWasTransferred ? (float) $stokEntry->jumlah_keluar : 0;
                    if (!$effectiveWasTransferred) {
                        $stokEntry->jumlah_keluar = 0;
                        $jumlahKeluar = 0;
                    }

                    $stokEntry->produk_id = $produksi->produk_id;
                    $stokEntry->jumlah_masuk = $hasilBaru;
                    $stokEntry->sisa_stok = max($hasilBaru - $jumlahKeluar, 0);
                    $stokEntry->harga_satuan = $hargaSatuan;
                    $stokEntry->grade_kualitas = $request->grade_kualitas;
                    $stokEntry->tanggal = $produksi->tanggal_produksi;
                    $stokEntry->keterangan = 'Hasil produksi ' . $produksi->nomor_produksi;
                    $stokEntry->save();
                } else {
                    StokProduk::create([
                        'produk_id' => $produksi->produk_id,
                        'batch_produksi_id' => $produksi->batch_produksi_id,
                        'jumlah_masuk' => $request->jumlah_masuk ?? $hasilBaru,
                        'jumlah_keluar' => 0,
                        'sisa_stok' => $hasilBaru,
                        'harga_satuan' => $hargaSatuan,
                        'grade_kualitas' => $request->grade_kualitas,
                        'tanggal' => $produksi->tanggal_produksi,
                        'keterangan' => 'Hasil produksi ' . $produksi->nomor_produksi,
                    ]);
                }
            } else {
                if ($effectiveWasTransferred && $stokEntry) {
                    if ((float) $stokEntry->jumlah_keluar > 0) {
                        throw new \Exception('Tidak dapat menahan hasil produksi karena stok operasional sudah digunakan.');
                    }

                    if ((float) $stokEntry->sisa_stok > 0) {
                        $produksi->produk->decrement('stok', (float) $stokEntry->sisa_stok);
                    }

                    $stokEntry->delete();
                } elseif ($effectiveWasTransferred && $effectiveOriginalJumlah > 0) {
                    $produksi->produk->decrement('stok', $effectiveOriginalJumlah);
                }
            }

            DB::commit();

            $message = $transferNow
                ? 'Produksi berhasil diselesaikan dan stok dipindahkan ke produk.'
                : 'Produksi selesai. Hasil produksi ditahan sampai dipindahkan ke stok produk.';

            return redirect()->route('backoffice.produksi.show', $produksi)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('completeProduction error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function transferToProduk(Request $request, Produksi $produksi)
    {
        if ($produksi->status !== 'selesai') {
            return back()->with('error', 'Produksi belum selesai sehingga hasil tidak dapat dipindahkan ke stok produk.');
        }

        if ($produksi->status_transfer === 'transferred') {
            return back()->with('info', 'Hasil produksi sudah dipindahkan ke stok produk.');
        }

        $hasilBaru = (float) ($produksi->jumlah_hasil ?? 0);
        if ($hasilBaru <= 0) {
            return back()->with('error', 'Jumlah hasil produksi kosong sehingga tidak ada stok yang dapat dipindahkan.');
        }

        $stokEntry = StokProduk::withTrashed()
            ->where('batch_produksi_id', $produksi->batch_produksi_id)
            ->first();

        if ($stokEntry && !$stokEntry->trashed() && (float) $stokEntry->jumlah_keluar > 0) {
            return back()->with('error', 'Sebagian hasil produksi sudah digunakan pada stok operasional, sehingga tidak dapat ditransfer ulang.');
        }

        $totalCost = (float) ($produksi->biaya_produksi ?? 0);

        DB::beginTransaction();
        try {
            $hppPerUnit = ($hasilBaru > 0 && $totalCost > 0) ? round($totalCost / $hasilBaru, 2) : 0;
            $hargaSatuan = $hppPerUnit > 0 ? $hppPerUnit : ($produksi->produk->harga_jual ?? 0);

            if ($stokEntry) {
                if ($stokEntry->trashed()) {
                    $stokEntry->restore();
                }

                $stokEntry->produk_id = $produksi->produk_id;
                $stokEntry->jumlah_masuk = $hasilBaru;
                $stokEntry->jumlah_keluar = 0;
                $stokEntry->sisa_stok = $hasilBaru;
                $stokEntry->harga_satuan = $hargaSatuan;
                $stokEntry->grade_kualitas = $produksi->grade_kualitas;
                $stokEntry->tanggal = $produksi->tanggal_produksi ?? now()->toDateString();
                $stokEntry->keterangan = 'Hasil produksi ' . $produksi->nomor_produksi;
                $stokEntry->save();
            } else {
                StokProduk::create([
                    'produk_id' => $produksi->produk_id,
                    'batch_produksi_id' => $produksi->batch_produksi_id,
                    'jumlah_masuk' => $hasilBaru,
                    'jumlah_keluar' => 0,
                    'sisa_stok' => $hasilBaru,
                    'harga_satuan' => $hargaSatuan,
                    'grade_kualitas' => $produksi->grade_kualitas,
                    'tanggal' => $produksi->tanggal_produksi ?? now()->toDateString(),
                    'keterangan' => 'Hasil produksi ' . $produksi->nomor_produksi,
                ]);
            }

            $produksi->produk->increment('stok', $hasilBaru);

            $produksi->update([
                'status_transfer' => 'transferred',
                'tanggal_transfer' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Hasil produksi berhasil dipindahkan ke stok produk.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('transferToProduk error: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat mentransfer hasil produksi: ' . $e->getMessage());
        }
    }

}
