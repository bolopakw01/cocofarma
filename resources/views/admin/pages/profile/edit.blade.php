@extends('admin.layouts.app')

@section('pageTitle', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<style>
	:root {
		--profile-card-bg: #ffffff;
		--profile-accent: #0d6efd;
		--profile-muted: #6c757d;
	}

	.profile-page {
		display: flex;
		justify-content: center;
		padding: 24px 0;
	}

	.edit-profile-card {
		width: 100%;
		max-width: 960px;
		display: flex;
		flex-direction: row;
		border-radius: 14px;
		background: var(--profile-card-bg);
		box-shadow: 0 10px 30px rgba(16, 24, 40, 0.08);
		overflow: hidden;
	}

	.profile-card-left {
		width: 320px;
		padding: 28px 22px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 14px;
		background: linear-gradient(180deg, rgba(13, 110, 253, 0.06), rgba(13, 110, 253, 0.02));
	}

	.profile-card-right {
		flex: 1 1 auto;
		padding: 28px 28px;
	}

	.profile-avatar {
		width: 128px;
		height: 128px;
		border-radius: 50%;
		object-fit: cover;
		border: 4px solid #fff;
		box-shadow: 0 6px 18px rgba(13, 110, 253, 0.12);
		background: #e9eefc;
	}

	.avatar-fallback {
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 44px;
		font-weight: 600;
		color: #fff;
		background: linear-gradient(135deg, #6478ff, #00c6a7);
	}

	.profile-role-pill {
		padding: 6px 12px;
		border-radius: 999px;
		background: rgba(13, 110, 253, 0.12);
		font-size: 0.85rem;
		font-weight: 600;
		color: #1255d1;
	}

	.helper-text {
		color: var(--profile-muted);
		font-size: 0.9rem;
	}

	.profile-form .form-control:focus,
	.profile-form .form-select:focus {
		box-shadow: none;
		border-color: var(--profile-accent);
	}

	.profile-form .input-group-text {
		background: #fff;
	}

	/* Visual treatment for fields that are intentionally disabled */
	.disabled-field {
		opacity: 0.6;
	}

	.disabled-field .form-select,
	.disabled-field .form-control {
		cursor: not-allowed;
	}

	.profile-form .btn-save {
		min-width: 170px;
	}

	.profile-alert {
		max-width: 960px;
		margin: 0 auto 16px;
	}

	#avatarCropperModal {
		z-index: 1050 !important;
	}

	#avatarCropperModal .modal-dialog {
		max-width: 720px;
	}

	#avatarCropperModal .modal-content {
		z-index: 1051 !important;
		pointer-events: auto !important;
	}

	.cropper-wrapper {
		position: relative;
		/* increased height to give bigger drag/crop area */
		min-height: 480px;
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

	/* Backdrop style for this modal only (added/removed by JS) */
	.modal-backdrop.avatar-backdrop {
		background-color: rgba(2,6,23,0.55) !important;
		backdrop-filter: blur(3px);
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

	@media (max-width: 767.98px) {
		.edit-profile-card {
			flex-direction: column;
		}

		.profile-card-left {
			width: 100%;
			flex-direction: row;
			align-items: center;
			justify-content: space-between;
			padding: 18px;
		}

		.profile-card-right {
			padding: 18px;
		}

		.profile-avatar,
		.avatar-fallback {
			width: 96px;
			height: 96px;
		}

		.avatar-preview-frame {
			width: 140px;
			height: 140px;
		}
	}
</style>
@endpush

@section('content')

	@if($errors->any())
		<div class="alert alert-danger profile-alert" role="alert">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@php
		$initial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name ?? 'U', 0, 1));
		$roleLabel = $roleOptions[$user->role] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', $user->role));
	@endphp

	<form id="adminProfileForm" class="profile-form" action="{{ route('backoffice.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
		@csrf
		@method('PUT')

		<div class="profile-page">
			<div class="edit-profile-card">
				<div class="profile-card-left text-center text-md-start">
					<div class="position-relative">
						<img id="avatarPreview" src="{{ $avatarUrl }}" alt="Avatar" class="profile-avatar {{ $avatarUrl ? '' : 'd-none' }}">
						<div id="avatarInitial" class="profile-avatar avatar-fallback {{ $avatarUrl ? 'd-none' : '' }}">{{ $initial }}</div>
					</div>
					<div class="profile-role-pill" id="roleDisplay" data-role-label="{{ $roleLabel }}">{{ $roleLabel }}</div>
					<label class="btn btn-sm btn-outline-primary w-100" for="avatarInput">
						<i class="fa-solid fa-camera me-1"></i> Ganti Foto
					</label>
					<input type="file" class="form-control d-none" id="avatarInput" name="avatar" accept="image/*">
					<div class="helper-text">PNG, JPG, atau GIF maksimal 2MB.</div>
					<div class="mt-auto text-center w-100 helper-text">
						Terakhir diperbarui: <strong>{{ $lastUpdatedDiff }}</strong>
					</div>
				</div>

				<div class="profile-card-right">
					<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
						<h3 class="mb-0" style="font-weight:600;font-size:1.25rem;color:#0f172a;">
							<i class="fa-solid fa-user-pen me-2 text-primary"></i>Profil Pengguna
						</h3>
						<small class="helper-text">Kelola informasi akun Anda di sini.</small>
					</div>

					<div class="row g-3">
						<div class="col-12 col-md-6">
							<label for="fullName" class="form-label">Nama Lengkap</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fa-solid fa-user"></i></span>
								<input id="fullName" type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name', $user->name) }}" required>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="email" class="form-label">Email</label>
							<div class="input-group">
								<span class="input-group-text">@</span>
								<input id="email" type="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ old('email', $user->email) }}" required>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="phone" class="form-label">No. Telepon</label>
							<div class="input-group">
								<span class="input-group-text">+62</span>
								<input id="phone" type="tel" name="phone" class="form-control" placeholder="81234567890" value="{{ old('phone', $user->phone) }}">
							</div>
							<div class="form-text helper-text">Gunakan nomor aktif untuk verifikasi.</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="role" class="form-label">Role / Peran</label>
							<div class="input-group {{ $user->role !== 'super_admin' ? 'disabled-field' : '' }}">
								<span class="input-group-text"><i class="fa-solid fa-shield"></i></span>
								<select id="role" name="role" class="form-select" @if($user->role !== 'super_admin') disabled aria-disabled="true" tabindex="-1" @endif>
									@foreach($roleOptions as $value => $label)
										<option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
									@endforeach
								</select>
							</div>
							@if($user->role !== 'super_admin')
								<input type="hidden" name="role" value="{{ $user->role }}">
							@endif
							<div class="form-text helper-text">Peran hanya dapat diubah oleh Super Admin.</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="password" class="form-label">Kata Sandi Baru <small class="text-muted">(opsional)</small></label>
							<div class="input-group">
								<span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
								<input id="password" type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password" aria-describedby="togglePassword">
								<button id="togglePassword" class="btn btn-outline-secondary" type="button" title="Tampilkan kata sandi"><i class="fa-regular fa-eye"></i></button>
							</div>
							<div class="form-text helper-text">Minimal 8 karakter dengan kombinasi huruf dan angka.</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
								<input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
							</div>
						</div>

						<div class="col-12">
							<label for="address" class="form-label">Alamat</label>
							<textarea id="address" name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
						</div>
					</div>

					<div class="d-flex justify-content-end gap-2 mt-4">
						<a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Batal</a>
						<button type="submit" class="btn btn-primary btn-save">
							<i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="modal fade" id="avatarCropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
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
								<img id="avatarCropImage" class="d-none" alt="Pratinjau pemotongan">
								<div id="avatarCropperFallback" class="cropper-fallback d-none">
									<p class="mb-3">Tidak dapat memuat pemotong gambar secara interaktif. Anda masih dapat menggunakan gambar apa adanya.</p>
									<img id="avatarFallbackPreview" class="img-fluid rounded" alt="Pratinjau gambar">
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">
							<div class="crop-preview-panel">
								<div class="avatar-preview-frame">
									<div class="avatar-crop-preview"></div>
									<img id="avatarPreviewFallback" class="avatar-preview-fallback d-none" alt="Pratinjau hasil">
								</div>
								<p class="text-muted small mb-0 text-center">Pratinjau hasil akhir</p>
							</div>
						</div>
					</div>
					<p class="text-muted small mt-3 mb-0">Seret untuk menyesuaikan area pemotongan. Pastikan wajah berada di tengah.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary" id="cropSubmitButton">Simpan &amp; Terapkan</button>
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
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
	const fileInput = document.getElementById('avatarInput');
	const previewImg = document.getElementById('avatarPreview');
	const initialBadge = document.getElementById('avatarInitial');
	const modalEl = document.getElementById('avatarCropperModal');
	const cropImage = document.getElementById('avatarCropImage');
	const fallbackWrapper = document.getElementById('avatarCropperFallback');
	const fallbackImage = document.getElementById('avatarFallbackPreview');
	const cropButton = document.getElementById('cropSubmitButton');
	const previewContainer = document.querySelector('.avatar-crop-preview');
	const previewFallbackImg = document.getElementById('avatarPreviewFallback');
	const roleSelect = document.getElementById('role');
	const roleDisplay = document.getElementById('roleDisplay');
	const togglePassword = document.getElementById('togglePassword');
	const passwordInput = document.getElementById('password');

	if (!fileInput || !modalEl) {
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
		initialPreviewSrc: previewImg && previewImg.getAttribute('src') ? previewImg.getAttribute('src') : '',
		initialPreviewIsObject: previewImg && previewImg.getAttribute('src') ? previewImg.getAttribute('src').startsWith('blob:') : false,
		cropConfirmed: false,
		pendingFile: null
	};

	if (state.initialPreviewIsObject) {
		state.currentPreviewUrl = state.initialPreviewSrc;
	}

	const syncPreviewVisibility = () => {
		const hasImage = previewImg && previewImg.getAttribute('src');
		if (previewImg) {
			previewImg.classList.toggle('d-none', !hasImage);
		}
		if (initialBadge) {
			initialBadge.classList.toggle('d-none', !!hasImage);
		}
	};

	const showPreview = (url, isObjectUrl = false) => {
		if (state.currentPreviewUrl && state.currentPreviewUrl !== url) {
			URL.revokeObjectURL(state.currentPreviewUrl);
			state.currentPreviewUrl = '';
		}

		if (previewImg) {
			if (url) {
				previewImg.src = url;
			} else {
				previewImg.removeAttribute('src');
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

	// toolbar buttons
	const btnZoomIn = document.getElementById('btnZoomIn');
	const btnZoomOut = document.getElementById('btnZoomOut');
	const btnRotate = document.getElementById('btnRotate');
	const btnReset = document.getElementById('btnReset');


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
		cropImage.src = '';
		cropImage.classList.add('d-none');
		cropImage.onload = null;
		cropImage.onerror = null;
		fallbackImage.src = '';
		fallbackWrapper.classList.add('d-none');
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
		cropImage.src = '';
		cropImage.classList.add('d-none');
		cropImage.onload = null;
		cropImage.onerror = null;
		fallbackWrapper.classList.remove('d-none');
		if (state.fallbackUrl) {
			URL.revokeObjectURL(state.fallbackUrl);
		}
		state.fallbackUrl = URL.createObjectURL(file);
		fallbackImage.src = state.fallbackUrl;
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
		cropImage.classList.remove('d-none');
		fallbackWrapper.classList.add('d-none');
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
			cropImage.src = state.objectUrl;
			cropImage.onload = () => {
				cropImage.onload = null;
				initializeCropper();
			};
			cropImage.onerror = () => {
				cropImage.onerror = null;
				activateFallback(file);
			};
		} catch (error) {
			console.error('Gagal menyiapkan cropper', error);
			activateFallback(file);
		}
	};

	fileInput.addEventListener('change', (event) => {
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
			fileInput.value = '';
			window.alert('Tidak dapat membuka pemotong gambar. Silakan coba lagi.');
		}
	});

	cropButton.addEventListener('click', () => {
		const file = state.pendingFile || (fileInput.files && fileInput.files[0]);
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
			fileInput.files = dataTransfer.files;

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

	modalEl.addEventListener('shown.bs.modal', () => {
		loadPendingFileIntoCropper();

		// style backdrop specifically for this modal
		setTimeout(() => {
			const bd = document.querySelector('.modal-backdrop');
			if (bd) bd.classList.add('avatar-backdrop');
		}, 1);

		// focus first control for accessibility
		if (btnZoomIn) btnZoomIn.focus();
	});

	modalEl.addEventListener('click', (event) => {
		if (event.target === modalEl) {
			modal.hide();
		}
	});

	modalEl.addEventListener('hidden.bs.modal', () => {
		resetCropperState();
		if (!state.cropConfirmed) {
			fileInput.value = '';
			showPreview(state.initialPreviewSrc, state.initialPreviewIsObject);
		}
		state.cropConfirmed = false;
		state.pendingFile = null;

		// remove custom backdrop class
		const bd = document.querySelector('.modal-backdrop');
		if (bd) bd.classList.remove('avatar-backdrop');
	});

	if (togglePassword && passwordInput) {
		togglePassword.addEventListener('click', () => {
			const isHidden = passwordInput.type === 'password';
			passwordInput.type = isHidden ? 'text' : 'password';
			togglePassword.innerHTML = isHidden
				? '<i class="fa-regular fa-eye-slash"></i>'
				: '<i class="fa-regular fa-eye"></i>';
			togglePassword.setAttribute('title', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
		});
	}

	if (roleSelect && roleDisplay) {
		const syncRoleLabel = () => {
			const selectedOption = roleSelect.options[roleSelect.selectedIndex];
			const fallbackLabel = roleDisplay.dataset.roleLabel || '';
			roleDisplay.textContent = selectedOption ? selectedOption.text : fallbackLabel;
		};
		syncRoleLabel();
		roleSelect.addEventListener('change', syncRoleLabel);
	}

	// wire up toolbar controls (zoom/rotate/reset)
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

	syncPreviewVisibility();
});
</script>
@endpush

<!-- Small note: toasts use Bootstrap's JS. If toasts don't appear, ensure bootstrap.bundle.js is loaded. -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
	@if(session('status'))
		// show SweetAlert2 popup for server-side status messages
		try {
			Swal.fire({
				title: 'Profil berhasil diperbarui',
				text: {!! json_encode(session('status')) !!},
				icon: 'success',
				confirmButtonText: 'Tutup'
			});
		} catch (e) {
			console.warn('SweetAlert failed', e);
		}
	@endif
});
</script>
@endpush