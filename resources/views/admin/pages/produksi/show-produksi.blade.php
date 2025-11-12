@extends('admin.layouts.app')

@php
	$pageTitle = 'Detail Produksi';
@endphp

@section('title', 'Detail Produksi - Cocofarma')

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

	/* switch-style container (kept separate so it doesn't interfere with header layout) */
	.transfer-switch-row {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	/* classic toggle switch */
	.switch {
		position: relative;
		display: inline-block;
		width: 58px;
		height: 34px;
	}

	.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		inset: 0;
		background-color: var(--light-gray);
		transition: 0.25s;
		border-radius: 34px;
		box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
	}

	.slider:before {
		content: "";
		position: absolute;
		height: 26px;
		width: 26px;
		left: 4px;
		top: 4px;
		background-color: white;
		transition: 0.25s;
		border-radius: 50%;
		box-shadow: 0 2px 6px rgba(0,0,0,0.08);
	}

	.switch input:checked + .slider {
		background-color: var(--primary);
	}

	.switch input:checked + .slider:before {
		transform: translateX(24px);
	}

	.transfer-option-item {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		padding: 12px 14px;
		border: 2px solid var(--light-gray);
		border-radius: var(--border-radius);
		background: white;
		transition: var(--transition);
		cursor: pointer;
		flex: 1;
		font-weight: 500;
	}

	.transfer-option-item:hover {
		border-color: var(--primary);
		background: rgba(67, 97, 238, 0.05);
		box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.12);
	}

	.transfer-option-item:has(input:checked) {
		border-color: var(--primary);
		background: rgba(67, 97, 238, 0.1);
		font-weight: 600;
	}

	.transfer-option-item input {
		accent-color: var(--primary);
	}

	.form-helper {
		display: block;
		margin-top: 6px;
		font-size: 0.8rem;
		color: var(--gray);
	}

	.transfer-alert {
		margin-bottom: 20px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		border-radius: var(--border-radius);
		padding: 16px 20px;
	}

	.transfer-alert-content {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}

	.transfer-alert-actions {
		flex-shrink: 0;
	}

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

	.page-subtitle {
		font-size: 0.95rem;
		color: var(--gray);
		margin-top: 6px;
	}

		.transfer-option-item {
			flex-direction: row;
		}

		.transfer-alert {
			flex-direction: column;
			align-items: stretch;
		}

		.transfer-alert-actions {
			width: 100%;
		}

		.transfer-alert-actions .btn {
			width: 100%;
			justify-content: center;
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

	.bahan-info {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.bahan-nama {
		font-weight: 500;
		color: var(--dark);
	}

	.bahan-detail {
		font-size: 0.8rem;
		color: var(--gray);
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

		.transfer-option-grid {
			flex-direction: column;
			gap: 10px;
		}

		.transfer-option-item {
			flex-direction: row;
		}

		.transfer-alert {
			flex-direction: column;
			align-items: stretch;
		}

		.transfer-alert-actions {
			width: 100%;
		}

		.transfer-alert-actions .btn {
			width: 100%;
			justify-content: center;
		}
	}
</style>

@php
	// Default: hold (0) for non-finished productions. If already finished, reflect actual transfer status.
	$transferDefault = old('transfer_ke_produk', $produksi->status === 'selesai'
		? ($produksi->status_transfer === 'held' ? '0' : '1')
		: '0');
@endphp

<div class="container">
	<div class="page-header">
		<div>
			<h1><i class="fas fa-tasks"></i> Update Produksi</h1>
			<div class="page-subtitle">Gunakan form ini untuk memperbarui status produksi, hasil akhir, dan bahan baku.</div>
		</div>
		<a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
			<i class="fas fa-arrow-left"></i> Kembali
		</a>
	</div>

	<div class="info-section">
		<h3><i class="fas fa-info-circle"></i> Ringkasan Produksi</h3>
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
				<span class="info-value">{{ optional($produksi->tanggal_produksi)->format('d/m/Y') ?? '-' }}</span>
			</div>
			<div class="info-item">
				<span class="info-label">Target Produksi</span>
				<span class="info-value">{{ number_format($produksi->jumlah_target, 0) }} {{ $produksi->produk->satuan ?? 'Unit' }}</span>
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

			<div class="info-item">
				<span class="info-label">Tanggal Transfer</span>
				<span class="info-value">{{ optional($produksi->tanggal_transfer)->format('d/m/Y H:i') ?? '-' }}</span>
			</div>
		</div>


	</div>

		@if($produksi->status === 'selesai' && $produksi->status_transfer === 'held')
		<div class="alert alert-warning transfer-alert">
			<div class="transfer-alert-content">
				<strong><i class="fas fa-warehouse"></i> Hasil produksi masih ditahan</strong>
				<span>Stok produk belum bertambah. Transfer sekarang jika ingin memindahkan hasil produksi ke stok operasional.</span>
			</div>
			<div class="transfer-alert-actions">
				<form id="transferHasilForm" action="{{ route('backoffice.produksi.transfer', $produksi) }}" method="POST">
					@csrf
					<button type="submit" class="btn btn-primary"><i class="fas fa-exchange-alt"></i> Transfer ke Produk</button>
				</form>
			</div>
		</div>
		@endif

	@if($produksi->produksiBahans->count() > 0)
	<div class="form-section">
		<h3><i class="fas fa-boxes"></i> Bahan Baku Terpakai</h3>
		<div class="bahan-baku-list">
			@foreach($produksi->produksiBahans as $bahan)
			<div class="bahan-item">
				<div class="bahan-info">
					<div class="bahan-nama">{{ $bahan->bahanBaku->nama_bahan ?? 'Bahan tidak ditemukan' }}</div>
					<div class="bahan-detail">
						Jumlah: {{ number_format($bahan->jumlah_digunakan, 0) }} {{ $bahan->bahanBaku->satuan ?? '' }} |
						Biaya: Rp {{ number_format($bahan->total_biaya ?? 0, 0, ',', '.') }}
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif

	<form action="{{ route('backoffice.produksi.update', $produksi->id) }}" method="POST" id="updateProduksiForm">
		@csrf
		@method('PUT')

		@if($errors->any())
		<div class="alert alert-danger">
			<h4><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</h4>
			<ul class="mb-0">
				@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		@if(session('success'))
		<div class="alert alert-success">
			<i class="fas fa-check-circle"></i> {{ session('success') }}
		</div>
		@endif

		<div class="form-section">
			<h3><i class="fas fa-clipboard-check"></i> Jalankan / Perbarui Produksi</h3>

			<div class="form-row">
				<div class="form-group">
					<label for="status">Status Produksi <span class="required">*</span></label>
					<select name="status" id="status" required>
						<option value="rencana" {{ old('status', $produksi->status) == 'rencana' ? 'selected' : '' }}>Rencana</option>
						<option value="proses" {{ old('status', $produksi->status) == 'proses' ? 'selected' : '' }}>Proses</option>
						<option value="selesai" {{ old('status', $produksi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
						<option value="gagal" {{ old('status', $produksi->status) == 'gagal' ? 'selected' : '' }}>Gagal</option>
					</select>
					@error('status')
						<span class="error-message">{{ $message }}</span>
					@enderror
				</div>

			</div>

			@if($produksi->status !== 'selesai' && $produksi->status !== 'gagal')
			<div class="form-row">
				<div class="form-group">
					<label for="jumlah_target">Target Produksi <span class="required">*</span></label>
					<div class="input-group">
						<input type="number" name="jumlah_target" id="jumlah_target"
							   value="{{ old('jumlah_target', number_format($produksi->jumlah_target, 0)) }}"
							   placeholder="0" min="0.01" step="0.01" required>
						<span class="input-group-text">{{ $produksi->produk->satuan ?? 'Unit' }}</span>
					</div>
					@error('jumlah_target')
						<span class="error-message">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
					<label for="estimasi_biaya">Estimasi Biaya</label>
					<input type="text" id="estimasi_biaya" name="estimasi_biaya" readonly placeholder="0" />
				</div>
			</div>

			<div class="form-group">
				<label for="catatan_produksi">Catatan</label>
				<textarea name="catatan_produksi" id="catatan_produksi" placeholder="Catatan untuk produksi">{{ old('catatan_produksi') }}</textarea>
				@error('catatan_produksi')
					<span class="error-message">{{ $message }}</span>
				@enderror
			</div>
			@endif

			<div class="form-row" id="jumlah-hasil-row" style="display: none;">
				<div class="form-group">
					<label for="jumlah_hasil">Jumlah Hasil Produksi <span class="required">*</span></label>
					<div class="input-group">
						<input type="number" name="jumlah_hasil" id="jumlah_hasil"
							   value="{{ old('jumlah_hasil', number_format($produksi->jumlah_hasil, 0)) }}"
							   placeholder="0" min="0" step="0.01">
						<span class="input-group-text">{{ $produksi->produk->satuan ?? 'Unit' }}</span>
					</div>
					@error('jumlah_hasil')
						<span class="error-message">{{ $message }}</span>
					@enderror
				</div>

				@if(!empty($grades))
				<div class="form-group">
					<label for="grade_kualitas">Grade Kualitas <span class="required">*</span></label>
					<select name="grade_kualitas" id="grade_kualitas">
						<option value="">Pilih Grade</option>
						@foreach($grades as $index => $grade)
							@php $gradeValue = chr(65 + $index); @endphp
							<option value="{{ $gradeValue }}" {{ old('grade_kualitas', $produksi->grade_kualitas) == $gradeValue ? 'selected' : '' }}>
								{{ $grade['name'] }} ({{ $grade['label'] }})
							</option>
						@endforeach
					</select>
					@error('grade_kualitas')
						<span class="error-message">{{ $message }}</span>
					@enderror
				</div>
				@else
				<div class="form-group">
					<label for="grade_kualitas">Grade Kualitas</label>
					<div class="alert alert-warning" style="padding: 10px; margin: 0; border-radius: 5px; background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404;">
						<i class="fas fa-exclamation-triangle"></i>
						Grade belum dikonfigurasi. Silakan <a href="{{ route('backoffice.pengaturan.grade') }}" target="_blank" style="color: #856404; text-decoration: underline;">atur grade produk</a> terlebih dahulu.
					</div>
					<input type="hidden" name="grade_kualitas" value="{{ $produksi->grade_kualitas }}">
				</div>
				@endif
			</div>

			<!-- transfer section (moved to its own card) -->
			<div class="form-section transfer-section" id="transfer-section" style="display: none;">
				<h3><i class="fas fa-exchange-alt"></i> Transfer Hasil Produksi</h3>
				<div style="display:flex;align-items:center;gap:16px;">
					<!-- hidden fallback value when unchecked -->
					<input type="hidden" name="transfer_ke_produk" value="0">
					<label class="switch" title="Pindahkan hasil produksi ke stok">
						<input type="checkbox" id="transferSwitch" name="transfer_ke_produk" value="1" {{ $transferDefault === '1' ? 'checked' : '' }}>
						<span class="slider"></span>
					</label>
					<div>
						<div id="transferStatusLabel" style="font-weight:600;">{{ $transferDefault === '1' ? 'Transfer aktif' : 'Tahan hasil' }}</div>
						<div class="form-helper">Aktifkan untuk memindahkan hasil produksi ke stok sekarang; non-aktif = tahan.</div>
					</div>
				</div>
				@error('transfer_ke_produk')
					<span class="error-message">{{ $message }}</span>
				@enderror
			</div>
		</div>

		@if($produksi->status !== 'selesai' && $produksi->status !== 'gagal')
		<div class="form-section">
			<h3><i class="fas fa-box-open"></i> Sesuaikan Bahan Baku</h3>

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
							{{ $bahanOption->nama_bahan }} (Stok: {{ $bahanOption->stok == floor($bahanOption->stok) ? number_format($bahanOption->stok, 0) : number_format($bahanOption->stok, 2) }} {{ $bahanOption->satuan }})
						</option>
						@endforeach
					</select>
					<input type="number" name="bahan_digunakan[{{ $index }}][jumlah]" class="bahan-jumlah"
						   placeholder="Jumlah" min="0.01" step="0.01" value="{{ number_format($bahan->jumlah_digunakan, 0) }}" required>
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
							{{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
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

		<div class="form-actions">
			<a href="{{ route('backoffice.produksi.index') }}" class="btn btn-secondary">
				<i class="fas fa-times"></i> Batal
			</a>
			<button type="submit" class="btn btn-primary">
				<i class="fas fa-save"></i> Simpan Perubahan
			</button>
		</div>
	</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const statusSelect = document.getElementById('status');
	const jumlahHasilRow = document.getElementById('jumlah-hasil-row');
	const jumlahHasilInput = document.getElementById('jumlah_hasil');
	const gradeKualitasSelect = document.getElementById('grade_kualitas');
	const transferSection = document.getElementById('transfer-section');
	const transferCheckbox = document.getElementById('transferSwitch');

	let bahanIndex = document.getElementById('bahan-baku-container') ?
		{{ count($produksi->produksiBahans) ?: 1 }} : 0;

	if (document.getElementById('bahan-baku-container')) {
		updateRemoveButtons();
	}

	window.addBahanItem = function() {
		const container = document.getElementById('bahan-baku-container');
		if (!container) return;

		const newItem = document.createElement('div');
		newItem.className = 'bahan-baku-item';
		newItem.setAttribute('data-index', bahanIndex);

		newItem.innerHTML = `
			<select name="bahan_digunakan[${bahanIndex}][bahan_baku_id]" class="bahan-select" required>
				<option value="">Pilih Bahan Baku</option>
				@foreach($bahanBakus as $bahan)
				<option value="{{ $bahan->id }}" data-harga="{{ $bahan->harga_per_satuan }}" data-satuan="{{ $bahan->satuan }}">
					{{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok == floor($bahan->stok) ? number_format($bahan->stok, 0) : number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
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

	window.removeBahanItem = function(button) {
		const item = button.closest('.bahan-baku-item');
		const container = document.getElementById('bahan-baku-container');
		if (!container) return;

		if (container.children.length > 1) {
			item.remove();
			updateRemoveButtons();
		}
	};

	function updateRemoveButtons() {
		const items = document.querySelectorAll('.bahan-baku-item');
		const removeButtons = document.querySelectorAll('.remove-bahan-btn');

		if (items.length === 1) {
			removeButtons.forEach(btn => btn.style.display = 'none');
		} else {
			removeButtons.forEach(btn => btn.style.display = 'block');
		}
	}

	function toggleJumlahHasilFields() {
			if (statusSelect.value === 'selesai') {
			jumlahHasilRow.style.display = 'flex';
			if (jumlahHasilInput) {
				jumlahHasilInput.setAttribute('required', 'required');
				jumlahHasilInput.removeAttribute('disabled');
			}
			if (gradeKualitasSelect) {
				gradeKualitasSelect.setAttribute('required', 'required');
				gradeKualitasSelect.removeAttribute('disabled');
			}
			if (transferSection) {
				transferSection.style.display = 'block';
			}
			// leave default checkbox state as set by server; no forced change here
		} else {
			jumlahHasilRow.style.display = 'none';
			if (jumlahHasilInput) {
				jumlahHasilInput.removeAttribute('required');
				jumlahHasilInput.setAttribute('disabled', 'disabled');
				jumlahHasilInput.value = '';
			}
			if (gradeKualitasSelect) {
				gradeKualitasSelect.removeAttribute('required');
				gradeKualitasSelect.setAttribute('disabled', 'disabled');
				gradeKualitasSelect.value = '';
			}
			if (transferSection) {
				transferSection.style.display = 'none';
			}
		}
	}

	toggleJumlahHasilFields();
	statusSelect.addEventListener('change', toggleJumlahHasilFields);

	// update label text when switch toggled
	if (transferCheckbox) {
		transferCheckbox.addEventListener('change', function() {
			const label = document.getElementById('transferStatusLabel');
			if (label) {
				label.textContent = this.checked ? 'Transfer aktif' : 'Tahan hasil';
			}
		});
	}

	document.getElementById('updateProduksiForm').addEventListener('submit', function(e) {
		if (statusSelect.value === 'selesai') {
			if (!jumlahHasilInput.value || parseFloat(jumlahHasilInput.value) <= 0) {
				e.preventDefault();
				alert('Jumlah hasil produksi harus diisi ketika status diset ke "Selesai".');
				jumlahHasilInput.focus();
				return false;
			}
			if (gradeKualitasSelect && !gradeKualitasSelect.value) {
				e.preventDefault();
				alert('Grade kualitas harus dipilih ketika status diset ke "Selesai".');
				gradeKualitasSelect.focus();
				return false;
			}
		}
	});

	function calculateEstimasiBiaya() {
		let totalBiaya = 0;

		document.querySelectorAll('.bahan-baku-item').forEach(item => {
			const select = item.querySelector('.bahan-select');
			const jumlahInput = item.querySelector('.bahan-jumlah');

			if (select && select.value && jumlahInput && jumlahInput.value) {
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

	document.addEventListener('change', function(e) {
		if (e.target.classList && (e.target.classList.contains('bahan-select') || e.target.classList.contains('bahan-jumlah'))) {
			calculateEstimasiBiaya();
		}
	});

	document.addEventListener('input', function(e) {
		if (e.target.classList && e.target.classList.contains('bahan-jumlah')) {
			calculateEstimasiBiaya();
		}
	});

	calculateEstimasiBiaya();

	const transferForm = document.getElementById('transferHasilForm');
	if (transferForm) {
		transferForm.addEventListener('submit', function(e) {
			const confirmTransfer = confirm('Pindahkan hasil produksi ini ke stok produk sekarang?');
			if (!confirmTransfer) {
				e.preventDefault();
			}
		});
	}
});
</script>
@endsection
