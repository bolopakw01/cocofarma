@extends('admin.layouts.app')

@section('pageTitle', 'Tambah Transaksi')

@section('title', 'Tambah Transaksi - Cocofarma')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>@yield('pageTitle')</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('backoffice.transaksi.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jenis Transaksi</label>
                                <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                                    <option value="penjualan">Penjualan</option>
                                    <option value="pembelian">Pembelian</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control">
                            </div>
                        </div>

                        <h6>Items</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Item</th>
                                        <th style="width:15%">Jumlah</th>
                                        <th style="width:20%">Harga Satuan</th>
                                        <th style="width:20%">Subtotal</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][item_id]" class="form-select item-select">
                                                @foreach($items as $it)
                                                    <option value="{{ $it->id }}" data-price="{{ $it->harga_jual ?? ($it->harga_per_satuan ?? 0) }}">{{ $it->nama_produk ?? $it->nama_bahan }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="items[0][jumlah]" class="form-control jumlah" min="1" step="1" value="1"></td>
                                        <td><input type="number" name="items[0][harga_satuan]" class="form-control harga" step="0.01" value="0"></td>
                                        <td><input type="text" name="items[0][subtotal]" class="form-control subtotal" readonly value="0"></td>
                                        <td><button type="button" class="btn btn-sm btn-danger btn-remove">-</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">Tambah Item</button>
                            </div>
                            <div class="text-end">
                                <strong>Total: </strong> <span id="grand-total">0</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary">Simpan Transaksi</button>
                            <a href="{{ route('backoffice.transaksi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function(){
        let idx = 1;

        function recalcRow($row){
            const qty = parseFloat($row.querySelector('.jumlah').value) || 0;
            const price = parseFloat($row.querySelector('.harga').value) || 0;
            const subtotal = qty * price;
            $row.querySelector('.subtotal').value = subtotal.toFixed(2);
            recalcTotal();
        }

        function recalcTotal(){
            let total = 0;
            document.querySelectorAll('#items-table .item-row').forEach(r => {
                total += parseFloat(r.querySelector('.subtotal').value) || 0;
            });
            document.getElementById('grand-total').innerText = new Intl.NumberFormat('id-ID', {minimumFractionDigits:2}).format(total);
        }

        document.getElementById('add-item').addEventListener('click', function(){
            const tbody = document.querySelector('#items-table tbody');
            const tr = document.createElement('tr');
            tr.classList.add('item-row');
            tr.innerHTML = `
                <td>
                    <select name="items[${idx}][item_id]" class="form-select item-select">
                        @foreach($items as $it)
                            <option value="{{ $it->id }}" data-price="{{ $it->harga_jual ?? ($it->harga_per_satuan ?? 0) }}">{{ $it->nama_produk ?? $it->nama_bahan }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[${idx}][jumlah]" class="form-control jumlah" min="1" step="1" value="1"></td>
                <td><input type="number" name="items[${idx}][harga_satuan]" class="form-control harga" step="0.01" value="0"></td>
                <td><input type="text" name="items[${idx}][subtotal]" class="form-control subtotal" readonly value="0"></td>
                <td><button type="button" class="btn btn-sm btn-danger btn-remove">-</button></td>
            `;
            tbody.appendChild(tr);
            idx++;
        });

        document.addEventListener('click', function(e){
            if(e.target && e.target.classList.contains('btn-remove')){
                const row = e.target.closest('.item-row');
                if(row) row.remove();
                recalcTotal();
            }
        });

        document.addEventListener('input', function(e){
            if(e.target && (e.target.classList.contains('jumlah') || e.target.classList.contains('harga'))){
                const row = e.target.closest('.item-row');
                recalcRow(row);
            }
        });

        // set default harga saat pilih item
        document.addEventListener('change', function(e){
            if(e.target && e.target.classList.contains('item-select')){
                const option = e.target.selectedOptions[0];
                const price = option ? option.dataset.price || 0 : 0;
                const row = e.target.closest('.item-row');
                if(row) row.querySelector('.harga').value = price;
                recalcRow(row);
            }
        });

        // initialize first row
        document.querySelectorAll('#items-table .item-row').forEach(r => {
            const sel = r.querySelector('.item-select');
            if(sel) sel.dispatchEvent(new Event('change'));
            recalcRow(r);
        });
    })();
</script>
@endpush

@endsection
