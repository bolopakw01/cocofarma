@extends('admin.layouts.app')

@php
    $pageTitle = 'Tambah Produk Baru';
@endphp

@section('title', 'Tambah Produk - Cocofarma')

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

    .file-error-alert {
        background: rgba(230, 57, 70, 0.1);
        border: 1px solid rgba(230, 57, 70, 0.3);
        border-radius: var(--border-radius);
        padding: 10px 12px;
        margin-top: 8px;
        font-size: 0.9rem;
        color: var(--danger);
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .file-error-alert i {
        margin-top: 1px;
        flex-shrink: 0;
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

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
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

    .swal2-cropper-popup {
        width: auto !important;
        max-width: 900px !important;
    }

    .swal2-cropper-popup .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Responsive layout for cropper */
    @media (max-width: 768px) {
        .swal2-cropper-popup {
            max-width: 95vw !important;
        }

        .swal2-cropper-popup .swal2-html-container > div {
            flex-direction: column !important;
            gap: 15px !important;
        }

        .swal2-cropper-popup .swal2-html-container > div > div:last-child {
            width: 100% !important;
            flex-direction: row !important;
            justify-content: center;
        }

        .swal2-cropper-popup .swal2-html-container > div > div:last-child > div:last-child {
            display: flex;
            flex-direction: row;
            gap: 8px;
            flex-wrap: wrap;
        }

        .swal2-cropper-popup .swal2-html-container > div > div:last-child > div:last-child button {
            flex: 1;
            min-width: 80px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-plus"></i> Tambah Master Produk</h1>
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

    <form class="form-container" action="{{ route('backoffice.master-produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="kode_produk">Kode Produk <span style="color: var(--danger);">*</span></label>
                <input type="text" id="kode_produk" name="kode_produk" value="{{ old('kode_produk') }}" readonly required style="background-color: #f8f9fa; cursor: not-allowed;">
                <small class="text-muted">Format: PRD-XXXXXXXXXX - Kode akan terisi otomatis dengan string acak unik</small>
                @error('kode_produk')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_produk">Nama Produk <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                @error('nama_produk')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="kategori">Kategori <span style="color: var(--danger);">*</span></label>
                <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Obat Bebas, Obat Keras, Vitamin" required>
                @error('kategori')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="satuan">Satuan <span style="color: var(--danger);">*</span></label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan') }}" placeholder="Contoh: tablet, kapsul, botol, tube" required>
                @error('satuan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="harga_jual">Harga Jual <span style="color: var(--danger);">*</span></label>
                <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" min="0" step="0.01" required>
                @error('harga_jual')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="minimum_stok">Minimum Stok <span style="color: var(--danger);">*</span></label>
                <input type="number" id="minimum_stok" name="minimum_stok" value="{{ old('minimum_stok', 10) }}" min="0" required>
                @error('minimum_stok')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status <span style="color: var(--danger);">*</span></label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto Produk</label>
                <div class="file-upload">
                    <input type="file" id="foto" name="foto" accept="image/*">
                    {{-- Hidden input to carry cropped image (base64) without replacing file input directly --}}
                    <input type="hidden" id="foto_cropped" name="foto_cropped" value="">
                    <div class="file-upload-label" id="fileUploadLabel">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Klik untuk upload foto atau drag & drop</span>
                    </div>
                </div>
                <div class="file-preview" id="filePreview">
                    <img id="previewImage" src="" alt="Preview">
                    <div class="file-info" id="fileInfo"></div>
                    <div id="fileWarning" style="display:none; color:#b02a37; margin-top:8px; font-size:0.9rem;"></div>
                    <button type="button" class="remove-file" onclick="removeFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="fileError" class="text-danger" style="display:none; margin-top:8px;"></div>
                @error('foto')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Hidden cropper container for SweetAlert --}}
        <div id="cropperContainer" style="display:none;">
            <div style="display:flex; gap:20px; align-items:flex-start; max-width:800px;">
                <!-- Left side: Cropper image -->
                <div style="flex:1; min-width:0;">
                    <div style="max-width:100%; max-height:400px; overflow:hidden; border:2px solid #e0e0e0; border-radius:8px;">
                        <img id="cropperImage" src="" style="max-width:100%; display:block;">
                    </div>
                </div>

                <!-- Right side: Controls and preview -->
                <div style="flex-shrink:0; width:200px; display:flex; flex-direction:column; gap:15px;">
                    <!-- Preview -->
                    <div style="text-align:center;">
                        <div style="font-weight:600; color:#555; margin-bottom:8px; font-size:14px;">Preview Hasil Crop</div>
                        <div style="width:150px; height:150px; overflow:hidden; background:#f8f8f8; border:2px solid #ddd; border-radius:8px; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                            <canvas id="cropperPreview" style="max-width:100%; max-height:100%;"></canvas>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateLeft" style="width:100%; font-size:12px; padding:6px;">
                            <i class="fas fa-undo"></i> Putar Kiri
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateRight" style="width:100%; font-size:12px; padding:6px;">
                            <i class="fas fa-redo"></i> Putar Kanan
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="flipHorizontal" style="width:100%; font-size:12px; padding:6px;">
                            <i class="fas fa-arrows-alt-h"></i> Balik Horizontal
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="flipVertical" style="width:100%; font-size:12px; padding:6px;">
                            <i class="fas fa-arrows-alt-v"></i> Balik Vertikal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi lengkap produk (opsional)">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <span class="text-danger" data-server-error="true">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('backoffice.master-produk.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>

<!-- Cropper.js CSS/JS (loaded via CDN). It's safe: we check for availability and fallback when absent. IDs are isolated to avoid collisions. -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<!-- SweetAlert2 for crop modal -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Auto-generate random kode produk when form loads
document.addEventListener('DOMContentLoaded', function() {
    const kodeInput = document.getElementById('kode_produk');

    // Generate random kode produk
    function generateRandomKode() {
        const prefix = 'PRD-';
        const randomString = Math.random().toString(36).substring(2, 12).toUpperCase();
        return prefix + randomString;
    }

    // Set kode produk on page load
    if (kodeInput && !kodeInput.value) {
        kodeInput.value = generateRandomKode();
    }

    // Initialize file upload (with optional cropper)
    initializeFileUpload();
});

// File upload + cropper functionality (isolated scope)
function initializeFileUpload() {
    const fileInput = document.getElementById('foto');
    const fileUploadLabel = document.getElementById('fileUploadLabel');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    const fotoCroppedInput = document.getElementById('foto_cropped');

    // Cropper UI elements
    const cropperContainer = document.getElementById('cropperContainer');
    const cropperImage = document.getElementById('cropperImage');
    const cropperPreview = document.getElementById('cropperPreview');

    let cropperInstance = null;
    let currentSwal = null;

    function safeRemoveCropper() {
        if (cropperInstance) {
            try { cropperInstance.destroy(); } catch (e) {}
            cropperInstance = null;
        }
    }

    function openCropper(dataURL) {
        if (typeof Cropper === 'undefined') {
            // No cropper available, fallback to simple preview
            previewImage.src = dataURL;
            filePreview.style.display = 'block';
            fileUploadLabel.style.display = 'none';
            return;
        }

        cropperImage.src = dataURL;

        // Use SweetAlert2 for crop modal
        currentSwal = Swal.fire({
            title: 'Crop Gambar Produk',
            html: cropperContainer.innerHTML,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Crop & Gunakan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-cropper-popup',
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            },
            didOpen: () => {
                // Initialize cropper after Swal is open
                const swalImage = Swal.getHtmlContainer().querySelector('#cropperImage');

                // Set image source and wait for it to load
                swalImage.src = dataURL;
                swalImage.onload = function() {
                    const swalPreview = Swal.getHtmlContainer().querySelector('#cropperPreview');

                    if (swalPreview) {
                        // small timeout to ensure image size available
                        setTimeout(function() {
                            cropperInstance = new Cropper(swalImage, {
                                aspectRatio: 1, // square by default for product thumbnails
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                                zoomOnWheel: true,
                                crop() {
                                    updateCropPreview(swalPreview);
                                }
                            });
                            updateCropPreview(swalPreview);
                        }, 100);
                    }
                };

                // Add event listeners for controls
                const rotateLeft = Swal.getHtmlContainer().querySelector('#rotateLeft');
                const rotateRight = Swal.getHtmlContainer().querySelector('#rotateRight');
                const flipHorizontal = Swal.getHtmlContainer().querySelector('#flipHorizontal');
                const flipVertical = Swal.getHtmlContainer().querySelector('#flipVertical');

                if (rotateLeft) rotateLeft.addEventListener('click', () => cropperInstance && cropperInstance.rotate(-90));
                if (rotateRight) rotateRight.addEventListener('click', () => cropperInstance && cropperInstance.rotate(90));
                if (flipHorizontal) flipHorizontal.addEventListener('click', () => cropperInstance && cropperInstance.scaleX(-cropperInstance.getData().scaleX || -1));
                if (flipVertical) flipVertical.addEventListener('click', () => cropperInstance && cropperInstance.scaleY(-cropperInstance.getData().scaleY || -1));
            },
            preConfirm: () => {
                // Handle crop apply
                if (cropperInstance) {
                    try {
                        const canvas = cropperInstance.getCroppedCanvas({ width: 1200, height: 1200, imageSmoothingQuality: 'high' });
                        if (!canvas) throw new Error('Canvas generation failed');
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                        // set preview and hidden input
                        previewImage.src = dataUrl;
                        fotoCroppedInput.value = dataUrl;
                        filePreview.style.display = 'block';
                        fileUploadLabel.style.display = 'none';
                        // clear any previous warning
                        const fw = document.getElementById('fileWarning'); if (fw) { fw.style.display = 'none'; fw.textContent = ''; }
                        return true;
                    } catch (err) {
                        Swal.showValidationMessage('Crop gagal: gambar terlalu besar atau memori tidak mencukupi. Silakan pilih gambar yang lebih kecil.');
                        return false;
                    }
                }
            },
            willClose: () => {
                safeRemoveCropper();
                currentSwal = null;
            }
        });
    }

    function updateCropPreview(previewCanvas) {
        if (!cropperInstance || !previewCanvas) return;
        const canvas = cropperInstance.getCroppedCanvas({ width: 600, height: 600, imageSmoothingQuality: 'high' });
        if (!canvas) return;
        // draw into preview canvas
        previewCanvas.width = canvas.width;
        previewCanvas.height = canvas.height;
        const ctx = previewCanvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(canvas, 0, 0);
    }

    const fileWarning = document.getElementById('fileWarning');

    // thresholds
    const warnThreshold = 1.5 * 1024 * 1024; // 1.5MB - show warning that upload may be rejected
    const rejectThreshold = 2 * 1024 * 1024; // 2MB - current server validation limit

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
        const fileError = document.getElementById('fileError');

        // Clear previous error messages
        fileError.style.display = 'none';
        fileError.textContent = '';
        fileError.className = 'text-danger';

        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            fileError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> File yang dipilih bukan gambar. Harap pilih file gambar yang valid (JPG, PNG, GIF, dll).';
            fileError.className = 'file-error-alert';
            fileError.style.display = 'flex';
            return;
        }

        // Validate file size: reject above server limit
        if (file.size > rejectThreshold) {
            const sizeMb = (file.size / 1024 / 1024).toFixed(2);
            fileError.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Ukuran file ${sizeMb} MB terlalu besar. Maksimal ukuran file adalah 2MB.`;
            fileError.className = 'file-error-alert';
            fileError.style.display = 'flex';
            return;
        }

        // Warn if file is approaching limit (non-blocking, ask for confirmation)
        if (file.size > warnThreshold) {
            const sizeMb = (file.size / 1024 / 1024).toFixed(2);
            const proceed = confirm(`Gambar berukuran ${sizeMb} MB. Ini mendekati batas maksimal upload dan dapat menyebabkan crop/upload gagal. Lanjutkan?`);
            if (!proceed) return;
            if (fileWarning) { fileWarning.style.display = 'block'; fileWarning.textContent = 'Peringatan: gambar besar dapat menyebabkan kegagalan crop atau ditolak oleh server.'; }
        } else {
            if (fileWarning) { fileWarning.style.display = 'none'; fileWarning.textContent = ''; }
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const dataUrl = e.target.result;

            // Clear any previous error messages when file is successfully loaded
            fileError.style.display = 'none';
            fileError.textContent = '';
            fileError.className = 'text-danger';

            // If Cropper available, open modal and let user crop
            if (typeof Cropper !== 'undefined') {
                openCropper(dataUrl);
            } else {
                // fallback: just use preview
                previewImage.src = dataUrl;
                filePreview.style.display = 'block';
                fileUploadLabel.style.display = 'none';

                // Show file info
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.textContent = `${file.name} (${fileSize} MB)`;
            }
        };
        reader.readAsDataURL(file);
    }

    // when removing file, also clear hidden cropped input and error messages
    const originalRemoveFile = window.removeFile;
    window.removeFile = function() {
        fileInput.value = '';
        fotoCroppedInput.value = '';
        filePreview.style.display = 'none';
        fileUploadLabel.style.display = 'flex';

        // Clear error messages
        const fileError = document.getElementById('fileError');
        fileError.style.display = 'none';
        fileError.textContent = '';
        fileError.className = 'text-danger';

        const fileWarning = document.getElementById('fileWarning');
        if (fileWarning) {
            fileWarning.style.display = 'none';
            fileWarning.textContent = '';
        }
    };
}

// Form validation and submission with enhanced visual feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['nama_produk', 'kategori', 'satuan', 'harga_jual', 'minimum_stok', 'status'];
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
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
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
    
    // Check if product was successfully created and notify parent window
    @if(session('success'))
        if (window.opener) {
            window.opener.postMessage({ type: 'produkCreated', message: 'Produk berhasil dibuat' }, '*');
        }
        // Also set localStorage for fallback
        if (window.opener) {
            window.opener.localStorage.setItem('refreshProdukSelect', 'true');
        }
    @endif
});
</script>
@endsection