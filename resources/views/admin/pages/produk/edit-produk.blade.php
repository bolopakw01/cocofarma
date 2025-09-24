@extends('admin.layouts.app')

@section('title', 'Edit Stok Operasional - Cocofarma')

@section('content')
<style>
    /* Minimal form styles to match other edit pages */
    .container { max-width: 900px; margin: 30px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);} 
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .page-header h1 { font-size:1.25rem; margin:0; display:flex; gap:10px; align-items:center; }
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-section { background:#f8f9fa; padding:20px; border-radius:8px; border:1px solid #e9ecef; }
    .form-row { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:18px; }
    .text-danger{ color:#e63946; font-size:0.875rem; }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-box"></i> Edit Stok Operasional</h1>
        <a href="{{ route('backoffice.produk.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="form-container">
        <form action="{{ route('backoffice.produk.stok.update', $stok) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-section">
                <div class="form-group">
                    <label>Produk</label>
                    <input type="text" class="form-control" value="{{ optional($stok->produk)->nama_produk }}" readonly>
                </div>

                @if(Auth::check() && Auth::user()->role === 'super_admin')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sisa_stok">Sisa Stok</label>
                            <input type="number" id="sisa_stok" name="sisa_stok" class="form-control" value="{{ old('sisa_stok', $stok->sisa_stok) }}" min="0" step="0.01" required>
                            @error('sisa_stok') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan</label>
                            <input type="number" id="harga_satuan" name="harga_satuan" class="form-control" value="{{ old('harga_satuan', $stok->harga_satuan) }}" min="0" step="0.01" required>
                            @error('harga_satuan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa (opsional)</label>
                            <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="form-control" value="{{ old('tanggal_kadaluarsa', optional($stok->tanggal_kadaluarsa)->format('Y-m-d')) }}">
                            @error('tanggal_kadaluarsa') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control" value="{{ old('keterangan', $stok->keterangan) }}">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        <a class="btn btn-secondary" href="{{ route('backoffice.produk.index') }}">Batal</a>
                    </div>
                @else
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sisa_stok">Sisa Stok</label>
                            <input type="number" id="sisa_stok" name="sisa_stok" class="form-control" value="{{ old('sisa_stok', $stok->sisa_stok) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan</label>
                            <input type="number" id="harga_satuan" name="harga_satuan" class="form-control" value="{{ old('harga_satuan', $stok->harga_satuan) }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" readonly>{{ old('keterangan', $stok->keterangan) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <a class="btn btn-secondary" href="{{ route('backoffice.produk.index') }}">Kembali</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection
