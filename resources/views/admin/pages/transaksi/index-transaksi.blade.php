@extends('admin.layouts.app')

@php
	use Illuminate\Support\Str;

	$perPageSelection = (string) request('per_page', 10);
	$perPageSelection = $perPageSelection === '1000' ? 'all' : $perPageSelection;
@endphp

@section('title', 'Transaksi')

@section('content')
<x-admin.data-table id="transaksi-table">
	<x-slot name="header">
		<div class="bolopa-tabel-header-title">
			<x-admin.icon name="transaction" alt="Transaksi" size="28" />
			<span>Transaksi</span>
		</div>
	</x-slot>

	<x-slot name="controls">
		<div class="bolopa-tabel-left-controls">
			<div class="bolopa-tabel-entries-select">
				<label for="entriesSelect">Tampilkan</label>
				<select id="entriesSelect">
					<option value="10" {{ $perPageSelection == '10' ? 'selected' : '' }}>10</option>
					<option value="25" {{ $perPageSelection == '25' ? 'selected' : '' }}>25</option>
					<option value="50" {{ $perPageSelection == '50' ? 'selected' : '' }}>50</option>
					<option value="all" {{ $perPageSelection == 'all' ? 'selected' : '' }}>Semua</option>
				</select>
				<span>entri</span>
			</div>
		</div>

		<div class="bolopa-tabel-right-controls">
			<div class="bolopa-tabel-search-box">
				<x-admin.icon name="search" alt="Cari" size="16" />
				<input type="text" id="searchInput" placeholder="Cari transaksi..." value="{{ request('search') }}">
			</div>

			@if(Auth::check() && Auth::user()->role === 'super_admin')
			<button class="bolopa-tabel-btn bolopa-tabel-btn-success" id="btnExport" type="button">
				<x-admin.icon name="export" alt="Export" size="16" />
				<span>Export</span>
			</button>
			@endif
			@if(Auth::check() && Auth::user()->role === 'super_admin')
			<button class="bolopa-tabel-btn bolopa-tabel-btn-primary" id="btnPrint" type="button">
				<x-admin.icon name="print" alt="Print" size="16" />
				<span>Print</span>
			</button>
			@endif
		</div>
	</x-slot>

	<x-slot name="beforeTable">
	</x-slot>

	<x-slot name="table">
		<table class="bolopa-tabel" id="dataTable">
			<thead>
				<tr>
					<th data-sort="no" style="width: 5%;">
						No
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="kode" style="width: 14%;">
						Kode Transaksi
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="tanggal" style="width: 12%;">
						Tanggal
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="jenis" style="width: 12%;">
						Jenis
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="item" style="width: 12%;">
						Item
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="total" style="width: 13%;" class="bolopa-align-right">
						Total
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="status" style="width: 12%;">
						Status
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th data-sort="keterangan" style="width: 20%;">
						Keterangan
						<span class="bolopa-tabel-sort-wrap">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-up" alt="Sort ascending">
							<img src="{{ asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg') }}" class="bolopa-tabel-sort-icon bolopa-tabel-sort-down" alt="Sort descending">
						</span>
					</th>
					<th style="width: 12%;">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse($transaksis ?? [] as $index => $transaksi)
				@php
					$rowNumber = ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1;
					$itemsCount = $transaksi->transaksiItems->count();
					$statusClass = $transaksi->status === 'selesai'
						? 'bolopa-tabel-badge-success'
						: ($transaksi->status === 'pending' ? 'bolopa-tabel-badge-warning' : 'bolopa-tabel-badge-danger');
					$jenisLabel = $transaksi->jenis_transaksi_label ?? Str::title((string) $transaksi->jenis_transaksi);
					$statusLabel = $transaksi->status_label ?? Str::title((string) $transaksi->status);
				@endphp
				<tr data-search="{{ strtolower($transaksi->kode_transaksi . ' ' . ($transaksi->keterangan ?? '') . ' ' . $jenisLabel . ' ' . $statusLabel) }}">
					<td data-sort-value="{{ $rowNumber }}">{{ $rowNumber }}</td>
					<td data-sort-value="{{ strtolower($transaksi->kode_transaksi) }}">{{ $transaksi->kode_transaksi }}</td>
					<td data-sort-value="{{ optional($transaksi->tanggal_transaksi)->format('Y-m-d') }}">
						{{ optional($transaksi->tanggal_transaksi)->format('d/m/Y') ?? '-' }}
					</td>
					<td data-sort-value="{{ strtolower($jenisLabel) }}">
						<span class="bolopa-tabel-badge bolopa-tabel-badge-info">{{ $jenisLabel }}</span>
					</td>
					<td data-sort-value="{{ $itemsCount }}">
						{{ $itemsCount }} item
					</td>
					<td data-sort-value="{{ $transaksi->total }}" class="bolopa-align-right">
						Rp {{ number_format($transaksi->total ?? 0, 0, ',', '.') }}
					</td>
					<td data-sort-value="{{ strtolower($statusLabel) }}">
						<span class="bolopa-tabel-badge {{ $statusClass }}">{{ $statusLabel }}</span>
					</td>
					<td data-sort-value="{{ strtolower($transaksi->keterangan ?? '') }}">
						{{ $transaksi->keterangan ? Str::limit($transaksi->keterangan, 80) : '-' }}
					</td>
					<td class="bolopa-tabel-actions" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 16px 12px;">
						<a href="{{ route('backoffice.transaksi.show', $transaksi->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-info bolopa-tabel-btn-action" aria-label="Lihat {{ $transaksi->kode_transaksi }}">
							<x-admin.icon name="view" alt="Detail" size="16" />
						</a>
						<a href="{{ route('backoffice.transaksi.edit', $transaksi->id) }}" class="bolopa-tabel-btn bolopa-tabel-btn-warning bolopa-tabel-btn-action" aria-label="Edit {{ $transaksi->kode_transaksi }}">
							<x-admin.icon name="edit" alt="Edit" size="16" />
						</a>
						@if(Auth::check() && Auth::user()->role === 'super_admin')
						<button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-danger bolopa-tabel-btn-action"
							onclick="confirmDelete({{ $transaksi->id }}, '{{ $transaksi->kode_transaksi }}', '{{ route('backoffice.transaksi.destroy', $transaksi->id) }}')"
							aria-label="Hapus {{ $transaksi->kode_transaksi }}">
							<x-admin.icon name="delete" alt="Hapus" size="16" />
						</button>
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="9" class="bolopa-tabel-empty">
						<x-admin.icon name="transaction" alt="Tidak ada data" size="48" style="opacity:0.6;margin-bottom:12px;" />
						<br>
						Tidak ada data transaksi
					</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</x-slot>

	@if(isset($transaksis) && ($transaksis->hasPages() || $transaksis->total() > 0))
		<x-slot name="footer">
			@php
				$queryParams = [
					'per_page' => request('per_page'),
					'search' => request('search'),
				];
				$queryString = '';
				foreach ($queryParams as $key => $value) {
					if($value !== null && $value !== '') {
						$queryString .= '&' . $key . '=' . urlencode($value);
					}
				}
			@endphp
			<div class="bolopa-tabel-pagination">
				<div class="bolopa-tabel-pagination-info">
					@if($transaksis->total() > 0)
						Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() ?? 0 }} entri
					@else
						Tidak ada entri yang ditampilkan
					@endif
				</div>
				<div class="bolopa-tabel-pagination-buttons">
					@if($transaksis->hasPages())
						@if($transaksis->onFirstPage())
							<button type="button" disabled aria-label="Halaman sebelumnya">
								<x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
							</button>
						@else
							<a href="{{ $transaksis->previousPageUrl() . $queryString }}">
								<button type="button" aria-label="Halaman sebelumnya">
									<x-admin.icon name="prev" alt="Halaman sebelumnya" size="18" />
								</button>
							</a>
						@endif

						@foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
							@if($page == $transaksis->currentPage())
								<button type="button" class="bolopa-tabel-active" aria-current="page">{{ $page }}</button>
							@else
								<a href="{{ $url . $queryString }}">
									<button type="button">{{ $page }}</button>
								</a>
							@endif
						@endforeach

						@if($transaksis->hasMorePages())
							<a href="{{ $transaksis->nextPageUrl() . $queryString }}">
								<button type="button" aria-label="Halaman selanjutnya">
									<x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
								</button>
							</a>
						@else
							<button type="button" disabled aria-label="Halaman selanjutnya">
								<x-admin.icon name="next" alt="Halaman selanjutnya" size="18" />
							</button>
						@endif
					@elseif($transaksis->perPage() >= 1000 && $transaksis->total() > 0)
						<button type="button" class="bolopa-tabel-btn bolopa-tabel-btn-primary" onclick="resetPagination()">Kembali ke Pagination</button>
					@endif
				</div>
			</div>
		</x-slot>
	@endif
</x-admin.data-table>

<div class="bolopa-tabel-toast" id="transaksiToast"></div>
@endsection

@push('styles')
<style>
</style>
@endpush

@push('scripts')
<script src="{{ asset('bolopa/back/js/bolopa-table.js') }}"></script>
<script src="{{ asset('bolopa/back/js/bolopa-export-print.js') }}"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const tableApi = window.initBolopaTable({
			tableSelector: '#dataTable',
			entriesSelector: '#entriesSelect',
			searchInputSelector: '#searchInput',
			toastSelector: '#transaksiToast'
		});

		const entriesSelect = document.getElementById('entriesSelect');
		if (entriesSelect) {
			entriesSelect.addEventListener('change', function (event) {
				const value = event.target.value;
				const url = new URL(window.location.href);
				if (value === 'all') {
					url.searchParams.set('per_page', '1000');
				} else {
					url.searchParams.set('per_page', value);
				}
				url.searchParams.delete('page');
				window.location.href = url.toString();
			});
		}

		const notify = function (message, type) {
			if (tableApi && typeof tableApi.showToast === 'function') {
				tableApi.showToast(message, type);
			} else if (type === 'error') {
				console.error(message);
			}
		};

		window.initBolopaExportPrint({
			tableSelector: '#dataTable',
			exportButtonSelector: '#btnExport',
			printButtonSelector: '#btnPrint',
			filenamePrefix: 'transaksi-export',
			printedBy: '{{ auth()->user()->name ?? 'Administrator' }}',
			printBrandTitle: 'Cocofarma — Daftar Transaksi',
			printBrandSubtitle: 'Ringkasan data transaksi operasional',
			printNotes: 'Catatan: Kolom aksi dihilangkan saat cetak/export. Gunakan filter untuk mempersempit data.',
			totalLabel: 'Total Transaksi',
			notify: notify,
			messages: {
				exportSuccess: 'Data transaksi berhasil diekspor.',
				exportError: 'Gagal export data transaksi.',
				printInfo: 'Membuka tampilan print...',
				printError: 'Gagal membuka tampilan print.'
			}
		});
	});

	function submitDeleteForm(url) {
		const form = document.createElement('form');
		form.method = 'POST';
		form.action = url;

		const csrfToken = document.querySelector('meta[name="csrf-token"]');
		if (csrfToken) {
			const csrfInput = document.createElement('input');
			csrfInput.type = 'hidden';
			csrfInput.name = '_token';
			csrfInput.value = csrfToken.getAttribute('content');
			form.appendChild(csrfInput);
		}

		const methodInput = document.createElement('input');
		methodInput.type = 'hidden';
		methodInput.name = '_method';
		methodInput.value = 'DELETE';
		form.appendChild(methodInput);

		document.body.appendChild(form);
		form.submit();
	}

	window.confirmDelete = function (id, kode, url) {
		Swal.fire({
			title: 'Hapus Transaksi',
			html: `Apakah Anda yakin ingin menghapus transaksi <strong>${kode}</strong>?<br><small style="color:#6c757d;">Tindakan ini tidak dapat dibatalkan.</small>`,
			icon: 'warning',
			showCancelButton: true,
			reverseButtons: true,
			confirmButtonColor: '#e63946',
			cancelButtonColor: '#4361ee',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				submitDeleteForm(url);
			}
		});
	};

	function resetPagination() {
		const url = new URL('{{ route('backoffice.transaksi.index') }}', window.location.origin);
		['search', 'per_page', 'page'].forEach(param => url.searchParams.delete(param));
		window.location.href = url.toString();
	}
</script>
@endpush
