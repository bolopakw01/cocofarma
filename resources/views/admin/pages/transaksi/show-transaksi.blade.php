@extends('admin.layouts.app')

@section('pageTitle', 'Detail Transaksi')
@section('title', 'Detail Transaksi - Cocofarma')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Detail Transaksi - {{ $transaksi->kode_transaksi ?? $transaksi->nomor_transaksi }}</h5>
                    <a href="{{ route('backoffice.transaksi.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Tanggal</strong>
                            <div>{{ $transaksi->tanggal_transaksi }}</div>
                        </div>
                        <div class="col-md-3">
                            <strong>Jenis</strong>
                            <div>{{ $transaksi->jenis_transaksi ?? $transaksi->tipe_transaksi }}</div>
                        </div>
                        <div class="col-md-3">
                            <strong>Status</strong>
                            <div>{{ $transaksi->status ?? 'n/a' }}</div>
                        </div>
                        <div class="col-md-3">
                            <strong>Total</strong>
                            <div>{{ number_format($transaksi->total ?? $transaksi->total_amount ?? 0, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    <h6>Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Item</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi->transaksiItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_item ?? ($item->produk->nama_produk ?? $item->bahanBaku->nama_bahan ?? '-') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ number_format($item->harga_satuan,2,',','.') }}</td>
                                        <td>{{ number_format($item->subtotal,2,',','.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada item</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
