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
        display: flex;
    }

    .input-group select {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }

    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
        white-space: nowrap;
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

    /* SweetAlert Toast Styling */
    .swal-toast-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
        border-left: 4px solid #ffc107 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .swal-toast-warning .swal2-title {
        color: #856404 !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
    }

    .swal-toast-warning .swal2-timer-progress-bar {
        background: #ffc107 !important;
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
                    <label for="produk_id">Produk <span class="required">*</span></label>
                    <div class="input-group">
                        <select name="produk_id" id="produk_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                {{ $produk->nama_produk }} ({{ $produk->kode_produk }})
                            </option>
                            @endforeach
                        </select>
                        <a href="{{ route('backoffice.master-produk.create') }}" target="_blank" class="btn btn-outline-primary" title="Tambah Produk Baru">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
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

            <div class="form-row">
                <div class="form-group">
                    <label for="estimasi_biaya">Estimasi Biaya</label>
                    <input type="text" id="estimasi_biaya" name="estimasi_biaya" readonly placeholder="0" />
                </div>
            </div>

            <div class="form-group">
                <label for="catatan_produksi">Catatan</label>
                <textarea name="catatan_produksi" id="catatan_produksi" placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan_produksi') }}</textarea>
                @error('catatan_produksi')
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
                        <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
                            {{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
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
let produkCompositions = @json($produkCompositions ?? []);
const EPS = 0.0001;

function addBahanItem() {
    const container = document.getElementById('bahan-baku-container');
    const newItem = document.createElement('div');
    newItem.className = 'bahan-baku-item';
    newItem.setAttribute('data-index', bahanIndex);

    newItem.innerHTML = `
        <select name="bahan_digunakan[${bahanIndex}][bahan_baku_id]" class="bahan-select" required>
            <option value="">Pilih Bahan Baku</option>
            @foreach($bahanBakus as $bahan)
            <option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
                {{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
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
    // enforce limits for the newly added item
    enforceLimitForItem(newItem);
}

// Helper to add bahan item with prefilled values
function addBahanItemWithValues(bahanBakuId, jumlah, hargaSatuan) {
    const container = document.getElementById('bahan-baku-container');
    const newItem = document.createElement('div');
    newItem.className = 'bahan-baku-item';
    newItem.setAttribute('data-index', bahanIndex);

    let optionsHtml = `<option value="">Pilih Bahan Baku</option>`;
    @foreach($bahanBakus as $bahan)
        optionsHtml += `<option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">{{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }} {{ $bahan->satuan }})</option>`;
    @endforeach

    newItem.innerHTML = `
        <select name="bahan_digunakan[${bahanIndex}][bahan_baku_id]" class="bahan-select" required>
            ${optionsHtml}
        </select>
        <input type="number" name="bahan_digunakan[${bahanIndex}][jumlah]" class="bahan-jumlah" placeholder="Jumlah" min="0.01" step="0.01" required>
        <button type="button" class="remove-bahan-btn" onclick="removeBahanItem(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;

    container.appendChild(newItem);

    // set values
    if (bahanBakuId) {
        const sel = newItem.querySelector('.bahan-select');
        sel.value = bahanBakuId;
        sel.dispatchEvent(new Event('change'));
    }
    if (jumlah) {
        newItem.querySelector('.bahan-jumlah').value = jumlah;
    }

    bahanIndex++;
    updateRemoveButtons();
    // enforce caps after inserting values
    enforceLimitForItem(newItem);
}

// Populate bahan container based on selected produk and jumlah target
function populateBahanFromProduk() {
    const produkSelect = document.getElementById('produk_id');
    const jumlahTarget = parseFloat(document.getElementById('jumlah_target').value) || 0;
    const produkId = produkSelect.value;

    const container = document.getElementById('bahan-baku-container');
    container.innerHTML = '';
    bahanIndex = 0;

    if (!produkId || !produkCompositions[produkId] || produkCompositions[produkId].length === 0) {
        addBahanItem();
        return;
    }

    produkCompositions[produkId].forEach(comp => {
        // find an operational bahan that matches master_bahan_id
        let matched = null;
        @foreach($bahanBakus as $b)
            if ({{ $b->master_bahan_id }} == comp.master_bahan_id) matched = {{ $b->id }};
        @endforeach

        let reqAmount = parseFloat(comp.jumlah_per_unit) * jumlahTarget;
        // if matched operational bahan exists, cap to its data-stok
        if (matched) {
            const opt = document.querySelector('.bahan-select option[value="' + matched + '"]');
            const avail = opt ? (parseFloat(opt.getAttribute('data-stok')) || 0) : 0;
            if (reqAmount > avail) reqAmount = avail;
        }
        addBahanItemWithValues(matched, reqAmount.toFixed(4), null);
    });

    calculateEstimasiBiaya();
}

// Attach listeners to product and target inputs
document.addEventListener('DOMContentLoaded', function() {
    const produkSelect = document.getElementById('produk_id');
    const jumlahInput = document.getElementById('jumlah_target');
    if (produkSelect) produkSelect.addEventListener('change', populateBahanFromProduk);
    if (jumlahInput) jumlahInput.addEventListener('input', function() { if (produkSelect && produkSelect.value) populateBahanFromProduk(); });
});

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
            // prefer harga dari input (user editable), fallback ke data-harga dari option
            const harga = parseFloat(select.selectedOptions[0].getAttribute('data-harga')) || 0;

            const jumlah = parseFloat(jumlahInput.value) || 0;
            totalBiaya += harga * jumlah;
        }
    });

    const estimEl = document.getElementById('estimasi_biaya');
    if (estimEl) {
        try {
            estimEl.value = totalBiaya.toLocaleString('id-ID');
        } catch (err) {
            console.debug('Could not set estimasi_biaya value', err);
        }
    }
}

// --- New helper functions: enforce per-bahan caps and show inline warnings ---
function getTotalRequestedForBahan(bahanId, excludeInput = null) {
    let total = 0;
    document.querySelectorAll('.bahan-baku-item').forEach(item => {
        const sel = item.querySelector('.bahan-select');
        const jumlahEl = item.querySelector('.bahan-jumlah');
        if (!sel || !jumlahEl) return;
        if (sel.value && sel.value.toString() === bahanId.toString()) {
            if (excludeInput && jumlahEl === excludeInput) return;
            const v = parseFloat(jumlahEl.value) || 0;
            total += v;
        }
    });
    return total;
}

function enforceLimitForItem(item) {
    const sel = item.querySelector('.bahan-select');
    const jumlahEl = item.querySelector('.bahan-jumlah');
    if (!sel || !jumlahEl) return;
    const bahanId = sel.value;
    if (!bahanId) return;

    const opt = sel.selectedOptions[0];
    const avail = opt ? (parseFloat(opt.getAttribute('data-stok')) || 0) : 0;

    // compute total requested excluding this field
    const otherTotal = getTotalRequestedForBahan(bahanId, jumlahEl);
    const maxForThis = Math.max(0, avail - otherTotal);

    let cur = parseFloat(jumlahEl.value) || 0;
    if (cur > maxForThis + EPS) {
        // cap to allowed
        const newVal = maxForThis;
        jumlahEl.value = newVal > 0 ? Number(newVal.toFixed(4)) : 0;
        showInlineWarning(item, 'Jumlah telah disesuaikan ke maksimum yang tersedia: ' + Number(newVal.toFixed(4)));
    } else {
        clearInlineWarning(item);
    }
}

function showInlineWarning(item, msg) {
    // Use SweetAlert toast for better positioning
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        icon: 'warning',
        title: msg,
        customClass: {
            popup: 'swal-toast-warning'
        }
    });
}

function clearInlineWarning(item) {
    // No need to clear anything with SweetAlert toast - it auto-dismisses
}

// Attach listeners to enforce caps live
document.addEventListener('change', function(e) {
    if (e.target.classList && e.target.classList.contains('bahan-select')) {
        // when select changes, enforce limit for all items with this bahan
        document.querySelectorAll('.bahan-baku-item').forEach(it => enforceLimitForItem(it));
        calculateEstimasiBiaya();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList && e.target.classList.contains('bahan-jumlah')) {
        const item = e.target.closest('.bahan-baku-item');
        enforceLimitForItem(item);
        calculateEstimasiBiaya();
    }
});

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

    // Form validation with aggregated stock check
    document.getElementById('produksiForm').addEventListener('submit', function(e) {
        const bahanItems = document.querySelectorAll('.bahan-baku-item');
        let hasValidBahan = false;

        // aggregate requested quantities per bahan id
        const requestedMap = {}; // bahanId -> totalRequested

        bahanItems.forEach(item => {
            const select = item.querySelector('.bahan-select');
            const jumlah = item.querySelector('.bahan-jumlah');

            if (select.value && jumlah.value && parseFloat(jumlah.value) > 0) {
                hasValidBahan = true;
                const id = select.value.toString();
                const val = parseFloat(jumlah.value) || 0;
                if (!requestedMap[id]) requestedMap[id] = 0;
                requestedMap[id] += val;
            }
        });

        if (!hasValidBahan) {
            e.preventDefault();
            alert('Minimal satu bahan baku harus dipilih dengan jumlah yang valid.');
            return false;
        }

        // compare against available stocks (read data-stok from each row's selected option)
        const shortages = [];
        const availableMap = {}; // bahanId -> available

        // build availableMap based on selected options in the form rows (prefer per-row data)
        bahanItems.forEach(item => {
            const select = item.querySelector('.bahan-select');
            if (select && select.value) {
                const id = select.value.toString();
                const opt = select.selectedOptions[0];
                const avail = opt ? (parseFloat(opt.getAttribute('data-stok')) || 0) : 0;
                // set available if not set yet (assume options list is same across rows)
                if (availableMap[id] === undefined) availableMap[id] = avail;
            }
        });

        // debug info
        console.debug('Requested map (pre-AJAX):', requestedMap);

        // Call server to get latest stok for the bahan IDs to avoid stale UI
        const ids = Object.keys(requestedMap);
        if (ids.length === 0) return true;

        e.preventDefault(); // we'll submit manually after AJAX check if OK

        fetch("{{ route('backoffice.produksi.api.bahan-stok') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: ids })
        }).then(resp => {
            if (!resp.ok) throw new Error('AJAX fetch failed');
            return resp.json();
        }).then(data => {
            const live = data.stok || {};
            console.debug('Live stok from server:', live);

            const EPS = 0.01;
            const liveShorts = [];
            for (const bid in requestedMap) {
                const req = requestedMap[bid];
                const avail = parseFloat(live[bid] || 0);
                if (req > avail + EPS) {
                    const selOpt = document.querySelector('.bahan-select option[value="' + bid + '"]');
                    const nama = selOpt ? selOpt.textContent.trim() : ('Bahan ID ' + bid);
                    liveShorts.push({ id: bid, name: nama, requested: req, available: avail });
                }
            }

            if (liveShorts.length) {
                // show inline error block (create or reuse)
                let errBlock = document.getElementById('stok-errors');
                if (!errBlock) {
                    errBlock = document.createElement('div');
                    errBlock.id = 'stok-errors';
                    errBlock.style.background = '#ffe6e6';
                    errBlock.style.border = '1px solid #ff9999';
                    errBlock.style.padding = '12px';
                    errBlock.style.margin = '12px 0';
                    errBlock.style.borderRadius = '6px';
                    const form = document.querySelector('form#produksiForm');
                    form.insertBefore(errBlock, form.firstChild);
                }
                let html = '<strong>Stok tidak mencukupi untuk beberapa bahan:</strong><ul>';
                liveShorts.forEach(s => {
                    html += '<li>' + s.name + ': Diminta ' + Number(s.requested).toFixed(4) + ', Tersedia ' + Number(s.available).toFixed(4) + '</li>';
                });
                html += '</ul>';
                errBlock.innerHTML = html;
                window.scrollTo({ top: errBlock.offsetTop - 20, behavior: 'smooth' });
                return false;
            }

            // no shortages, submit the form programmatically
            document.getElementById('produksiForm').submit();
        }).catch(err => {
            console.error('Stok check failed, falling back to client-side check', err);
            // fallback to previous client-side check
            const EPS = 0.01;
            for (const bahanId in requestedMap) {
                const available = availableMap[bahanId] !== undefined ? availableMap[bahanId] : 0;
                const requested = requestedMap[bahanId];
                if (requested > available + EPS) {
                    shortages.push({ id: bahanId, name: (document.querySelector('.bahan-select option[value="' + bahanId + '"]') || {}).textContent || ('Bahan ID ' + bahanId), requested: requested, available: available });
                }
            }
            if (shortages.length > 0) {
                let msg = 'Stok tidak mencukupi untuk beberapa bahan:\n';
                shortages.forEach(s => { msg += '- ' + s.name + ': Diminta ' + Number(s.requested).toFixed(4) + ', Tersedia ' + Number(s.available).toFixed(4) + '\n'; });
                msg += '\n(Perhatian: sistem mengizinkan toleransi ' + EPS + ' untuk perbedaan rounding)\n\n';
                msg += '\nSilakan kurangi jumlah atau tambahkan stok sebelum menyimpan.';
                alert(msg);
                return false;
            }
            // else attempt submit
            document.getElementById('produksiForm').submit();
        });
    });

    // Refresh produk select when window regains focus (after adding new product)
    window.addEventListener('focus', function() {
        // Check if we need to refresh products
        if (localStorage.getItem('refreshProdukSelect') === 'true') {
            localStorage.removeItem('refreshProdukSelect');
            location.reload();
        }
    });

    // Listen for messages from child windows (product creation)
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'produkCreated') {
            // Refresh the page to reload products
            location.reload();
        }
    });
});
</script>
@endsection