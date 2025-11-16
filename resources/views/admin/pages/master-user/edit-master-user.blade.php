@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit User';
    $defaultAvatar = asset('bolopa/back/images/icon/bi--person-circle.svg');
    $storedAvatar = $avatarUrl ?? null;
    $removedViaOld = old('remove_avatar') === '1';
    $initialAvatar = $removedViaOld ? $defaultAvatar : ($storedAvatar ?: $defaultAvatar);
    $hasInitialAvatar = !$removedViaOld && (bool) $storedAvatar;
    $statusOldValue = (string) old('status', in_array($user->status, ['1', 1, true, 'active'], true) ? '1' : '0');
@endphp

@section('title', 'Edit User - Cocofarma')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3651c7;
    --secondary: #0f172a;
    --accent: #4895ef;
    --light: #f8fbff;
    --light-gray: #dfe7f5;
    --gray: #6b7280;
    --dark: #1f2937;
    --success: #2a9d8f;
    --danger: #e63946;
    --warning: #f4a261;
    --info: #3a86ff;
    --border-radius: 16px;
    --transition: all 0.2s ease-in-out;
    --card-shadow: 0 24px 45px -22px rgba(15, 23, 42, 0.35);
}

.edit-user-wrapper {
    padding: 32px;
    background: var(--light);
    min-height: calc(100vh - 160px);
}

.edit-user-card {
    background: #ffffff;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    padding: 32px;
    animation: fadeInUp 0.45s ease-out;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    margin-bottom: 28px;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-title i {
    color: var(--primary);
}

.page-subtitle {
    margin: 0;
    color: var(--gray);
    font-size: 0.95rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn i {
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--primary);
    color: #ffffff;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-secondary {
    background: #e0e7ff;
    color: var(--secondary);
}

.btn-secondary:hover {
    background: #c7d2fe;
}

.btn-outline {
    background: transparent;
    color: var(--primary);
    border: 1px solid var(--primary);
}

.btn-outline:hover {
    background: var(--primary);
    color: #ffffff;
}

.btn-light {
    background: #ffffff;
    color: var(--secondary);
    border: 1px solid var(--light-gray);
}

.btn-light:hover {
    background: var(--light);
}

.content-body {
    border-radius: var(--border-radius);
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.05);
    padding: 32px;
}

.info-box {
    border-radius: 14px;
    background: rgba(67, 97, 238, 0.08);
    border: 1px solid rgba(67, 97, 238, 0.15);
    padding: 16px 18px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 24px;
}

.info-box i {
    color: var(--primary);
    font-size: 1.1rem;
    margin-top: 2px;
}

.info-box p {
    margin: 0;
    color: var(--dark);
    font-weight: 600;
    font-size: 0.95rem;
}

.info-box small {
    display: block;
    margin-top: 4px;
    color: var(--gray);
}

.alert {
    padding: 14px 18px;
    border-radius: var(--border-radius);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.alert i {
    font-size: 1rem;
}

.alert-success {
    background: rgba(42, 173, 143, 0.12);
    color: var(--success);
    border: 1px solid rgba(42, 173, 143, 0.3);
}

.alert-danger {
    background: rgba(230, 57, 70, 0.12);
    color: var(--danger);
    border: 1px solid rgba(230, 57, 70, 0.3);
}

.form-section {
    background: var(--light);
    border-radius: var(--border-radius);
    padding: 24px;
    margin-bottom: 22px;
    border: 1px solid var(--light-gray);
    animation: slideInLeft 0.5s ease-out;
}

.form-section h3 {
    font-size: 1.12rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
    font-size: 0.92rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--light-gray);
    border-radius: var(--border-radius);
    font-size: 0.94rem;
    transition: var(--transition);
    background: #ffffff;
}

.form-group textarea {
    min-height: 110px;
    resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.avatar-preview {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 2px dashed var(--light-gray);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: var(--transition);
}

.avatar-preview.has-image {
    border-style: solid;
    border-color: var(--primary);
    background: transparent;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.profile-photo-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 28px;
    align-items: stretch;
}

.profile-photo-preview-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 18px;
}

.profile-photo-buttons {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.profile-photo-actions {
    display: flex;
    gap: 10px;
    width: 100%;
}

.profile-photo-actions .btn {
    flex: 1;
    justify-content: center;
}

.profile-photo-support {
    background: rgba(15, 23, 42, 0.02);
    border: 1px dashed var(--light-gray);
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.profile-photo-support h4 {
    margin: 0;
    font-size: 0.96rem;
    font-weight: 600;
    color: var(--secondary);
}

.profile-photo-guidelines {
    margin: 0;
    padding-left: 18px;
    font-size: 0.86rem;
    color: var(--gray);
    display: grid;
    gap: 6px;
}

.avatar-input {
    display: none;
}

.avatar-note {
    font-size: 0.8rem;
    color: var(--gray);
    line-height: 1.4;
}

.input-group {
    position: relative;
}

.input-group .input-group-text {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    cursor: pointer;
    z-index: 2;
    display: inline-flex;
    align-items: center;
}

.text-danger {
    color: var(--danger);
    font-size: 0.82rem;
    margin-top: 6px;
    display: block;
}

.text-muted {
    color: var(--gray);
    font-size: 0.8rem;
    margin-top: 6px;
    display: block;
}

.required-indicator {
    color: var(--danger);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 22px;
    border-top: 2px solid var(--light-gray);
    margin-top: 30px;
}

.password-strength {
    margin-top: 6px;
    font-size: 0.78rem;
    font-weight: 600;
}

.password-strength.weak {
    color: var(--danger);
}

.password-strength.medium {
    color: var(--warning);
}

.password-strength.strong {
    color: var(--success);
}

@media (max-width: 992px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-row {
        grid-template-columns: repeat(1, 1fr);
    }

    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .form-actions .btn,
    .profile-photo-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .profile-photo-layout {
        grid-template-columns: 1fr;
    }

    .profile-photo-preview-block {
        align-items: flex-start;
    }

    .profile-photo-actions {
        flex-direction: column;
        align-items: stretch;
    }
}

	@media (max-width: 576px) {
    .edit-user-wrapper,
    .content-body {
        padding: 20px;
    }

    .edit-user-card {
        padding: 22px;
    }

    .profile-photo-support {
        padding: 16px;
    }

    .cropper-modal__dialog {
        max-width: 100%;
    }

    .cropper-modal__controls {
        flex-direction: column;
        gap: 8px;
    }

    .cropper-zoom-slider {
        max-width: 100%;
    }

    #avatarCropperModal .modal-dialog {
        width: 95vw;
        max-width: none;
        height: auto;
        aspect-ratio: 4 / 3;
        max-height: 80vh;
        margin: 10px auto;
    }

    .cropper-wrapper {
        min-height: 300px;
        max-height: 400px;
    }
}@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(18px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-12px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

	#avatarCropperModal {
		z-index: 1050 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
	}

	#avatarCropperModal .modal-dialog {
		max-width: 800px;
		width: 90vw;
		max-height: 90vh;
		height: 600px;
		aspect-ratio: 4 / 3;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	#avatarCropperModal .modal-content {
		z-index: 1051 !important;
		pointer-events: auto !important;
		border-radius: 12px;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
	}

	.cropper-wrapper {
		position: relative;
		/* increased height to give bigger drag/crop area */
		min-height: 400px;
		max-height: 500px;
		background: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: 0.75rem;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
	}

	.cropper-wrapper img {
		max-width: 100%;
		display: block;
	}

	.cropper-fallback {
		text-align: center;
		padding: 24px;
	}

	.cropper-fallback img {
		max-height: 320px;
		border: 1px solid #dee2e6;
		border-radius: 0.5rem;
	}

	.crop-preview-panel {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 12px;
	}



	.cropper-controls {
		display: flex;
		gap: 8px;
		align-items: center;
	}

	.cropper-controls .btn {
		padding: 6px 10px;
		font-size: 0.9rem;
	}

	.avatar-preview-frame {
		/* larger preview for easier visibility */
		width: 220px;
		height: 220px;
		border-radius: 50%;
		overflow: hidden;
		position: relative;
		background: #f1f5f9;
		border: 1px solid #e2e8f0;
	}

	.avatar-crop-preview {
		width: 100%;
		height: 100%;
		border-radius: inherit;
		overflow: hidden;
	}

	.avatar-crop-preview img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.avatar-preview-fallback {
		position: absolute;
		inset: 0;
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.cropper-container {
		pointer-events: auto !important;
	}
</style>
@endpush

@section('content')
<div class="edit-user-wrapper">
    <div class="edit-user-card">
        <div class="page-header">
            <div>
                <h1 class="page-title"><i class="fas fa-user-edit"></i> Edit User</h1>
                <p class="page-subtitle">Perbarui identitas, akses, dan keamanan akun pengguna.</p>
            </div>
            <a href="{{ route('backoffice.master-user.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke daftar
            </a>
        </div>

        <div class="content-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Periksa kembali formulir. Terdapat data yang belum valid.
                </div>
            @endif

            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div>
                    <p>Pastikan nama, email, dan role sesuai dengan akses yang diberikan.</p>
                    <small>Biarkan kolom password kosong jika tidak ingin mengubah kata sandi.</small>
                </div>
            </div>

            <form action="{{ route('backoffice.master-user.update', $user->id) }}" method="POST" id="userEditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="remove_avatar" id="removeAvatar" value="{{ old('remove_avatar', '0') }}">

                <div class="form-section">
                    <h3><i class="fas fa-id-badge"></i> Foto Profil</h3>
                    <div class="profile-photo-layout">
                        <div class="profile-photo-preview-block">
                            <div
                                class="avatar-preview{{ $hasInitialAvatar ? ' has-image' : '' }}"
                                id="avatarPreviewWrapper"
                                data-initial-has-image="{{ $hasInitialAvatar ? 'true' : 'false' }}">
                                <img
                                    src="{{ $initialAvatar }}"
                                    alt="Avatar {{ $user->name }}"
                                    id="avatarPreviewImage"
                                    data-default="{{ $defaultAvatar }}"
                                    data-initial="{{ $initialAvatar }}">
                            </div>
                            <div class="profile-photo-buttons">
                                <label for="avatarInput" class="btn btn-primary">
                                    <i class="fas fa-camera"></i> Pilih Foto
                                </label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="avatar-input">
                                <div class="profile-photo-actions">
                                    <button type="button" class="btn btn-light" id="avatarRemove">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="avatarReset">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="profile-photo-support">
                            <h4>Panduan Foto</h4>
                            <ul class="profile-photo-guidelines">
                                <li>Gunakan rasio 1:1 agar tampilan avatar tetap proporsional.</li>
                                <li>Pilih foto dengan pencahayaan baik dan wajah terlihat jelas.</li>
                                <li>Format JPG, PNG, atau WEBP dengan ukuran maksimal 2MB.</li>
                            </ul>
                            <p class="avatar-note">
                                Foto membantu tim mengenali pemilik akun lebih cepat. Anda dapat menghapus atau mengatur ulang kapan saja.
                            </p>
                            @error('avatar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-user-circle"></i> Informasi Dasar</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nama Lengkap <span class="required-indicator">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Username <span class="required-indicator">*</span></label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email <span class="required-indicator">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Opsional">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" placeholder="Opsional">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-user-shield"></i> Peran &amp; Status</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role">Role <span class="required-indicator">*</span></label>
                            <select id="role" name="role" required>
                                <option value="" disabled {{ old('role', $user->role) ? '' : 'selected' }}>Pilih role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->role) === $role->name ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status Akun <span class="required-indicator">*</span></label>
                            <select id="status" name="status" required>
                                <option value="1" {{ $statusOldValue === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $statusOldValue === '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-lock"></i> Keamanan</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <span class="input-group-text" data-toggle="password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <span class="text-muted">Minimal 8 karakter dengan kombinasi huruf dan angka.</span>
                            <div class="password-strength" id="passwordStrength"></div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                                <span class="input-group-text" data-toggle="password" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('backoffice.master-user.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

	<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header d-flex align-items-center justify-content-between">
					<div class="d-flex align-items-center">
						<h5 class="modal-title mb-0">Sesuaikan Foto Profil</h5>
						<div class="cropper-controls ms-3">
							<button type="button" class="btn btn-sm btn-outline-primary" id="btnZoomOut" title="Zoom keluar"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
							<button type="button" class="btn btn-sm btn-outline-primary" id="btnZoomIn" title="Zoom masuk"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
							<button type="button" class="btn btn-sm btn-outline-primary" id="btnRotate" title="Putar 90°"><i class="fa-solid fa-rotate-right"></i></button>
							<button type="button" class="btn btn-sm btn-outline-primary" id="btnReset" title="Setel ulang"><i class="fa-solid fa-rotate-left"></i></button>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
				</div>
				<div class="modal-body">
					<div class="row g-3 align-items-start">
						<div class="col-12 col-lg-8">
							<div class="cropper-wrapper">
								<img id="cropperImage" class="d-none" alt="Pratinjau pemotongan">
								<div id="cropperFallback" class="cropper-fallback d-none">
									<p class="mb-3">Tidak dapat memuat pemotong gambar secara interaktif. Anda masih dapat menggunakan gambar apa adanya.</p>
									<img id="cropperFallbackPreview" class="img-fluid rounded" alt="Pratinjau gambar">
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">
							<div class="crop-preview-panel">
								<div class="avatar-preview-frame">
									<div class="avatar-crop-preview"></div>
									<img id="cropperPreviewFallback" class="avatar-preview-fallback d-none" alt="Pratinjau hasil">
								</div>
								<p class="text-muted small mb-0 text-center">Pratinjau hasil akhir</p>
							</div>
						</div>
					</div>
					<p class="text-muted small mt-3 mb-0">Seret untuk menyesuaikan area pemotongan. Pastikan wajah berada di tengah.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary" id="cropperApply">Simpan &amp; Terapkan</button>
				</div>
			</div>
		</div>
	</div>
    
			<!-- Toast notifications -->
			<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
				<div id="cropToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="d-flex">
						<div class="toast-body" id="cropToastBody">Perubahan berhasil disimpan.</div>
						<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
					</div>
				</div>

				<div id="cropErrorToast" class="toast align-items-center text-white bg-danger border-0 d-none" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="d-flex">
						<div class="toast-body" id="cropErrorToastBody">Terjadi kesalahan saat memproses gambar.</div>
						<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
					</div>
				</div>
			</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
(function() {
    const avatarInput = document.getElementById('avatarInput');
    const previewImage = document.getElementById('avatarPreviewImage');
    const previewWrapper = document.getElementById('avatarPreviewWrapper');
    const removeButton = document.getElementById('avatarRemove');
    const resetButton = document.getElementById('avatarReset');
    const removeInput = document.getElementById('removeAvatar');
    const modalEl = document.getElementById('cropperModal');
    const cropImage = document.getElementById('cropperImage');
    const fallbackWrapper = document.getElementById('cropperFallback');
    const fallbackImage = document.getElementById('cropperFallbackPreview');
    const cropButton = document.getElementById('cropperApply');
    const previewContainer = document.querySelector('.avatar-crop-preview');
    const previewFallbackImg = document.getElementById('cropperPreviewFallback');

    if (!previewImage || !previewWrapper) {
        return;
    }

    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        console.error('Bootstrap Modal JavaScript tidak ditemukan. Pastikan bootstrap.min.js dimuat.');
        return;
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    const state = {
        cropper: null,
        objectUrl: '',
        fallbackUrl: '',
        currentPreviewUrl: '',
        initialPreviewSrc: previewImage && previewImage.getAttribute('src') ? previewImage.getAttribute('src') : '',
        initialPreviewIsObject: previewImage && previewImage.getAttribute('src') ? previewImage.getAttribute('src').startsWith('blob:') : false,
        cropConfirmed: false,
        pendingFile: null
    };

    if (state.initialPreviewIsObject) {
        state.currentPreviewUrl = state.initialPreviewSrc;
    }

    const syncPreviewVisibility = () => {
        const hasImage = previewImage && previewImage.getAttribute('src');
        if (previewWrapper) {
            previewWrapper.classList.toggle('has-image', !!hasImage);
        }
    };

    const showPreview = (url, isObjectUrl = false) => {
        if (state.currentPreviewUrl && state.currentPreviewUrl !== url) {
            URL.revokeObjectURL(state.currentPreviewUrl);
            state.currentPreviewUrl = '';
        }

        if (previewImage) {
            if (url) {
                previewImage.src = url;
            } else {
                previewImage.removeAttribute('src');
            }
        }

        if (url && isObjectUrl) {
            state.currentPreviewUrl = url;
        } else if (!url) {
            state.currentPreviewUrl = '';
        }

        syncPreviewVisibility();
    };

    const clearPreviewBox = () => {
        if (previewContainer) {
            previewContainer.innerHTML = '';
            previewContainer.classList.remove('d-none');
        }
        if (previewFallbackImg) {
            previewFallbackImg.src = '';
            previewFallbackImg.classList.add('d-none');
        }
    };

    // Toast helpers
    const toastSuccessEl = document.getElementById('cropToast');
    const toastSuccessBody = document.getElementById('cropToastBody');
    const toastErrorEl = document.getElementById('cropErrorToast');
    const toastErrorBody = document.getElementById('cropErrorToastBody');

    function showToast(message, type = 'success') {
        const el = (type === 'success') ? toastSuccessEl : toastErrorEl;
        const body = (type === 'success') ? toastSuccessBody : toastErrorBody;
        if (!el || !body) return;
        body.textContent = message;
        // reset color classes
        el.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-primary', 'd-none');
        if (type === 'success') el.classList.add('bg-success');
        else if (type === 'info') el.classList.add('bg-primary');
        else if (type === 'warning') el.classList.add('bg-warning');
        else el.classList.add('bg-danger');
        try {
            new bootstrap.Toast(el, { delay: 3500 }).show();
        } catch (e) {
            console.warn('Toast failed to show', e);
        }
    }

    const resetCropperState = () => {
        if (state.cropper) {
            state.cropper.destroy();
            state.cropper = null;
        }
        if (state.objectUrl) {
            URL.revokeObjectURL(state.objectUrl);
            state.objectUrl = '';
        }
        if (state.fallbackUrl) {
            URL.revokeObjectURL(state.fallbackUrl);
            state.fallbackUrl = '';
        }
        if (cropImage) {
            cropImage.src = '';
            cropImage.classList.add('d-none');
            cropImage.onload = null;
            cropImage.onerror = null;
        }
        if (fallbackImage) {
            fallbackImage.src = '';
        }
        if (fallbackWrapper) {
            fallbackWrapper.classList.add('d-none');
        }
        clearPreviewBox();
    };

    const activateFallback = (file) => {
        if (!file) {
            return;
        }
        if (state.cropper) {
            state.cropper.destroy();
            state.cropper = null;
        }
        if (state.objectUrl) {
            URL.revokeObjectURL(state.objectUrl);
            state.objectUrl = '';
        }
        if (cropImage) {
            cropImage.src = '';
            cropImage.classList.add('d-none');
            cropImage.onload = null;
            cropImage.onerror = null;
        }
        if (fallbackWrapper) {
            fallbackWrapper.classList.remove('d-none');
        }
        if (state.fallbackUrl) {
            URL.revokeObjectURL(state.fallbackUrl);
        }
        state.fallbackUrl = URL.createObjectURL(file);
        if (fallbackImage) {
            fallbackImage.src = state.fallbackUrl;
        }
        if (previewContainer) {
            previewContainer.classList.add('d-none');
            previewContainer.innerHTML = '';
        }
        if (previewFallbackImg) {
            previewFallbackImg.src = state.fallbackUrl;
            previewFallbackImg.classList.remove('d-none');
        }

        // notify user about fallback
        if (typeof showToast === 'function') showToast('Cropper tidak tersedia — menggunakan gambar asli', 'warning');
    };

    const initializeCropper = () => {
        if (cropImage) {
            cropImage.classList.remove('d-none');
        }
        if (fallbackWrapper) {
            fallbackWrapper.classList.add('d-none');
        }
        if (previewFallbackImg) {
            previewFallbackImg.src = '';
            previewFallbackImg.classList.add('d-none');
        }
        if (previewContainer) {
            previewContainer.classList.remove('d-none');
            previewContainer.innerHTML = '';
        }
        try {
            state.cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                /* make crop box larger by default */
                autoCropArea: 0.95,
                minCropBoxWidth: 180,
                minCropBoxHeight: 180,
                zoomOnWheel: true,
                background: false,
                responsive: true,
                preview: '.avatar-crop-preview'
            });
        } catch (error) {
            console.error('Gagal menginisialisasi cropper', error);
            activateFallback(state.pendingFile);
        }
    };

    const loadPendingFileIntoCropper = () => {
        const file = state.pendingFile;
        if (!file) {
            return;
        }

        if (!window.Cropper) {
            activateFallback(file);
            return;
        }

        clearPreviewBox();
        try {
            if (state.objectUrl) {
                URL.revokeObjectURL(state.objectUrl);
            }
            state.objectUrl = URL.createObjectURL(file);
            if (cropImage) {
                cropImage.src = state.objectUrl;
                cropImage.onload = () => {
                    if (cropImage) {
                        cropImage.onload = null;
                    }
                    initializeCropper();
                };
                cropImage.onerror = () => {
                    if (cropImage) {
                        cropImage.onerror = null;
                    }
                    activateFallback(file);
                };
            }
        } catch (error) {
            console.error('Gagal menyiapkan cropper', error);
            activateFallback(file);
        }
    };

    if (avatarInput) {
        avatarInput.addEventListener('change', (event) => {
            const file = event.target.files && event.target.files[0];
            if (!file) {
                return;
            }
            if (!file.type.startsWith('image/')) {
                event.target.value = '';
                window.alert('File harus berupa gambar (PNG, JPG, atau GIF).');
                return;
            }
            state.pendingFile = file;
            state.cropConfirmed = false;
            resetCropperState();
            try {
                modal.show();
            } catch (error) {
                console.error('Gagal menampilkan modal cropper', error);
                avatarInput.value = '';
                window.alert('Tidak dapat membuka pemotong gambar. Silakan coba lagi.');
            }
        });
    }

    if (cropButton) {
        cropButton.addEventListener('click', () => {
            const file = state.pendingFile || (avatarInput && avatarInput.files && avatarInput.files[0]);
            if (!file) {
                modal.hide();
                return;
            }

            if (!state.cropper) {
                const previewUrl = URL.createObjectURL(file);
                showPreview(previewUrl, true);
                state.initialPreviewSrc = previewUrl;
                state.initialPreviewIsObject = true;
                state.cropConfirmed = true;
                state.pendingFile = null;
                if (state.fallbackUrl) {
                    URL.revokeObjectURL(state.fallbackUrl);
                    state.fallbackUrl = '';
                }
                modal.hide();
                // notify user we applied image without cropping
                if (typeof showToast === 'function') showToast('Gambar diterapkan tanpa pemotongan', 'info');
                return;
            }

            state.cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingQuality: 'high'
            }).toBlob((blob) => {
                if (!blob) {
                    activateFallback(file);
                    return;
                }

                const croppedFile = new File([blob], `avatar-${Date.now()}.png`, { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                if (avatarInput) {
                    avatarInput.files = dataTransfer.files;
                }

                const previewUrl = URL.createObjectURL(blob);
                showPreview(previewUrl, true);
                state.initialPreviewSrc = previewUrl;
                state.initialPreviewIsObject = true;
                state.cropConfirmed = true;
                state.pendingFile = null;

                if (state.objectUrl) {
                    URL.revokeObjectURL(state.objectUrl);
                    state.objectUrl = '';
                }

                modal.hide();
                // success toast
                if (typeof showToast === 'function') showToast('Foto profil berhasil diperbarui', 'success');
            });
        });
    }

    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', () => {
            // Remove any backdrop elements
            document.querySelectorAll('.modal-backdrop').forEach(bd => bd.remove());
            loadPendingFileIntoCropper();
        });

        modalEl.addEventListener('click', (event) => {
            if (event.target === modalEl) {
                modal.hide();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            resetCropperState();
            if (!state.cropConfirmed) {
                if (avatarInput) {
                    avatarInput.value = '';
                }
                showPreview(state.initialPreviewSrc, state.initialPreviewIsObject);
            }
            state.cropConfirmed = false;
            state.pendingFile = null;
        });
    }

    // wire up toolbar controls (zoom/rotate/reset)
    const btnZoomIn = document.getElementById('btnZoomIn');
    const btnZoomOut = document.getElementById('btnZoomOut');
    const btnRotate = document.getElementById('btnRotate');
    const btnReset = document.getElementById('btnReset');

    if (btnZoomIn) {
        btnZoomIn.addEventListener('click', () => {
            if (state.cropper) state.cropper.zoom(0.1);
        });
    }
    if (btnZoomOut) {
        btnZoomOut.addEventListener('click', () => {
            if (state.cropper) state.cropper.zoom(-0.1);
        });
    }
    if (btnRotate) {
        btnRotate.addEventListener('click', () => {
            if (state.cropper) state.cropper.rotate(90);
        });
    }
    if (btnReset) {
        btnReset.addEventListener('click', () => {
            if (state.cropper) state.cropper.reset();
        });
    }

    if (removeButton) {
        removeButton.addEventListener('click', function() {
            if (avatarInput) {
                avatarInput.value = '';
            }
            resetCropperState();
            const defaultSrc = previewImage.getAttribute('data-default') || '';
            showPreview(defaultSrc, false);
            if (removeInput) {
                removeInput.value = '1';
            }
        });
    }

    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (avatarInput) {
                avatarInput.value = '';
            }
            resetCropperState();
            showPreview(state.initialPreviewSrc, state.initialPreviewIsObject);
            if (removeInput) {
                removeInput.value = '0';
            }
        });
    }

    syncPreviewVisibility();
})();
</script>

<script>
(function() {
    document.querySelectorAll('[data-toggle="password"]').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const targetId = toggle.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = toggle.querySelector('i');
            if (!input || !icon) {
                return;
            }
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        });
    });
})();
</script>

<script>
(function() {
    const passwordInput = document.getElementById('password');
    const indicator = document.getElementById('passwordStrength');
    if (!passwordInput || !indicator) {
        return;
    }

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        indicator.textContent = '';
        indicator.className = 'password-strength';

        if (!password) {
            return;
        }

        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        if (score <= 2) {
            indicator.classList.add('weak');
            indicator.textContent = 'Password lemah. Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol.';
        } else if (score === 3 || score === 4) {
            indicator.classList.add('medium');
            indicator.textContent = 'Password cukup, tambahkan variasi karakter agar lebih kuat.';
        } else {
            indicator.classList.add('strong');
            indicator.textContent = 'Password kuat.';
        }
    });
})();
</script>

<script>
(function() {
    const form = document.getElementById('userEditForm');
    const submitButton = document.getElementById('submitButton');
    if (!form || !submitButton) {
        return;
    }

    form.addEventListener('submit', function() {
        if (submitButton.disabled) {
            return;
        }
        submitButton.disabled = true;
        submitButton.dataset.original = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
})();
</script>
@endpush
