@extends('admin.layouts.app')

@section('title', 'Edit Master Bahan Baku - Cocofarma')

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
        max-width: 800px;
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

    .info-box {
        background: rgba(73, 143, 239, 0.1);
        border: 1px solid rgba(73, 143, 239, 0.3);
        border-radius: var(--border-radius);
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-box i {
        color: var(--info);
        font-size: 1.2rem;
    }

    .info-box p {
        margin: 0;
        color: var(--info);
        font-weight: 500;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Master Bahan Baku</h1>
        <a href="{{ route('backoffice.master-bahan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <div>
            <p><strong>Mengedit Master Bahan Baku</strong></p>
            <small>Perubahan pada master bahan akan mempengaruhi semua bahan baku operasional yang terkait.</small>
        </div>
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

    <form class="form-container" action="{{ route('backoffice.master-bahan.update', $bahanBaku->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="kode_bahan">Kode Bahan <span style="color: var(--danger);">*</span></label>
                <input type="text" id="kode_bahan" name="kode_bahan" value="{{ old('kode_bahan', $bahanBaku->kode_bahan) }}" readonly required style="background-color: #f8f9fa; cursor: not-allowed;">
                <small class="text-muted">Kode bahan tidak dapat diubah setelah dibuat</small>
                @error('kode_bahan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_bahan">Nama Bahan <span style="color: var(--danger);">*</span></label>
                <input type="text" id="nama_bahan" name="nama_bahan" value="{{ old('nama_bahan', $bahanBaku->nama_bahan) }}" required>
                @error('nama_bahan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="satuan">Satuan <span style="color: var(--danger);">*</span></label>
                <input type="text" id="satuan" name="satuan" value="{{ old('satuan', $bahanBaku->satuan) }}" placeholder="Contoh: kg, liter, pcs" required>
                @error('satuan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="harga_per_satuan">Harga per Satuan <span style="color: var(--danger);">*</span></label>
                <input type="number" id="harga_per_satuan" name="harga_per_satuan" value="{{ old('harga_per_satuan', $bahanBaku->harga_per_satuan_formatted) }}" min="0" step="1" required>
                @error('harga_per_satuan')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="stok_minimum">Stok Minimum</label>
                <input type="number" id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $bahanBaku->stok_minimum_formatted) }}" min="0" step="1" placeholder="Masukkan stok minimum (opsional)">
                <small class="text-muted">Stok minimum untuk notifikasi ketika stok rendah</small>
                @error('stok_minimum')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color: var(--danger);">*</span></label>
                <select id="status" name="status" required>
                    <option value="aktif" {{ old('status', $bahanBaku->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $bahanBaku->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <span class="text-danger" data-server-error="true">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi master bahan baku (opsional)">{{ old('deskripsi', $bahanBaku->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="text-danger" data-server-error="true">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('backoffice.master-bahan.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Master Bahan
            </button>
        </div>
    </form>
</div>

<script>
// Prevent manual editing of kode field
document.getElementById('kode_bahan').addEventListener('keydown', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_bahan').addEventListener('paste', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_bahan').addEventListener('cut', function(e) {
    e.preventDefault();
    return false;
});

document.getElementById('kode_bahan').addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Form validation and submission with enhanced visual feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['nama_bahan', 'satuan'];
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
            if (!element.parentNode.querySelector('.text-danger[data-client-error]')) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-danger';
                errorDiv.setAttribute('data-client-error', 'true');
                errorDiv.textContent = 'Field ini wajib diisi';
                element.parentNode.appendChild(errorDiv);
            }

            isValid = false;
        } else {
            element.classList.remove('error-border');
            element.style.borderColor = 'var(--light-gray)';
            element.style.boxShadow = '0 0 0 3px rgba(67, 97, 238, 0.15)';

            // Remove client error message
            const clientError = element.parentNode.querySelector('.text-danger[data-client-error]');
            if (clientError) {
                clientError.remove();
            }
        }
    });

    if (!isValid) {
        e.preventDefault();

        // Scroll to first error
        const firstError = document.querySelector('.error-border');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }

        // Show alert with animation
        const alertDiv = document.createElement('div');
        alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--danger);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            animation: slideInRight 0.3s ease-out;
        `;
        alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Mohon lengkapi semua field yang wajib diisi!';

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);

        return; // Stop execution if validation fails
    }

    // Add loading state if validation passes
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
    submitBtn.style.opacity = '0.7';

    // Re-enable after 3 seconds (in case of slow response)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        submitBtn.style.opacity = '1';
    }, 3000);
});

// Add slide animations for alerts
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>

@endsection