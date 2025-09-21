@extends('admin.layouts.app')

@php
    $pageTitle = 'Tambah Produksi';
@endphp

@section('title', 'Tambah Produksi - Cocofarma')

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
        font-family: inherit;
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

    .bahan-baku-section {
        margin-top: 20px;
    }

    .bahan-baku-item {
        display: grid;
        grid-template-columns: 3fr 1fr 80px;
        gap: 12px;
        align-items: end;
        margin-bottom: 12px;
        padding: 16px;
        background: white;
        border-radius: var(--border-radius);
        border: 1px solid var(--light-gray);
    }

    .bahan-baku-item select,
    .bahan-baku-item input {
        padding: 10px 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }

    .bahan-baku-item select:focus,
    .bahan-baku-item input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.15);
    }

    .remove-bahan-btn {
        background: var(--danger);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 10px 12px;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.8rem;
    }

    .remove-bahan-btn:hover {
        background: #c22c38;
        transform: translateY(-1px);
    }

    .add-bahan-btn {
        background: var(--success);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 12px 20px;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
    }

    .add-bahan-btn:hover {
        background: #3aafd9;
        transform: translateY(-1px);
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

    .form-info {
        background: rgba(67, 97, 238, 0.1);
        border: 1px solid rgba(67, 97, 238, 0.2);
        border-radius: var(--border-radius);
        padding: 16px;
        margin-bottom: 20px;
    }

    .form-info p {
        margin: 0;
        color: var(--primary);
        font-size: 0.9rem;
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

    @media (max-width: 768px) {
        .container {
            padding: 16px;
        }

        .form-section {
            padding: 20px;
        }

        .bahan-baku-item {
            grid-template-columns: 1fr;
            gap: 8px;
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
        <h1><i class="fas fa-plus"></i> Tambah Produksi Baru</h1>
        <a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-info">
        <p><i class="fas fa-info-circle"></i> Lengkapi informasi produksi di bawah ini. Pastikan semua bahan baku yang diperlukan tersedia dalam stok.</p>
    </div>

    <form action="{{ route('backoffice.produksi.store') }}" method="POST" id="produksiForm">
        @csrf

        <!-- Informasi Produksi -->
        <div class="form-section">
            <h3><i class="fas fa-cogs"></i> Informasi Produksi</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="batch_produksi_id">Batch Produksi <span class="required">*</span></label>
                    <select name="batch_produksi_id" id="batch_produksi_id" required>
                        <option value="">Pilih Batch Produksi</option>
                        @foreach($batchProduksis as $batch)
                        <option value="{{ $batch->id }}" {{ old('batch_produksi_id') == $batch->id ? 'selected' : '' }}>
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
                    <select name="produk_id" id="produk_id" required>
                        <option value="">Pilih Produk</option>
                        @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
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
                           value="{{ old('tanggal_produksi', date('Y-m-d')) }}" required>
                    @error('tanggal_produksi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_target">Target Produksi <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" name="jumlah_target" id="jumlah_target"
                               value="{{ old('jumlah_target') }}" placeholder="0" min="0.01" step="0.01" required>
                        <span class="input-group-text">Unit</span>
                    </div>
                    @error('jumlah_target')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Bahan Baku -->
        <div class="form-section">
            <h3><i class="fas fa-boxes"></i> Bahan Baku Yang Digunakan</h3>

            <div id="bahan-baku-container">
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
            </div>

            <button type="button" class="add-bahan-btn" onclick="addBahanItem()">
                <i class="fas fa-plus"></i> Tambah Bahan Baku
            </button>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Produksi
            </button>
        </div>
    </form>
</div>

<script>
let bahanIndex = 1;

function addBahanItem() {
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
        <input type="number" name="bahan_digunakan[${bahanIndex}][jumlah]" class="bahan-jumlah" placeholder="Jumlah" min="0.01" step="0.01" required>
        <button type="button" class="remove-bahan-btn" onclick="removeBahanItem(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;

    container.appendChild(newItem);
    bahanIndex++;

    // Show remove button on first item if there are multiple items
    updateRemoveButtons();
}

function removeBahanItem(button) {
    const item = button.closest('.bahan-baku-item');
    item.remove();
    updateRemoveButtons();
    calculateEstimasiBiaya();
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.bahan-baku-item');
    const removeButtons = document.querySelectorAll('.remove-bahan-btn');

    if (items.length > 1) {
        removeButtons.forEach(btn => btn.style.display = 'block');
    } else {
        removeButtons.forEach(btn => btn.style.display = 'none');
    }
}

function calculateEstimasiBiaya() {
    let totalBiaya = 0;

    document.querySelectorAll('.bahan-baku-item').forEach(item => {
        const select = item.querySelector('.bahan-select');
        const jumlahInput = item.querySelector('.bahan-jumlah');

        if (select.value && jumlahInput.value) {
            const harga = parseFloat(select.selectedOptions[0].getAttribute('data-harga')) || 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;
            totalBiaya += harga * jumlah;
        }
    });

    document.getElementById('estimasi_biaya').value = totalBiaya.toLocaleString('id-ID');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Calculate biaya when bahan or jumlah changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('bahan-select') || e.target.classList.contains('bahan-jumlah')) {
            calculateEstimasiBiaya();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('bahan-jumlah')) {
            calculateEstimasiBiaya();
        }
    });

    // Form validation
    document.getElementById('produksiForm').addEventListener('submit', function(e) {
        const bahanItems = document.querySelectorAll('.bahan-baku-item');
        let hasValidBahan = false;

        bahanItems.forEach(item => {
            const select = item.querySelector('.bahan-select');
            const jumlah = item.querySelector('.bahan-jumlah');

            if (select.value && jumlah.value && parseFloat(jumlah.value) > 0) {
                hasValidBahan = true;
            }
        });

        if (!hasValidBahan) {
            e.preventDefault();
            alert('Minimal satu bahan baku harus dipilih dengan jumlah yang valid.');
            return false;
        }
    });
});
</script>
@endsection