@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Produksi';
@endphp

@section('title', 'Edit Produksi - Cocofarma')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --primary-hover: #3a4fd8;
        --success: #4cc9f0;
        --info: #4895ef;
        --warning: #f72585;
        --danger: #e63946;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    html, body {
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
        overflow: hidden;
        margin-top: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.6rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-section {
        background: var(--light);
        border-radius: var(--border-radius);
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .form-section h3 {
        color: var(--dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .info-section {
        background: rgba(73, 143, 239, 0.1);
        border: 1px solid rgba(73, 143, 239, 0.2);
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 0.8rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 1rem;
        color: var(--dark);
        font-weight: 600;
    }

    .bahan-baku-list {
        margin-top: 20px;
    }

    .bahan-baku-list h4 {
        color: var(--dark);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .bahan-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid var(--light-gray);
        margin-bottom: 8px;
    }

    .bahan-baku-item {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 12px;
        padding: 16px;
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid var(--light-gray);
    }

    .bahan-baku-item .bahan-select {
        flex: 2;
        padding: 8px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }

    .bahan-baku-item .bahan-jumlah {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }

    .bahan-baku-item .remove-bahan-btn {
        padding: 8px 12px;
        background: var(--danger);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .bahan-baku-item .remove-bahan-btn:hover {
        background: #c82333;
    }

    .add-bahan-btn {
        padding: 12px 20px;
        background: var(--success);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background-color 0.2s;
        margin-top: 16px;
    }

    .add-bahan-btn:hover {
        background: #218838;
    }

    .add-bahan-btn i {
        margin-right: 8px;
    }

    .bahan-nama {
        font-weight: 500;
        color: var(--dark);
    }

    .bahan-detail {
        font-size: 0.8rem;
        color: var(--gray);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--light-gray);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .required {
        color: var(--danger);
    }

    .error-message {
        color: var(--danger);
        font-size: 0.8rem;
        margin-top: 4px;
        display: block;
    }

    .input-group {
        position: relative;
    }

    .input-group-text {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 0.8rem;
        pointer-events: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-rencana {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }

    .status-proses {
        background: rgba(73, 143, 239, 0.1);
        color: var(--info);
    }

    .status-selesai {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }

    .status-gagal {
        background: rgba(230, 57, 70, 0.1);
        color: var(--danger);
    }

    @media (max-width: 768px) {
        .container {
            padding: 16px;
        }

        .form-section {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Produksi</h1>
        <a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Produksi -->
    <div class="info-section">
        <h3><i class="fas fa-info-circle"></i> Informasi Produksi</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nomor Produksi</span>
                <span class="info-value">{{ $produksi->nomor_produksi }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Batch Produksi</span>
                <span class="info-value">{{ $produksi->batchProduksi->nomor_batch ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Produk</span>
                <span class="info-value">{{ $produksi->produk->nama_produk ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Produksi</span>
                <span class="info-value">{{ $produksi->tanggal_produksi->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Target Produksi</span>
                <span class="info-value">{{ number_format($produksi->jumlah_target, 2) }} Unit</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status Saat Ini</span>
                <span class="status-badge status-{{ $produksi->status }}">
                    <i class="fas fa-circle"></i>
                    {{ $produksi->status_label }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Biaya Produksi</span>
                <span class="info-value">Rp {{ number_format($produksi->biaya_produksi, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($produksi->catatan)
        <div class="info-item" style="margin-top: 16px;">
            <span class="info-label">Catatan</span>
            <span class="info-value">{{ $produksi->catatan }}</span>
        </div>
        @endif
    </div>

    <!-- Bahan Baku Yang Digunakan -->
    @if($produksi->produksiBahans->count() > 0)
    <div class="form-section">
        <h3><i class="fas fa-boxes"></i> Bahan Baku Yang Digunakan</h3>
        <div class="bahan-baku-list">
            @foreach($produksi->produksiBahans as $bahan)
            <div class="bahan-item">
                <div class="bahan-info">
                    <div class="bahan-nama">{{ $bahan->bahanBaku->nama_bahan ?? 'Bahan tidak ditemukan' }}</div>
                    <div class="bahan-detail">
                        Jumlah: {{ number_format($bahan->jumlah_digunakan, 2) }} {{ $bahan->bahanBaku->satuan ?? '' }} |
                        Biaya: Rp {{ number_format($bahan->biaya_bahan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Form Edit -->
    <form action="{{ route('produksi.update', $produksi->id) }}" method="POST" id="editProduksiForm">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h3><i class="fas fa-edit"></i> Update Informasi Produksi</h3>

            @if($produksi->status !== 'rencana')
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Perhatian:</strong> Produksi ini sudah diproses dan tidak dapat diedit.
                Anda hanya dapat mengubah catatan.
            </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="batch_produksi_id">Batch Produksi <span class="required">*</span></label>
                    <select name="batch_produksi_id" id="batch_produksi_id" {{ $produksi->status !== 'rencana' ? 'disabled' : 'required' }}>
                        <option value="">Pilih Batch Produksi</option>
                        @foreach($batchProduksis as $batch)
                        <option value="{{ $batch->id }}" {{ $produksi->batch_produksi_id == $batch->id ? 'selected' : '' }}>
                            {{ $batch->nomor_batch }} - {{ $batch->tungku->nama_tungku ?? 'Tungku tidak ditemukan' }}
                        </option>
                        @endforeach
                    </select>
                    @error('batch_produksi_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="produk_id">Produk <span class="required">*</span></label>
                    <select name="produk_id" id="produk_id" {{ $produksi->status !== 'rencana' ? 'disabled' : 'required' }}>
                        <option value="">Pilih Produk</option>
                        @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ $produksi->produk_id == $produk->id ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} ({{ $produk->kode_produk }})
                        </option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_produksi">Tanggal Produksi <span class="required">*</span></label>
                    <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                           value="{{ old('tanggal_produksi', $produksi->tanggal_produksi->format('Y-m-d')) }}"
                           {{ $produksi->status !== 'rencana' ? 'disabled' : 'required' }}>
                    @error('tanggal_produksi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_target">Target Produksi <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" name="jumlah_target" id="jumlah_target"
                               value="{{ old('jumlah_target', $produksi->jumlah_target) }}"
                               placeholder="0" min="0.01" step="0.01"
                               {{ $produksi->status !== 'rencana' ? 'disabled' : 'required' }}>
                        <span class="input-group-text">Unit</span>
                    </div>
                    @error('jumlah_target')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" placeholder="Update catatan produksi">{{ old('catatan', $produksi->catatan) }}</textarea>
                @error('catatan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Bahan Baku (hanya bisa edit jika status rencana) -->
        @if($produksi->status === 'rencana')
        <div class="form-section">
            <h3><i class="fas fa-boxes"></i> Bahan Baku Yang Digunakan</h3>

            <div id="bahan-baku-container">
                @forelse($produksi->produksiBahans as $index => $bahan)
                <div class="bahan-baku-item" data-index="{{ $index }}">
                    <select name="bahan_digunakan[{{ $index }}][bahan_baku_id]" class="bahan-select" required>
                        <option value="">Pilih Bahan Baku</option>
                        @foreach($bahanBakus as $bahanOption)
                        <option value="{{ $bahanOption->id }}"
                                data-harga="{{ $bahanOption->harga_per_satuan }}"
                                data-satuan="{{ $bahanOption->satuan }}"
                                {{ $bahan->bahan_baku_id == $bahanOption->id ? 'selected' : '' }}>
                            {{ $bahanOption->nama_bahan }} (Stok: {{ number_format($bahanOption->stok, 2) }} {{ $bahanOption->satuan }})
                        </option>
                        @endforeach
                    </select>
                    <input type="number" name="bahan_digunakan[{{ $index }}][jumlah]" class="bahan-jumlah"
                           placeholder="Jumlah" min="0.01" step="0.01" value="{{ $bahan->jumlah_digunakan }}" required>
                    <button type="button" class="remove-bahan-btn" onclick="removeBahanItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @empty
                <div class="bahan-baku-item" data-index="0">
                    <select name="bahan_digunakan[0][bahan_baku_id]" class="bahan-select" required>
                        <option value="">Pilih Bahan Baku</option>
                        @foreach($bahanBakus as $bahan)
                        <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}">
                            {{ $bahan->nama_bahan }} (Stok: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                        </option>
                        @endforeach
                    </select>
                    <input type="number" name="bahan_digunakan[0][jumlah]" class="bahan-jumlah" placeholder="Jumlah" min="0.01" step="0.01" required>
                    <button type="button" class="remove-bahan-btn" onclick="removeBahanItem(this)" style="display: none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @endforelse
            </div>

            <button type="button" class="add-bahan-btn" onclick="addBahanItem()">
                <i class="fas fa-plus"></i> Tambah Bahan Baku
            </button>
        </div>
        @endif

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('produksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Produksi
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let bahanIndex = {{ count($produksi->produksiBahans) ?: 1 }};

    // Initialize remove buttons
    updateRemoveButtons();

    // Function to add new bahan baku item
    window.addBahanItem = function() {
        const container = document.getElementById('bahan-baku-container');
        const newItem = document.createElement('div');
        newItem.className = 'bahan-baku-item';
        newItem.setAttribute('data-index', bahanIndex);

        newItem.innerHTML = `
            <select name="bahan_digunakan[${bahanIndex}][bahan_baku_id]" class="bahan-select" required>
                <option value="">Pilih Bahan Baku</option>
                @foreach($bahanBakus as $bahan)
                <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}">
                    {{ $bahan->nama_bahan }} (Stok: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                </option>
                @endforeach
            </select>
            <input type="number" name="bahan_digunakan[${bahanIndex}][jumlah]" class="bahan-jumlah"
                   placeholder="Jumlah" min="0.01" step="0.01" required>
            <button type="button" class="remove-bahan-btn" onclick="removeBahanItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;

        container.appendChild(newItem);
        bahanIndex++;
        updateRemoveButtons();
    };

    // Function to remove bahan baku item
    window.removeBahanItem = function(button) {
        const item = button.closest('.bahan-baku-item');
        const container = document.getElementById('bahan-baku-container');

        if (container.children.length > 1) {
            item.remove();
            updateRemoveButtons();
        }
    };

    // Function to update remove button visibility
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.bahan-baku-item');
        const removeButtons = document.querySelectorAll('.remove-bahan-btn');

        if (items.length === 1) {
            removeButtons.forEach(btn => btn.style.display = 'none');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'block');
        }
    }

    // Form validation
    document.getElementById('editProduksiForm').addEventListener('submit', function(e) {
        const status = document.getElementById('status')?.value;
        const jumlahHasilInput = document.getElementById('jumlah_hasil');

        if (status === 'selesai' && jumlahHasilInput && (!jumlahHasilInput.value || parseFloat(jumlahHasilInput.value) <= 0)) {
            e.preventDefault();
            alert('Jumlah hasil produksi harus diisi ketika status diset ke "Selesai".');
            jumlahHasilInput.focus();
            return false;
        }
    });
});
</script>
@endsection