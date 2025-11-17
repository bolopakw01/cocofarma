@php
    $label = $metric['label'] ?? '';
    $target = $metric['target'] ?? 100;
    $benchmark = $metric['benchmark'] ?? 70;
    $description = $metric['description'] ?? '';
    $key = $metric['key'] ?? '';
    $displayIndex = '#';
    if (is_int($index)) {
        $displayIndex = $index + 1;
    } elseif (is_string($index) && ctype_digit($index)) {
        $displayIndex = ((int) $index) + 1;
    }
@endphp

<div class="performance-item card shadow-sm border-0" data-index="{{ $index }}" data-metric-index="{{ $index }}">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
        <div class="d-flex align-items-center gap-2">
            <span class="metric-order badge rounded-pill bg-primary-subtle text-primary fw-semibold" aria-label="Urutan indikator">{{ $displayIndex }}</span>
            <div class="d-flex flex-column">
                <span class="fw-semibold">Indikator Dashboard</span>
                <small class="text-muted">Masukkan nama, target, dan rata-rata industri.</small>
            </div>
        </div>
        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi indikator">
            <button type="button" class="btn btn-outline-secondary btn-collapse-metric" title="Sembunyikan/lihat detail">
                <i class="fas fa-chevron-up"></i>
            </button>
            <button type="button" class="btn btn-outline-danger btn-remove-metric" title="Hapus indikator">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="metric-section">
                    <span class="section-label text-uppercase text-muted small fw-semibold">Informasi Indikator</span>
                    <div class="row g-3 mt-1">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Indikator</label>
                            <input type="text" class="form-control" name="metrics[{{ $index }}][label]" value="{{ old("metrics.$index.label", $label) }}" placeholder="Contoh: Produksi Bulanan" data-auto-key="true" required>
                            <input type="hidden" name="metrics[{{ $index }}][key]" value="{{ old("metrics.$index.key", $key) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="metric-section">
                    <span class="section-label text-uppercase text-muted small fw-semibold">Target & Benchmark</span>
                    <div class="row g-3 mt-1">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Target (jumlah)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                                <input type="number" class="form-control" name="metrics[{{ $index }}][target]" min="0" step="0.01" value="{{ old("metrics.$index.target", $target) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Rata-rata Industri</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                <input type="number" class="form-control" name="metrics[{{ $index }}][benchmark]" min="0" step="0.01" value="{{ old("metrics.$index.benchmark", $benchmark) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="metric-section border-top pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Singkat</label>
                            <textarea class="form-control" rows="2" name="metrics[{{ $index }}][description]" placeholder="Tambahkan konteks singkat">{{ old("metrics.$index.description", $description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="metric-section mt-3">
                    <span class="section-label text-uppercase text-muted small fw-semibold">Ringkasan Grafik</span>
                    <div class="mt-2 metric-chart-box">
                        <canvas class="metric-chart w-100" height="60" data-values='@json($metric['values'] ?? [])'></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
