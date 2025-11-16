@extends('admin.layouts.app')

@php
	$pageTitle = 'Tambah User';
@endphp

@section('title', 'Tambah User - Cocofarma')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
<style>
	.user-create-wrapper {
		max-width: 1080px;
		margin: 0 auto;
	}

	.user-create-card {
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
		overflow: hidden;
	}

	.user-create-header {
		padding: 28px 32px 24px;
		background: #ffffff;
		border-bottom: 1px solid rgba(148, 163, 184, 0.18);
		display: flex;
		align-items: center;
		justify-content: space-between;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
	}

	.bolopa-tabel-header-title {
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 1.25rem;
		font-weight: 700;
		color: #0f172a;
	}

	.bolopa-tabel-header-actions {
		display: flex;
		gap: 12px;
	}

	.user-create-body {
		padding: 32px;
		display: flex;
		flex-direction: column;
		gap: 24px;
	}

	.user-create-sidebar,
	.user-create-main {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}

	.user-section {
		border: 1px solid rgba(226, 232, 240, 0.8);
		border-radius: 18px;
		padding: 24px;
		background: #f8fafc;
	}

	.user-section h2 {
		margin-bottom: 12px;
		font-size: 1.05rem;
		font-weight: 700;
		color: #0f172a;
	}

	.user-section p.section-hint {
		margin: 0 0 20px;
		font-size: 0.9rem;
		color: #64748b;
	}

	.avatar-upload {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 18px;
	}

	.avatar-preview {
		width: 110px;
		height: 110px;
		border-radius: 50%;
		border: 2px dashed rgba(148, 163, 184, 0.5);
		display: grid;
		place-items: center;
		background: #e0e7ff;
		color: #1d4ed8;
		font-size: 2.4rem;
		font-weight: 700;
		overflow: hidden;
	}

	.avatar-preview img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}

	.avatar-actions {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
		justify-content: center;
	}

	.form-grid {
		display: grid;
		gap: 18px;
	}

	.form-grid.two-columns {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}

	.form-label {
		font-weight: 600;
		font-size: 0.94rem;
		color: #0f172a;
		margin-bottom: 6px;
	}

	.form-control,
	.form-select,
	textarea.form-control {
		border-radius: 12px;
		border: 1px solid rgba(148, 163, 184, 0.6);
		padding: 10px 14px;
		font-size: 0.95rem;
		transition: border-color 0.2s ease, box-shadow 0.2s ease;
	}

	.form-control:focus,
	.form-select:focus,
	textarea.form-control:focus {
		border-color: #2563eb;
		box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
	}

	.status-toggle {
		display: flex;
		gap: 16px;
		align-items: center;
		padding: 14px;
		border-radius: 12px;
		background: rgba(37, 99, 235, 0.06);
	}

	.status-toggle label {
		margin: 0;
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: 600;
		cursor: pointer;
	}

	.avatar-preview.has-image {
		border-color: rgba(37, 99, 235, 0.35);
		background: #ffffff;
	}

	.avatar-preview.has-image img {
		display: block;
	}

	.avatar-preview-frame {
		width: 180px;
		height: 180px;
		border-radius: 50%;
		padding: 12px;
		border: 1px dashed rgba(148, 163, 184, 0.5);
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 12px;
		background: #ffffff;
	}

	.avatar-crop-preview {
		width: 156px;
		height: 156px;
		border-radius: 50%;
		overflow: hidden;
		background: #f1f5f9;
		border: 1px solid rgba(226, 232, 240, 0.8);
	}

	.avatar-preview-fallback {
		width: 156px;
		height: 156px;
		border-radius: 50%;
		overflow: hidden;
		object-fit: cover;
		border: 1px solid rgba(148, 163, 184, 0.45);
	}

	#cropperModal {
		z-index: 1060;
	}

	#cropperModal .modal-dialog {
		max-width: 820px;
		width: 92vw;
	}

	#cropperModal .modal-content {
		border-radius: 16px;
		overflow: hidden;
		box-shadow: 0 24px 80px rgba(15, 23, 42, 0.18);
	}

	.cropper-wrapper {
		position: relative;
		min-height: 360px;
		background: #f8fafc;
		border: 1px solid rgba(226, 232, 240, 0.7);
		border-radius: 16px;
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
		max-width: 100%;
		border-radius: 12px;
		border: 1px solid rgba(226, 232, 240, 0.8);
	}

	.cropper-controls .btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
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
		border: 1px dashed rgba(148, 163, 184, 0.5);
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
		color: #0f172a;
	}

	.profile-photo-guidelines {
		margin: 0;
		padding-left: 18px;
		font-size: 0.86rem;
		color: #64748b;
		display: grid;
		gap: 6px;
	}

	.avatar-note {
		font-size: 0.8rem;
		color: #64748b;
		line-height: 1.4;
	}

	.user-create-footer {
		padding: 24px 32px;
		display: flex;
		justify-content: flex-end;
		gap: 12px;
		border-top: 1px solid rgba(226, 232, 240, 0.9);
		background: #ffffff;
	}

	.password-input-wrap {
		position: relative;
	}

	.password-toggle-btn {
		position: absolute;
		right: 12px;
		top: 50%;
		transform: translateY(-50%);
		border: none;
		background: transparent;
		padding: 2px 6px;
		cursor: pointer;
		color: #475569;
	}

	.password-strength {
		margin-top: 6px;
		font-size: 0.85rem;
		font-weight: 500;
		color: #64748b;
	}

	.password-strength.weak { color: #dc2626; }
	.password-strength.medium { color: #d97706; }
	.password-strength.strong { color: #16a34a; }

	@media (max-width: 992px) {
		.user-create-body {
			grid-template-columns: 1fr;
			padding: 24px;
		}

		.user-create-footer {
			flex-direction: column-reverse;
			align-items: stretch;
		}

		.user-create-footer .btn {
			width: 100%;
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
		.user-create-header {
			padding: 20px;
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.bolopa-tabel-header-actions {
			align-self: flex-end;
		}

		.user-create-body {
			padding: 20px;
		}

		.user-section {
			padding: 18px;
		}

		.form-grid.two-columns {
			grid-template-columns: 1fr;
		}
	}
</style>
@endpush

@section('content')
<div class="user-create-wrapper">
	<div class="user-create-card">
		<div class="user-create-header">
			<div class="bolopa-tabel-header-title">
				<i class="fa-solid fa-user-plus" style="font-size: 28px; color: #2563eb;"></i>
				<span>Tambah User</span>
			</div>
			<div class="bolopa-tabel-header-actions">
				<a href="{{ route('backoffice.master-user.index') }}" class="btn btn-secondary">
					<i class="fa-solid fa-arrow-left"></i>
					Kembali
				</a>
			</div>
		</div>

		@if($errors->any())
			<div class="alert alert-danger m-0 rounded-0" role="alert">
				<strong>Terjadi kesalahan.</strong>
				<ul class="mb-0 mt-2">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form id="userCreateForm" action="{{ route('backoffice.master-user.store') }}" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="user-create-body">
				@php
					$oldAvatar = old('avatar_preview');
					$initialPreview = $oldAvatar ? asset('storage/' . $oldAvatar) : '';
					$hasInitialAvatar = !empty($oldAvatar);
				@endphp
				<div class="user-section">
					<h2>Foto Profil</h2>
					<p class="section-hint">Opsional. Format yang didukung: JPG, PNG, maksimal 2 MB.</p>

					<div class="profile-photo-layout">
						<div class="profile-photo-preview-block">
							<div class="avatar-upload">
								@php
									$iconStyle = $hasInitialAvatar ? 'display: none;' : '';
									$imageStyle = $hasInitialAvatar ? '' : 'display: none;';
								@endphp
								<div class="avatar-preview{{ $hasInitialAvatar ? ' has-image' : '' }}" id="avatarPreviewWrapper">
									<i class="fa-solid fa-user" style="font-size: 3rem; {{ $iconStyle }}"></i>
									<img
										src="{{ $hasInitialAvatar ? $initialPreview : '' }}"
										alt="Pratinjau avatar"
										id="avatarPreviewImage"
										data-default=""
										data-initial="{{ $hasInitialAvatar ? $initialPreview : '' }}"
										style="{{ $imageStyle }}">
								</div>
								<div class="profile-photo-buttons">
									<label class="btn btn-primary mb-0">
										Pilih Foto
										<input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none">
									</label>
									<div class="profile-photo-actions">
										<button type="button" class="btn btn-outline-secondary" id="avatarRemove" {{ $hasInitialAvatar ? '' : 'disabled' }}>Hapus</button>
										<button type="button" class="btn btn-outline-secondary" id="avatarReset" {{ $hasInitialAvatar ? '' : 'disabled' }}>Reset</button>
									</div>
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
						</div>
					</div>
				</div>

				<div class="user-section">
					<h2>Informasi Dasar</h2>
					<p class="section-hint">Informasi utama untuk identitas pengguna.</p>

					<div class="form-grid two-columns">
						<div>
							<label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
							<input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autocomplete="off">
						</div>
						<div>
							<label for="username" class="form-label">Username <span class="text-danger">*</span></label>
							<input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required autocomplete="off" placeholder="contoh: johndoe">
						</div>
						<div>
							<label for="email" class="form-label">Email <span class="text-danger">*</span></label>
							<input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="off">
						</div>
						<div>
							<label for="phone" class="form-label">Nomor Telepon</label>
							<input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" autocomplete="off" placeholder="08xxxxxxxxxx">
						</div>
					</div>

					<div class="mt-3">
						<label for="address" class="form-label">Alamat</label>
						<textarea id="address" name="address" rows="3" class="form-control" placeholder="Alamat lengkap atau keterangan lain">{{ old('address') }}</textarea>
					</div>
				</div>

				<div class="user-section">
					<h2>Role &amp; Status</h2>
					<p class="section-hint">Tentukan hak akses dan status pengguna.</p>

					<div class="form-grid two-columns">
						<div>
							<label for="role" class="form-label">Role <span class="text-danger">*</span></label>
							<select id="role" name="role" class="form-select" required>
								<option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role</option>
								@foreach($roles ?? [] as $role)
									@php
										$roleValue = is_object($role)
											? ($role->name ?? ($role->slug ?? ($role->value ?? '')))
											: (string) $role;
										$roleValue = trim($roleValue ?? '');
										$roleLabel = is_object($role) && isset($role->display_name)
											? $role->display_name
											: \Illuminate\Support\Str::title(str_replace('_', ' ', $roleValue));
									@endphp
									@if($roleValue !== '')
										<option value="{{ $roleValue }}" {{ old('role') === $roleValue ? 'selected' : '' }}>
											{{ $roleLabel }}
										</option>
									@endif
								@endforeach
							</select>
						</div>
						<div>
							<label class="form-label">Status</label>
							<div class="status-toggle">
								<label>
									<input type="radio" name="status" value="1" {{ old('status', '1') === '1' ? 'checked' : '' }}>
									Aktif
								</label>
								<label>
									<input type="radio" name="status" value="0" {{ old('status') === '0' ? 'checked' : '' }}>
									Non-Aktif
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="user-section">
					<h2>Keamanan Akun</h2>
					<p class="section-hint">Setel password awal. Pengguna dapat mengubahnya setelah login.</p>

					<div class="form-grid two-columns">
						<div>
							<label for="password" class="form-label">Password <span class="text-danger">*</span></label>
							<div class="password-input-wrap">
								<input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" minlength="8">
								<button type="button" class="password-toggle-btn" data-target="password" aria-label="Tampilkan password">
									<i class="fa-solid fa-eye"></i>
								</button>
							</div>
							<div id="passwordStrength" class="password-strength"></div>
						</div>
						<div>
							<label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
							<div class="password-input-wrap">
								<input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password" minlength="8">
								<button type="button" class="password-toggle-btn" data-target="password_confirmation" aria-label="Tampilkan password">
									<i class="fa-solid fa-eye"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>			<div class="user-create-footer">
				<a href="{{ route('backoffice.master-user.index') }}" class="btn btn-outline-secondary">Batal</a>
				<button type="submit" class="btn btn-primary" id="submitButton">
					<i class="fa-solid fa-floppy-disk me-2"></i>Simpan User
				</button>
			</div>
		</form>
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
	const modalEl = document.getElementById('cropperModal');
	const cropImage = document.getElementById('cropperImage');
	const fallbackWrapper = document.getElementById('cropperFallback');
	const fallbackImage = document.getElementById('cropperFallbackPreview');
	const cropButton = document.getElementById('cropperApply');
	const previewContainer = document.querySelector('.avatar-crop-preview');
	const previewFallbackImg = document.getElementById('cropperPreviewFallback');
	const toastSuccessEl = document.getElementById('cropToast');
	const toastSuccessBody = document.getElementById('cropToastBody');
	const toastErrorEl = document.getElementById('cropErrorToast');
	const toastErrorBody = document.getElementById('cropErrorToastBody');

	if (!previewImage || !modalEl) {
		return;
	}

	if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
		console.error('Bootstrap Modal JavaScript tidak ditemukan. Pastikan bootstrap.min.js dimuat.');
		return;
	}

	const defaultSrc = previewImage ? (previewImage.getAttribute('data-default') || '') : '';
	const initialAttr = previewImage ? (previewImage.getAttribute('data-initial') || defaultSrc) : defaultSrc;
	const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

	const state = {
		cropper: null,
		objectUrl: '',
		fallbackUrl: '',
		currentPreviewUrl: '',
		initialPreviewSrc: initialAttr,
		initialPreviewIsObject: initialAttr.startsWith('blob:'),
		cropConfirmed: false,
		pendingFile: null
	};

	if (state.initialPreviewIsObject) {
		state.currentPreviewUrl = initialAttr;
	}

	const showToast = (message, type = 'success') => {
		const el = type === 'success' ? toastSuccessEl : toastErrorEl;
		const body = type === 'success' ? toastSuccessBody : toastErrorBody;
		if (!el || !body) {
			return;
		}
		body.textContent = message;
		el.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-primary', 'd-none');
		if (type === 'success') {
			el.classList.add('bg-success');
		} else if (type === 'info') {
			el.classList.add('bg-primary');
		} else if (type === 'warning') {
			el.classList.add('bg-warning');
		} else {
			el.classList.add('bg-danger');
		}
		try {
			new bootstrap.Toast(el, { delay: 3500 }).show();
		} catch (error) {
			console.warn('Toast gagal ditampilkan', error);
		}
	};

	const updateActionState = () => {
		const img = document.getElementById('avatarPreviewImage');
		const currentSrc = img ? (img.getAttribute('src') || '') : '';
		const hasCustomImage = currentSrc && currentSrc !== defaultSrc && (!img || img.style.display !== 'none');
		if (removeButton) {
			removeButton.disabled = !hasCustomImage;
		}
		if (resetButton) {
			resetButton.disabled = currentSrc === state.initialPreviewSrc;
		}
		if (previewWrapper) {
			previewWrapper.classList.toggle('has-image', hasCustomImage);
		}
	};

	const showPreview = (url, isObjectUrl = false) => {
		if (state.currentPreviewUrl && state.currentPreviewUrl !== url) {
			URL.revokeObjectURL(state.currentPreviewUrl);
			state.currentPreviewUrl = '';
		}
		if (url) {
			// pastikan ada img
			let img = document.getElementById('avatarPreviewImage');
			if (!img) {
				img = document.createElement('img');
				img.id = 'avatarPreviewImage';
				img.alt = 'Pratinjau avatar';
				img.setAttribute('data-default', defaultSrc);
				img.setAttribute('data-initial', url);
				previewWrapper.appendChild(img);
				// sembunyikan icon jika ada
				const icon = previewWrapper.querySelector('i.fa-user');
			}
			img.src = url;
			img.style.display = 'block';
			const icon = previewWrapper.querySelector('i.fa-user');
			if (icon) {
				icon.style.display = 'none';
			}
		} else {
			// tampilkan icon, sembunyikan img
			const img = document.getElementById('avatarPreviewImage');
			if (img) {
				img.style.display = 'none';
				img.src = '';
			}
			const icon = previewWrapper.querySelector('i.fa-user');
			if (icon) {
				icon.style.display = '';
			}
		}
		if (url && isObjectUrl) {
			state.currentPreviewUrl = url;
		} else if (!url) {
			state.currentPreviewUrl = '';
		}
		updateActionState();
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
		showToast('Cropper tidak tersedia — menggunakan gambar asli', 'warning');
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
					cropImage.onload = null;
					initializeCropper();
				};
				cropImage.onerror = () => {
					cropImage.onerror = null;
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
			if (file.size > 2 * 1024 * 1024) {
				event.target.value = '';
				window.alert('Ukuran file maksimal 2 MB.');
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
				previewImage.setAttribute('data-initial', previewUrl);

				if (state.fallbackUrl) {
					URL.revokeObjectURL(state.fallbackUrl);
					state.fallbackUrl = '';
				}

				modal.hide();
				showToast('Gambar diterapkan tanpa pemotongan', 'info');
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
				previewImage.setAttribute('data-initial', previewUrl);

				if (state.objectUrl) {
					URL.revokeObjectURL(state.objectUrl);
					state.objectUrl = '';
				}

				modal.hide();
				showToast('Foto profil berhasil diperbarui', 'success');
			}, 'image/png');
		});
	}

	if (modalEl) {
		modalEl.addEventListener('shown.bs.modal', () => {
			modalEl.setAttribute('aria-hidden', 'false');
			document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());
			loadPendingFileIntoCropper();
		});

		modalEl.addEventListener('click', (event) => {
			if (event.target === modalEl) {
				modal.hide();
			}
		});

		modalEl.addEventListener('hidden.bs.modal', () => {
			modalEl.setAttribute('aria-hidden', 'true');
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

	const btnZoomIn = document.getElementById('btnZoomIn');
	const btnZoomOut = document.getElementById('btnZoomOut');
	const btnRotate = document.getElementById('btnRotate');
	const btnResetCropper = document.getElementById('btnReset');

	if (btnZoomIn) {
		btnZoomIn.addEventListener('click', () => {
			if (state.cropper) {
				state.cropper.zoom(0.1);
			}
		});
	}

	if (btnZoomOut) {
		btnZoomOut.addEventListener('click', () => {
			if (state.cropper) {
				state.cropper.zoom(-0.1);
			}
		});
	}

	if (btnRotate) {
		btnRotate.addEventListener('click', () => {
			if (state.cropper) {
				state.cropper.rotate(90);
			}
		});
	}

	if (btnResetCropper) {
		btnResetCropper.addEventListener('click', () => {
			if (state.cropper) {
				state.cropper.reset();
			}
		});
	}

	if (removeButton) {
		removeButton.addEventListener('click', () => {
			if (avatarInput) {
				avatarInput.value = '';
			}
			resetCropperState();
			showPreview('', false);
			state.initialPreviewSrc = '';
			state.initialPreviewIsObject = false;
			const img = document.getElementById('avatarPreviewImage');
			if (img) img.setAttribute('data-initial', '');
			showToast('Foto profil dikembalikan ke bawaan', 'info');
		});
	}

	if (resetButton) {
		resetButton.addEventListener('click', () => {
			showPreview(state.initialPreviewSrc, state.initialPreviewIsObject);
			updateActionState();
		});
	}

	updateActionState();

	const initialImg = document.getElementById('avatarPreviewImage');
	if (initialImg) {
		const hasSrc = initialImg.getAttribute('src');
		const icon = previewWrapper ? previewWrapper.querySelector('i.fa-user') : null;
		if (icon) {
			icon.style.display = hasSrc ? 'none' : '';
		}
		if (!hasSrc) {
			initialImg.style.display = 'none';
		}
	}
})();
</script>

<script>
(function() {
	document.querySelectorAll('.password-toggle-btn').forEach((button) => {
		const targetId = button.getAttribute('data-target');
		const icon = button.querySelector('i');

		button.addEventListener('click', () => {
			const input = document.getElementById(targetId);
			if (!input) {
				return;
			}

			const isPassword = input.type === 'password';
			input.type = isPassword ? 'text' : 'password';
			if (icon) {
				icon.classList.toggle('fa-eye');
				icon.classList.toggle('fa-eye-slash');
			}
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

	passwordInput.addEventListener('input', () => {
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
		} else if (score <= 4) {
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
	const form = document.getElementById('userCreateForm');
	const submitButton = document.getElementById('submitButton');
	if (!form || !submitButton) {
		return;
	}

	form.addEventListener('submit', () => {
		if (submitButton.disabled) {
			return;
		}
		submitButton.disabled = true;
		submitButton.dataset.original = submitButton.innerHTML;
		submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Menyimpan...';
	});
})();
</script>
@endpush