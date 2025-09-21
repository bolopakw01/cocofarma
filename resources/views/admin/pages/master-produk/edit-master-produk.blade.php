@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Produk';
@endphp

@section('title', 'Edit Produk - Cocofarma')

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
        max-width: 900px;
        margin: 40px auto 60px auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        overflow: hidden;
        min-height: calc(100vh - 200px);
        position: relative;
        animation: fadeInUp 0.6s ease-out;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-header h1 i {
        color: var(--primary);
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    .form-container {
        animation: slideInLeft 0.5s ease-out 0.2s both;
    }

    .form-group {
        margin-bottom: 25px;
        animation: slideInLeft 0.5s ease-out 0.3s both;
    }

    .form-group:nth-child(2) {
        animation-delay: 0.4s;
    }

    .form-group:nth-child(3) {
        animation-delay: 0.5s;
    }

    .form-group:nth-child(4) {
        animation-delay: 0.6s;
    }

    .form-group:nth-child(5) {
        animation-delay: 0.7s;
    }

    .form-group:nth-child(6) {
        animation-delay: 0.8s;
    }

    .form-group:nth-child(7) {
        animation-delay: 0.9s;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
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
        resize: vertical;
        min-height: 80px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row .form-group {
        margin-bottom: 0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 2px solid var(--light-gray);
        animation: slideInLeft 0.5s ease-out 1s both;
    }

    .text-danger {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .error-border {
        border-color: var(--danger) !important;
        box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15) !important;
    }

    .alert {
        padding: 15px 20px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
        border: 1px solid rgba(76, 201, 240, 0.3);
    }

    .alert-danger {
        background: rgba(230, 57, 70, 0.1);
        color: var(--danger);
        border: 1px solid rgba(230, 57, 70, 0.3);
    }

    .alert i {
        font-size: 1.1rem;
    }

    /* File upload styling */
    .file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px;
        border: 2px dashed var(--light-gray);
        border-radius: var(--border-radius);
        background: var(--light);
        cursor: pointer;
        transition: var(--transition);
        min-height: 60px;
    }

    .file-upload-label:hover {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.05);
    }

    .file-upload-label.dragover {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.1);
    }

    .file-preview {
        display: none;
        margin-top: 10px;
        text-align: center;
    }

    .file-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .file-info {
        margin-top: 10px;
        font-size: 0.9rem;
        color: var(--gray);
    }

    .remove-file {
        background: var(--danger);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        margin-left: 10px;
        transition: var(--transition);
    }

    .remove-file:hover {
        background: #c22c38;
        transform: scale(1.1);
    }

    .current-image {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        background: var(--light);
        border-radius: var(--border-radius);
        margin-bottom: 10px;
    }

    .current-image img {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius);
        object-fit: cover;
        border: 2px solid var(--light-gray);
    }

    .current-image-info {
        flex: 1;
    }

    .current-image-info small {
        color: var(--gray);
        display: block;
        margin-top: 2px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .container {
            margin: 20px auto 40px auto;
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }

    .form-group input[readonly] {
        background-color: #f8f9fa !important;
        cursor: not-allowed !important;
        opacity: 0.8;
        border-color: #dee2e6 !important;
    }

    .form-group input[readonly]:focus {
        border-color: #dee2e6 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Master Produk</h1>
        <a href="{{ route('backoffice.master-produk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-container" action="{{ route('backoffice.master-produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="kode_produk">Kode Produk <span style="color: var(--danger);">*</span></label>
                <input type="text" id="kode_produk" name="kode_produk" value="{{ old('kode_produk', $produk->kode_produk) }}" readonly required style="background-color: #f8f9fa; cursor: not-allowed;">
                <small class="text-muted">Kode produk tidak dapat diubah</small>
                @error('kode_produk')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_produk">Nama Produk <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                @error('nama_produk')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="kategori">Kategori <span style="color: var(--danger);">*</span></label>
                <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $produk->kategori) }}" placeholder="Contoh: Obat Bebas, Obat Keras, Vitamin" required>
                @error('kategori')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="satuan">Satuan <span style="color: var(--danger);">*</span></label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan', $produk->satuan) }}" placeholder="Contoh: tablet, kapsul, botol, tube" required>
                @error('satuan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="harga_jual">Harga Jual <span style="color: var(--danger);">*</span></label>
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" min="0" step="0.01" required>
                @error('harga_jual')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="minimum_stok">Minimum Stok <span style="color: var(--danger);">*</span></label>
                <input type="number" id="minimum_stok" name="minimum_stok" value="{{ old('minimum_stok', $produk->minimum_stok) }}" min="0" required>
                @error('minimum_stok')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status <span style="color: var(--danger);">*</span></label>
                <select id="status" name="status" required>
                    <option value="1" {{ old('status', $produk->status) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $produk->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto Produk</label>
                @if($produk->foto)
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="Current Image">
                        <div class="current-image-info">
                            <strong>Foto Saat Ini</strong>
                            <small>Klik upload di bawah untuk mengganti foto</small>
                        </div>
                    </div>
                @endif
                <div class="file-upload">
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <div class="file-upload-label" id="fileUploadLabel">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Klik untuk upload foto baru atau drag & drop</span>
                    </div>
                </div>
                <div class="file-preview" id="filePreview">
                    <img id="previewImage" src="" alt="Preview">
                    <div class="file-info" id="fileInfo"></div>
                    <button type="button" class="remove-file" onclick="removeFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @error('foto')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi lengkap produk (opsional)">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="text-danger" data-server-error="true">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('backoffice.master-produk.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Produk
            </button>
        </div>
    </form>
</div>

<script>
// Prevent manual editing of kode field
document.getElementById('kode_produk').addEventListener('keydown', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_produk').addEventListener('paste', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_produk').addEventListener('cut', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_produk').addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Initialize file upload
document.addEventListener('DOMContentLoaded', function() {
    initializeFileUpload();
});

// File upload functionality
function initializeFileUpload() {
    const fileInput = document.getElementById('foto');
    const fileUploadLabel = document.getElementById('fileUploadLabel');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, unhighlight, false);
    });

    fileUploadLabel.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        fileUploadLabel.classList.add('dragover');
    }

    function unhighlight() {
        fileUploadLabel.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    }

    function handleFileSelect(file) {
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Harap pilih file gambar yang valid.');
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB.');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            filePreview.style.display = 'block';
            fileUploadLabel.style.display = 'none';

            // Show file info
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.textContent = `${file.name} (${fileSize} MB)`;
        };
        reader.readAsDataURL(file);
    }
}

function removeFile() {
    const fileInput = document.getElementById('foto');
    const fileUploadLabel = document.getElementById('fileUploadLabel');
    const filePreview = document.getElementById('filePreview');

    fileInput.value = '';
    filePreview.style.display = 'none';
    fileUploadLabel.style.display = 'flex';
}

// Form validation and submission with enhanced visual feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['nama_produk', 'kategori', 'satuan', 'harga_jual', 'minimum_stok'];
    let isValid = true;

    // Reset previous error states
    document.querySelectorAll('.form-group input, .form-group select').forEach(element => {
        element.classList.remove('error-border');
    });
    document.querySelectorAll('.text-danger').forEach(error => {
        if (!error.hasAttribute('data-server-error')) {
            error.remove();
        }
    });

    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.classList.add('error-border');
            element.style.borderColor = 'var(--danger)';
            element.style.boxShadow = '0 0 0 3px rgba(230, 57, 70, 0.15)';

            // Add error message if not exists
            if (!element.parentElement.querySelector('.text-danger[data-client-error]')) {
                const errorMsg = document.createElement('span');
                errorMsg.className = 'text-danger';
                errorMsg.setAttribute('data-client-error', 'true');
                errorMsg.textContent = 'Field ini wajib diisi';
                element.parentElement.appendChild(errorMsg);
            }
            isValid = false;
        }
    });

    // Validate harga_jual
    const hargaJual = document.getElementById('harga_jual');
    if (hargaJual.value && parseFloat(hargaJual.value) < 0) {
        hargaJual.classList.add('error-border');
        hargaJual.style.borderColor = 'var(--danger)';
        hargaJual.style.boxShadow = '0 0 0 3px rgba(230, 57, 70, 0.15)';

        if (!hargaJual.parentElement.querySelector('.text-danger[data-client-error]')) {
            const errorMsg = document.createElement('span');
            errorMsg.className = 'text-danger';
            errorMsg.setAttribute('data-client-error', 'true');
            errorMsg.textContent = 'Harga jual tidak boleh negatif';
            hargaJual.parentElement.appendChild(errorMsg);
        }
        isValid = false;
    }

    // Validate minimum_stok
    const minStok = document.getElementById('minimum_stok');
    if (minStok.value && parseInt(minStok.value) < 0) {
        minStok.classList.add('error-border');
        minStok.style.borderColor = 'var(--danger)';
        minStok.style.boxShadow = '0 0 0 3px rgba(230, 57, 70, 0.15)';

        if (!minStok.parentElement.querySelector('.text-danger[data-client-error]')) {
            const errorMsg = document.createElement('span');
            errorMsg.className = 'text-danger';
            errorMsg.setAttribute('data-client-error', 'true');
            errorMsg.textContent = 'Minimum stok tidak boleh negatif';
            minStok.parentElement.appendChild(errorMsg);
        }
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        // Scroll to first error
        const firstError = document.querySelector('.error-border');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
        return false;
    }

    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupdate...';
    submitBtn.disabled = true;

    // Re-enable after 10 seconds (in case of error)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});

// Format number inputs
document.getElementById('harga_jual').addEventListener('input', function() {
    // Allow only numbers and decimal point
    this.value = this.value.replace(/[^0-9.]/g, '');
});

document.getElementById('minimum_stok').addEventListener('input', function() {
    // Allow only positive integers
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Enhanced kategori suggestions
const kategoriSuggestions = [
    'Obat Bebas', 'Obat Bebas Terbatas', 'Obat Keras', 'Obat Wajib Apotek',
    'Vitamin & Suplemen', 'Obat Herbal', 'Kosmetik', 'Alat Kesehatan',
    'Obat Anak', 'Obat Dewasa', 'Obat Lansia'
];

document.getElementById('kategori').addEventListener('input', function() {
    const value = this.value.toLowerCase();
    const datalist = document.getElementById('kategori-list') || createDatalist();

    // Clear existing options
    datalist.innerHTML = '';

    // Add matching suggestions
    kategoriSuggestions.forEach(suggestion => {
        if (suggestion.toLowerCase().includes(value) && value.length > 0) {
            const option = document.createElement('option');
            option.value = suggestion;
            datalist.appendChild(option);
        }
    });
});

function createDatalist() {
    const datalist = document.createElement('datalist');
    datalist.id = 'kategori-list';
    document.getElementById('kategori').setAttribute('list', 'kategori-list');
    document.body.appendChild(datalist);
    return datalist;
}

// Initialize datalist on load
document.addEventListener('DOMContentLoaded', function() {
    createDatalist();
});
</script>
@endsection