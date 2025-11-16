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
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i> Dashboard Metrics</h4>
                <small class="text-white-75">Atur target total pendapatan, total biaya, dan total laba.</small>
            </div>
            <a href="{{ route('backoffice.pengaturan.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Pengaturan
            </a>
        </div>
        <div class="card-body">
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
