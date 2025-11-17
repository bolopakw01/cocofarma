@extends('admin.layouts.app')

@php
    $pageTitle = 'Performance Dashboard';
    $breadcrumb = [
        ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')],
        ['title' => 'Pengaturan', 'url' => route('backoffice.pengaturan.index')],
        ['title' => 'Performance Dashboard', 'active' => true]
    ];
    $formMetrics = old('metrics', $metrics ?? []);
@endphp

@section('content')
<style>
    .goals-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .goals-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f8f9fa;
        gap: 20px; /* Add gap between title and button */
    }

    .goals-header h2 {
        color: #2d3748;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1; /* Allow title to take available space */
        min-width: 0; /* Allow text to wrap if needed */
    }

    .goals-header h2 i {
        color: #4299e1;
    }

    .goals-header .btn-back {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .goals-header .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .goal-form {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .goal-form h4 i {
        color: #4299e1;
    }

    .metrics-toolbar {
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .template-section {
        flex: 1;
        min-width: 300px;
    }

    .actions-section {
        flex-shrink: 0;
    }

    .template-section label {
        color: #495057;
        font-weight: 600;
        margin-right: 8px;
    }

    .actions-section .btn {
        white-space: nowrap;
    }

    .form-info {
        margin-bottom: 25px;
        padding: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        border-left: 4px solid #4299e1;
    }

    .info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .info-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        background: #d1ecf1;
        color: #0c5460;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }

    .info-badge i {
        color: #17a2b8;
    }

    .info-content {
        font-size: 14px;
        color: #6c757d;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group label i {
        color: #6c757d;
        font-size: 14px;
    }

    .required {
        color: #dc3545;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .form-help {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #6c757d;
        font-style: italic;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        flex-wrap: wrap;
        gap: 15px;
    }

    .action-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        font-size: 13px;
        font-style: italic;
    }

    .action-info i {
        color: #ffc107;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #1e7e34;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #bd2130;
    }

    .goals-section {
        margin-top: 40px;
    }

    .goals-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .goals-header-info h4 {
        margin: 0;
        color: #495057;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .goals-header-info h4 i {
        color: #4299e1;
    }

    .goals-summary {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .goals-count {
        background: #e9ecef;
        color: #495057;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: 500;
    }

    .goals-help i {
        color: #6c757d;
        cursor: help;
        font-size: 16px;
    }

    .goal-item {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        transition: all 0.2s ease;
        gap: 20px;
    }

    .goal-item:hover {
        background: #f8f9fa;
        transform: translateX(2px);
    }

    .goal-item:last-child {
        border-bottom: none;
    }

    .goal-info {
        flex: 1;
        min-width: 0;
    }

    .goal-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .goal-title h5 {
        margin: 0;
        color: #495057;
        font-size: 16px;
        font-weight: 600;
        flex: 1;
        min-width: 0;
    }

    .goal-category-badge {
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .goal-details {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .goal-target {
        font-size: 14px;
        color: #495057;
    }

    .goal-category-desc {
        font-size: 12px;
        color: #6c757d;
    }

    .goal-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .empty-tips {
        margin-top: 15px;
        padding: 12px;
        background: #d1ecf1;
        border-radius: 6px;
        font-size: 13px;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .empty-state {
        text-align: center;
        padding: 50px 30px;
        color: #6c757d;
        background: white;
        border-radius: 8px;
        margin: 20px;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        display: block;
        color: #cbd5e0;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-info {
        font-size: 13px;
        color: #856404;
        line-height: 1.4;
    }

    .modal-info i {
        margin-right: 5px;
    }

    /* Small chart box for metric sparkline */
    .metric-chart-box {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 6px;
        padding: 8px;
    }
    .metric-chart {
        display: block;
        width: 100% !important;
        height: 60px !important;
    }
</style>

<div class="goals-container">
    <div class="goals-header">
        <h2><i class="fas fa-bolt"></i> Konfigurasi Performance Dashboard</h2>
        <a href="{{ route('backoffice.pengaturan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data yang Anda masukkan.</strong>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info d-flex align-items-start gap-3">
                <i class="fas fa-info-circle fa-lg mt-1"></i>
                <div>
                    <strong>Tips:</strong> Target dan rata-rata industri diisi dengan angka absolut (jumlah unit, batch, atau pesanan). Nilai aktual akan dihitung otomatis berdasarkan sumber data yang dipilih untuk setiap indikator.
                </div>
            </div>

            @if(!empty($liveMetrics))
            <div class="mb-4">
                <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-2">
                    <h5 class="mb-0">Snapshot Performa Saat Ini</h5>
                    <span class="badge bg-light text-dark">
                        Total Aktual Bulan Ini: <strong>{{ number_format($totalActual, 0, ',', '.') }}</strong>
                    </span>
                </div>
                <div class="row g-3">
                    @foreach($liveMetrics as $metric)
                    @php
                        $rawTarget = (float) ($metric['target'] ?? 0);
                        $targetValue = max($rawTarget, 0.0001);
                        $actualValue = (float) ($metric['actual'] ?? 0);
                        $progress = min(100, ($actualValue / $targetValue) * 100);
                        $difference = $actualValue - $rawTarget;
                        $isAhead = $difference >= 0;
                        $formatNumber = function($value) {
                            $value = (float) $value;
                            $decimals = floor($value) != $value ? 2 : 0;
                            return number_format($value, $decimals, ',', '.');
                        };
                    @endphp
                    <div class="col-12 col-md-6 col-xxl-4">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $metric['label'] }}</div>
                                    @if(!empty($metric['description']))
                                        <small class="text-muted">{{ $metric['description'] }}</small>
                                    @endif
                                </div>
                                <span class="badge {{ $isAhead ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $isAhead ? '+'. $formatNumber($difference) : $formatNumber($difference) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Aktual</span>
                                <span>{{ $formatNumber($actualValue) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $isAhead ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ number_format($progress, 2) }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-2">
                                <span>Target</span>
                                <span>{{ $formatNumber($rawTarget) }}</span>
                            </div>
                            <div class="mt-1 text-muted small">
                                Rata-rata Industri: {{ $formatNumber($metric['benchmark'] ?? 0) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="goal-form">
                <div class="metrics-toolbar d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div class="template-section d-flex flex-wrap gap-2 align-items-center">
                        <label for="metric-template-select" class="text-muted small mb-0 fw-semibold">
                            <i class="fas fa-magic me-1"></i> Template Cepat:
                        </label>
                        <select class="form-select form-select-sm" id="metric-template-select" style="min-width: 200px;">
                            <option value="">Pilih template indikator...</option>
                            @php
                                $usedKeys = array_map('strtolower', array_filter(array_column($formMetrics ?? [], 'key')));
                            @endphp
                            @foreach($defaultMetrics as $template)
                                @php
                                    $templateKey = strtolower($template['key']);
                                    $isUsed = in_array($templateKey, $usedKeys, true);
                                @endphp
                                <option value="{{ $template['key'] }}" {{ $isUsed ? 'disabled' : '' }} {{ $isUsed ? 'style="color: #6c757d; font-style: italic;"' : '' }}>
                                    {{ $template['label'] }} {{ $isUsed ? '(sudah digunakan)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-success" id="add-template-btn">
                            <i class="fas fa-plus me-1"></i> Tambah dari Template
                        </button>
                    </div>
                    <div class="actions-section d-flex flex-wrap gap-2 align-items-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggle-all-metrics" data-collapsed="false">
                            <i class="fas fa-eye-slash me-1"></i> Sembunyikan Semua
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear-all-metrics">
                            <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('backoffice.pengaturan.performance.save') }}" id="performance-form">
                @csrf
                <div id="metrics-wrapper" class="d-flex flex-column gap-3">
                    @foreach($formMetrics as $index => $metric)
                        @include('admin.pages.pengaturan.partials.performance-item', ['index' => $index, 'metric' => $metric])
                    @endforeach

                    <div id="empty-metrics-state" class="empty-metrics-state text-center py-4 px-3 {{ empty($formMetrics) ? '' : 'd-none' }}">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <i class="fas fa-chart-line fa-2x text-muted"></i>
                            <p class="mb-0 text-muted small">Belum ada indikator performance yang aktif. Gunakan tombol <strong>Tambah dari Template</strong> untuk mulai membuat indikator.</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <a href="{{ route('backoffice.pengaturan.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="metric-template">
    @include('admin.pages.pengaturan.partials.performance-item', ['index' => '__INDEX__', 'metric' => [
        'label' => '',
        'key' => '',
        'target' => 100,
        'benchmark' => 70,
        'description' => ''
    ]])
</template>
@endsection

@push('scripts')
<script>
// Performance Dashboard JavaScript - Version 2.1
document.addEventListener('DOMContentLoaded', function() {
(function(){
    const wrapper = document.getElementById('metrics-wrapper');
    const templateEl = document.getElementById('metric-template');
    const templateSelect = document.getElementById('metric-template-select');
    const addTemplateBtn = document.getElementById('add-template-btn');
    const toggleAllBtn = document.getElementById('toggle-all-metrics');
    const clearAllBtn = document.getElementById('clear-all-metrics');
    const emptyState = document.getElementById('empty-metrics-state');
    let metricIndex = wrapper.querySelectorAll('.performance-item').length;

    const slugify = (value) => {
        return value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '') || '';
    };

    const updateTemplateSelect = () => {
        const usedKeys = Array.from(wrapper.querySelectorAll('.performance-item')).map(card => {
            const keyInput = card.querySelector('input[name*="[key]"]');
            return keyInput ? (keyInput.value || '').trim().toLowerCase() : null;
        }).filter(key => key);

        templateSelect.querySelectorAll('option').forEach(option => {
            const value = (option.value || '').trim().toLowerCase();
            if (value) {
                const isUsed = usedKeys.includes(value);
                option.disabled = isUsed;
                option.textContent = option.textContent.replace(' (sudah digunakan)', '');
                if (isUsed) {
                    option.textContent += ' (sudah digunakan)';
                }
                option.style.color = isUsed ? '#6c757d' : '';
                option.style.fontStyle = isUsed ? 'italic' : '';
            }
        });
    };

    const updateEmptyState = () => {
        if (!emptyState) return;
        const hasMetrics = wrapper.querySelectorAll('.performance-item').length > 0;
        emptyState.classList.toggle('d-none', hasMetrics);
    };

    const renumberCards = () => {
        wrapper.querySelectorAll('.performance-item').forEach((card, idx) => {
            const badge = card.querySelector('.metric-order');
            if (badge) badge.textContent = idx + 1;
        });
    };

    const toggleCard = (card, forceCollapse = null) => {
        const isCollapsed = card.classList.contains('is-collapsed');
        const shouldCollapse = forceCollapse === null ? !isCollapsed : forceCollapse;
        if (shouldCollapse) {
            card.classList.add('is-collapsed');
            const icon = card.querySelector('.btn-collapse-metric i');
            if (icon) icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        } else {
            card.classList.remove('is-collapsed');
            const icon = card.querySelector('.btn-collapse-metric i');
            if (icon) icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    };

    const addMetricCard = (metricData = null) => {
        const indexMarker = metricIndex;
        let templateHTML = templateEl.innerHTML.replace(/__INDEX__/g, indexMarker);
        wrapper.insertAdjacentHTML('beforeend', templateHTML);
        const newCard = wrapper.querySelector('.performance-item:last-child');

        if (metricData) {
            Object.keys(metricData).forEach((key) => {
                const input = newCard.querySelector(`[name="metrics[${indexMarker}][${key}]"]`);
                if (!input) return;
                input.value = metricData[key] ?? '';
            });
        }

        attachEvents(newCard);
        // Render chart for the new card if applicable
        if (typeof initMetricCharts === 'function') initMetricCharts();
        metricIndex++;
        renumberCards();
        updateEmptyState();
        updateTemplateSelect();
        return newCard;
    };

    // Initialize mini charts for metrics using Chart.js when available.
    const initMetricCharts = () => {
        const canvases = Array.from(document.querySelectorAll('.metric-chart'));
        if (!canvases.length) return;

        const ensureChartJs = (cb) => {
            if (typeof Chart !== 'undefined') return cb();
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = cb;
            document.body.appendChild(s);
        };

        ensureChartJs(() => {
            canvases.forEach((canvas) => {
                // Avoid re-rendering if we've already created a chart instance
                if (canvas._chartInitialized) return;

                let values = [];
                try { values = JSON.parse(canvas.getAttribute('data-values') || '[]'); } catch(e) { values = []; }

                const ctx = canvas.getContext('2d');
                if (!values || values.length < 2) {
                    // draw a subtle placeholder background
                    ctx.fillStyle = '#f8fafc';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    return;
                }

                // build lightweight sparkline
                const labels = values.map((_, i) => i + 1);
                try {
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                borderColor: '#4299e1',
                                backgroundColor: 'rgba(66,153,225,0.12)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { x: { display: false }, y: { display: false } },
                            elements: { line: { borderWidth: 2 } }
                        }
                    });
                    canvas._chartInitialized = true;
                    canvas._chartInstance = chart;
                } catch (err) {
                    // ignore chart errors silently
                    console.warn('Chart render error', err);
                }
            });
        });
    };

    const attachEvents = (parent) => {
        parent.querySelectorAll('[data-auto-key="true"]').forEach((input) => {
            input.addEventListener('input', function(){
                // Auto-generate key from label (handled server-side)
            });
        });

        parent.querySelectorAll('.btn-remove-metric').forEach((button) => {
            button.addEventListener('click', function(){
                const card = this.closest('.performance-item');
                card.remove();
                renumberCards();
                updateEmptyState();
                updateTemplateSelect();
            });
        });

        parent.querySelectorAll('.btn-collapse-metric').forEach((button) => {
            button.addEventListener('click', function(){
                const card = this.closest('.performance-item');
                toggleCard(card);
            });
        });
    };

    attachEvents(wrapper);
    updateEmptyState();
    updateTemplateSelect();

    addTemplateBtn.addEventListener('click', function(){
        const selectedKey = templateSelect.value;
        if (!selectedKey) {
            Swal.fire('Pilih Template', 'Silakan pilih template indikator terlebih dahulu.', 'info');
            return;
        }

        // Check if selected template is already used
        const selectedOption = templateSelect.querySelector(`option[value="${selectedKey}"]`);
        if (selectedOption && selectedOption.disabled) {
            Swal.fire('Template Sudah Digunakan', 'Template indikator ini sudah digunakan. Pilih template lain atau edit indikator yang sudah ada.', 'warning');
            templateSelect.value = '';
            return;
        }

        const templatePayload = @json($defaultMetrics);
        const templateData = templatePayload.find(function(item){ return item.key === selectedKey; });
        if (!templateData) {
            Swal.fire('Template Tidak Ditemukan', 'Template yang Anda pilih tidak tersedia.', 'error');
            return;
        }

        addMetricCard(templateData);
        templateSelect.value = '';
    });

    clearAllBtn.addEventListener('click', function(){
        Swal.fire({
            title: 'Hapus Semua Indikator Performance?',
            text: 'Semua indikator performance akan dihapus dan grafik radar tidak akan ditampilkan di dashboard.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove all metric cards
                wrapper.querySelectorAll('.performance-item').forEach(card => card.remove());
                metricIndex = 0;
                updateEmptyState();
                updateTemplateSelect();

                // Submit form with empty metrics
                const form = document.getElementById('performance-form');
                form.submit();
            }
        });
    });

    toggleAllBtn.addEventListener('click', function(){
        const currentlyCollapsed = this.getAttribute('data-collapsed') === 'true';
        const newState = !currentlyCollapsed;

        wrapper.querySelectorAll('.performance-item').forEach((card) => {
            toggleCard(card, newState);
        });

        this.setAttribute('data-collapsed', newState ? 'true' : 'false');
        this.innerHTML = newState
            ? '<i class="fas fa-eye me-1"></i> Tampilkan Semua'
            : '<i class="fas fa-eye-slash me-1"></i> Sembunyikan Semua';
    });
})();
});
</script>
@endpush
