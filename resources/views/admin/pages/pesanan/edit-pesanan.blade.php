@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Pesanan';
@endphp

@section('title', 'Edit Pesanan - Cocofarma')

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
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        overflow: hidden;
        margin-top: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--light-gray);
    }

    .page-header h1 {
        color: var(--dark);
        font-size: 1.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--gray);
    }

    .form-container {
        background: var(--light);
        border-radius: var(--border-radius);
        padding: 25px;
        margin-bottom: 20px;
    }

    .form-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid var(--light-gray);
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
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

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #3aafd9;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #c22c38;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--light-gray);
    }

    .error-message {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    /* Order Items Section */
    .order-items-section {
        margin-top: 30px;
    }

    .items-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .items-table th,
    .items-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--light-gray);
    }

    .items-table th {
        background: var(--light);
        font-weight: 600;
        color: var(--dark);
    }

    .items-table .product-select {
        width: 100%;
        padding: 8px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
    }

    .items-table .quantity-input,
    .items-table .price-input {
        width: 100px;
        padding: 8px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        text-align: right;
    }

    .items-table .subtotal {
        font-weight: 600;
        color: var(--success);
        text-align: right;
    }

    .total-section {
        background: var(--light);
        padding: 20px;
        border-radius: var(--border-radius);
        margin-top: 20px;
        text-align: right;
    }

    .total-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success);
        margin: 0;
    }

    .total-label {
        font-size: 0.9rem;
        color: var(--gray);
        margin-bottom: 10px;
    }

    .order-code {
        background: var(--primary);
        color: white;
        padding: 4px 8px;
        border-radius: var(--border-radius);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 10px;
        display: inline-block;
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-container {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            margin: 10px;
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .items-table {
            font-size: 0.85rem;
        }

        .items-table th,
        .items-table td {
            padding: 8px;
        }

        .quantity-input,
        .price-input {
            width: 80px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Pesanan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backoffice.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backoffice.pesanan.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item active">Edit Pesanan</li>
            </ol>
        </nav>
    </div>

    <div class="order-code">{{ $pesanan->kode_pesanan }}</div>

    <div class="form-container">
        <form action="{{ route('backoffice.pesanan.update', $pesanan->id) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3><i class="fas fa-user"></i> Informasi Pelanggan</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal_pesanan">Tanggal Pesanan <span style="color: var(--danger);">*</span></label>
                        <input type="date" id="tanggal_pesanan" name="tanggal_pesanan" value="{{ old('tanggal_pesanan', $pesanan->tanggal_pesanan->format('Y-m-d')) }}" required>
                        @error('tanggal_pesanan')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}" required>
                        @error('nama_pelanggan')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="no_telepon">No. Telepon <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pesanan->no_telepon) }}" required>
                        @error('no_telepon')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status Pesanan <span style="color: var(--danger);">*</span></label>
                        <select id="status" name="status" required>
                            <option value="pending" {{ old('status', $pesanan->status) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ old('status', $pesanan->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $pesanan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $pesanan->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="alamat">Alamat <span style="color: var(--danger);">*</span></label>
                        <textarea id="alamat" name="alamat" required>{{ old('alamat', $pesanan->alamat) }}</textarea>
                        @error('alamat')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section order-items-section">
                <div class="items-header">
                    <h3><i class="fas fa-list"></i> Item Pesanan</h3>
                    <button type="button" class="btn btn-success" id="addItemBtn">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>

                <table class="items-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Produk</th>
                            <th style="width: 15%;">Jumlah</th>
                            <th style="width: 20%;">Harga Satuan</th>
                            <th style="width: 20%;">Subtotal</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @foreach($pesanan->pesananItems as $index => $item)
                        <tr class="item-row">
                            <td>
                                <select name="items[{{ $index }}][produk_id]" class="product-select" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}" data-price="{{ $produk->harga_jual }}" {{ $item->produk_id == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }} ({{ $produk->satuan }})
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][jumlah]" class="quantity-input" value="{{ old('items.' . $index . '.jumlah', $item->jumlah) }}" min="0.01" step="0.01" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][harga_satuan]" class="price-input" value="{{ old('items.' . $index . '.harga_satuan', $item->harga_satuan) }}" min="0" step="0.01" required>
                            </td>
                            <td>
                                <span class="subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-section">
                    <div class="total-label">Total Pesanan</div>
                    <div class="total-amount" id="totalAmount">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('backoffice.pesanan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template for order item row -->
<template id="itemRowTemplate">
    <tr class="item-row">
        <td>
            <select name="items[INDEX][produk_id]" class="product-select" required>
                <option value="">Pilih Produk</option>
                @foreach($produks as $produk)
                <option value="{{ $produk->id }}" data-price="{{ $produk->harga_jual }}">
                    {{ $produk->nama_produk }} ({{ $produk->satuan }})
                </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[INDEX][jumlah]" class="quantity-input" min="0.01" step="0.01" required>
        </td>
        <td>
            <input type="number" name="items[INDEX][harga_satuan]" class="price-input" min="0" step="0.01" required>
        </td>
        <td>
            <span class="subtotal">Rp 0</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-item">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    let itemIndex = {{ count($pesanan->pesananItems) }};

    document.addEventListener('DOMContentLoaded', function() {
        // Calculate initial total
        calculateTotal();

        // Event listeners
        document.getElementById('addItemBtn').addEventListener('click', addItem);
        document.getElementById('itemsBody').addEventListener('change', handleItemChange);
        document.getElementById('itemsBody').addEventListener('click', handleRemoveItem);
        document.getElementById('orderForm').addEventListener('submit', validateForm);

        // Calculate subtotals for existing items
        document.querySelectorAll('.item-row').forEach(row => {
            calculateSubtotal(row);
        });
    });

    function addItem() {
        const template = document.getElementById('itemRowTemplate');
        const tbody = document.getElementById('itemsBody');
        const row = template.content.cloneNode(true);

        // Replace INDEX with actual index
        const html = row.innerHTML.replace(/INDEX/g, itemIndex);
        const tr = document.createElement('tr');
        tr.innerHTML = html;
        tr.classList.add('item-row');

        tbody.appendChild(tr);
        itemIndex++;
    }

    function handleItemChange(e) {
        const target = e.target;

        if (target.classList.contains('product-select')) {
            handleProductChange(target);
        } else if (target.classList.contains('quantity-input') || target.classList.contains('price-input')) {
            calculateSubtotal(target.closest('.item-row'));
            calculateTotal();
        }
    }

    function handleProductChange(select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        const row = select.closest('.item-row');
        const priceInput = row.querySelector('.price-input');

        priceInput.value = price;
        calculateSubtotal(row);
        calculateTotal();
    }

    function calculateSubtotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = quantity * price;
        const subtotalElement = row.querySelector('.subtotal');

        subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        subtotalElement.setAttribute('data-value', subtotal);
    }

    function calculateTotal() {
        const subtotals = document.querySelectorAll('.subtotal');
        let total = 0;

        subtotals.forEach(subtotal => {
            total += parseFloat(subtotal.getAttribute('data-value')) || 0;
        });

        document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function handleRemoveItem(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            const row = e.target.closest('.item-row');
            row.remove();
            calculateTotal();

            // Ensure at least one item remains
            if (document.querySelectorAll('.item-row').length === 0) {
                addItem();
            }
        }
    }

    function validateForm(e) {
        const items = document.querySelectorAll('.item-row');
        let isValid = true;

        if (items.length === 0) {
            alert('Minimal harus ada 1 item pesanan!');
            e.preventDefault();
            return false;
        }

        items.forEach((item, index) => {
            const productSelect = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const priceInput = item.querySelector('.price-input');

            if (!productSelect.value) {
                alert(`Item ${index + 1}: Pilih produk!`);
                isValid = false;
            }

            if (!quantityInput.value || quantityInput.value <= 0) {
                alert(`Item ${index + 1}: Masukkan jumlah yang valid!`);
                isValid = false;
            }

            if (!priceInput.value || priceInput.value < 0) {
                alert(`Item ${index + 1}: Masukkan harga yang valid!`);
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            return false;
        }
    }
</script>
@endsection