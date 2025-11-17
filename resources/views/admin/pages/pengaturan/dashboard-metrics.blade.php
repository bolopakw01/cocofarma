@extends('admin.layouts.app')

@php
    $pageTitle = 'Dashboard Metrics';
    $breadcrumb = [
        ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')],
        ['title' => 'Pengaturan', 'url' => route('backoffice.pengaturan.index')],
        ['title' => 'Dashboard Metrics', 'active' => true]
    ];
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

    .goal-form h4 {
        margin-bottom: 20px;
        color: #495057;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .goal-form h4 i {
        color: #4299e1;
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
</style>

<div class="goals-container">
    <div class="goals-header">
        <h2><i class="fas fa-chart-line"></i> Dashboard Metrics</h2>
        <a href="{{ route('backoffice.pengaturan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('backoffice.pengaturan.dashboard-metrics.save') }}">
                @csrf
                <div class="mb-4">
                    <label for="total_pendapatan" class="form-label fw-semibold">TOTAL PENDAPATAN</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="total_pendapatan" id="total_pendapatan" class="form-control" placeholder="Masukkan target total pendapatan" value="{{ old('total_pendapatan', $metrics['total_pendapatan'] ?? '') }}">
                    </div>
                    @error('total_pendapatan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="total_biaya" class="form-label fw-semibold">TOTAL BIAYA</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="total_biaya" id="total_biaya" class="form-control" placeholder="Masukkan target total biaya" value="{{ old('total_biaya', $metrics['total_biaya'] ?? '') }}">
                    </div>
                    @error('total_biaya')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="total_laba" class="form-label fw-semibold">TOTAL LABA</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="total_laba" id="total_laba" class="form-control" placeholder="Masukkan target total laba" value="{{ old('total_laba', $metrics['total_laba'] ?? '') }}">
                    </div>
                    @error('total_laba')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Target
                    </button>
                    <a href="{{ route('backoffice.pengaturan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
