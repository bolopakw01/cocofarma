<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokProduk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StokProdukController extends Controller
{
    // Show edit form (only for super_admin)
    public function edit(StokProduk $stok)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        return view('admin.pages.produk.edit-produk', compact('stok'));
    }

    // Update stok
    public function update(Request $request, StokProduk $stok)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'sisa_stok' => 'required|numeric|min:0',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $stok->update([
                'sisa_stok' => $validated['sisa_stok'],
                'harga_satuan' => $validated['harga_satuan'],
                'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            return redirect()->route('backoffice.produk.index')->with('success', 'Stok produk berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('StokProduk update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan perubahan.');
        }
    }

    // Destroy stok
    public function destroy(StokProduk $stok)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        try {
            $stok->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            Log::error('StokProduk delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus stok'], 500);
        }
    }
}
