@extends('admin.layouts.app')

@php
    $pageTitle = 'Goals';
    $breadcrumb = [
        ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')],
        ['title' => 'Pengaturan', 'url' => route('backoffice.pengaturan.index')],
        ['title' => 'Goals', 'active' => true]
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
        <h2><i class="fas fa-bullseye"></i> Dashboard Goals Management</h2>
        <a href="{{ route('backoffice.pengaturan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form untuk menambah goal baru -->
    <div class="goal-form">
        <div class="form-info">
            <div class="info-header">
                <h4><i class="fas fa-plus"></i> Tambah Goal Baru</h4>
                <div class="info-badge">
                    <i class="fas fa-info-circle"></i>
                    <span>Goals akan ditampilkan di dashboard untuk tracking progress</span>
                </div>
            </div>
            <div class="info-content">
                <p><strong>Cara Kerja:</strong> Setiap goal akan menampilkan progress pencapaian target di dashboard utama. Goals membantu Anda memantau KPI penting bisnis seperti penjualan, produksi, dan inventori.</p>
            </div>
        </div>

        <form id="addGoalForm">
            <div class="form-group">
                <label for="goal_label">
                    <i class="fas fa-tag"></i> Nama Goal
                    <span class="required">*</span>
                </label>
                <input type="text" id="goal_label" name="label" class="form-control"
                       placeholder="Contoh: Total Penjualan Bulanan, Jumlah Produk Aktif, Target Produksi Harian"
                       required>
                <small class="form-help">Beri nama yang jelas dan deskriptif untuk goal Anda</small>
            </div>

            <div class="form-group">
                <label for="goal_category">
                    <i class="fas fa-list"></i> Kategori
                    <span class="required">*</span>
                </label>
                <select id="goal_category" name="key" class="form-control" required>
                    <option value="">Pilih kategori</option>
                    <option value="produk">📦 Produk - Total jumlah produk dalam katalog</option>
                    <option value="penjualan">💰 Penjualan - Total nilai penjualan dalam Rupiah</option>
                    <option value="bahan_baku">🏭 Bahan Baku - Total jenis bahan baku tersedia</option>
                    <option value="produksi">⚙️ Produksi - Total unit yang diproduksi</option>
                    <option value="stok">📊 Stok - Total stok produk tersedia</option>
                    <option value="user">👥 User - Total pengguna terdaftar</option>
                </select>
                <small class="text-muted">Kategori yang sudah digunakan tidak dapat dipilih</small>
            </div>

            <div class="form-group">
                <label for="goal_target">
                    <i class="fas fa-bullseye"></i> Target
                    <span class="required">*</span>
                </label>
                <input type="number" id="goal_target" name="target" class="form-control"
                       placeholder="Masukkan angka target (contoh: 1000000 untuk penjualan)"
                       min="0" required>
                <small class="form-help">Target harus dalam angka positif. Progress akan dihitung otomatis berdasarkan data real-time.</small>
            </div>

            <div class="form-group">
                <label for="goal_color">
                    <i class="fas fa-palette"></i> Warna (Opsional)
                </label>
                <input type="color" id="goal_color" name="color" class="form-control" value="#007bff">
                <small class="form-help">Warna untuk membedakan goal di dashboard. Default: Biru</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Goal
                </button>
                <div class="action-info">
                    <i class="fas fa-lightbulb"></i>
                    <span>Goal akan langsung muncul di dashboard setelah ditambahkan</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Daftar goals -->
    <div class="goals-section">
        <div class="goals-header-info">
            <h4><i class="fas fa-list"></i> Daftar Goals</h4>
            <div class="goals-summary">
                <span class="goals-count" id="goalsCount">{{ count($goals ?? []) }} goals aktif</span>
                <div class="goals-help">
                    <i class="fas fa-question-circle" title="Goals akan ditampilkan di dashboard dengan progress real-time"></i>
                </div>
            </div>
        </div>

        <div class="goals-list">
            <div id="goalsContainer">
                @if(isset($goals) && count($goals) > 0)
                    @foreach($goals as $index => $goal)
                        <div class="goal-item" data-index="{{ $index }}">
                            <div class="goal-info">
                                <div class="goal-title">
                                    <h5>{{ $goal['label'] ?? 'Unnamed Goal' }}</h5>
                                    <span class="goal-category-badge" style="background: {{ $goal['color'] ?? '#007bff' }};">
                                        {{ ucfirst(str_replace('_', ' ', $goal['key'] ?? 'unknown')) }}
                                    </span>
                                </div>
                                <div class="goal-details">
                                    <div class="goal-target">
                                        <strong>Target:</strong> {{ number_format($goal['target'] ?? 0) }}
                                    </div>
                                    <div class="goal-category-desc">
                                        @switch($goal['key'])
                                            @case('produk')
                                                <small>📦 Total produk dalam katalog</small>
                                                @break
                                            @case('penjualan')
                                                <small>💰 Total nilai penjualan bulan ini</small>
                                                @break
                                            @case('bahan_baku')
                                                <small>🏭 Total jenis bahan baku tersedia</small>
                                                @break
                                            @case('produksi')
                                                <small>⚙️ Total unit yang diproduksi bulan ini</small>
                                                @break
                                            @case('stok')
                                                <small>📊 Total stok produk tersedia</small>
                                                @break
                                            @case('user')
                                                <small>👥 Total pengguna terdaftar</small>
                                                @break
                                            @default
                                                <small>📋 Kategori tidak dikenal</small>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            <div class="goal-actions">
                                <button class="btn btn-primary btn-sm edit-goal" data-index="{{ $index }}"
                                        title="Edit goal ini">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-goal" data-index="{{ $index }}"
                                        title="Hapus goal ini">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-bullseye"></i>
                        <h4>Belum ada goals</h4>
                        <p>Gunakan form di atas untuk menambah goal pertama Anda.</p>
                        <div class="empty-tips">
                            <strong>Tips:</strong> Mulai dengan goal yang paling penting untuk bisnis Anda, seperti target penjualan bulanan atau jumlah produk aktif.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk edit goal -->
<div id="editGoalModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 550px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h4 style="margin: 0; color: #2d3748; font-size: 20px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: #4299e1;"></i> Edit Goal
            </h4>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 20px; color: #6c757d; cursor: pointer; padding: 5px;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-info" style="margin: 20px 0; padding: 12px; background: #f8f9fa; border-radius: 6px; border-left: 3px solid #ffc107;">
            <i class="fas fa-info-circle" style="color: #ffc107; margin-right: 8px;"></i>
            <strong>Edit Goal:</strong> Anda dapat mengubah nama, kategori, target, dan warna goal. Pastikan kategori yang dipilih belum digunakan goal lain.
        </div>

        <form id="editGoalForm">
            <input type="hidden" id="edit_index" name="index">

            <div class="form-group">
                <label for="edit_goal_label">
                    <i class="fas fa-tag"></i> Nama Goal
                    <span class="required">*</span>
                </label>
                <input type="text" id="edit_goal_label" name="label" class="form-control"
                       placeholder="Contoh: Total Penjualan Bulanan, Jumlah Produk Aktif"
                       required>
                <small class="form-help">Beri nama yang jelas dan deskriptif untuk goal Anda</small>
            </div>

            <div class="form-group">
                <label for="edit_goal_category">
                    <i class="fas fa-list"></i> Kategori
                    <span class="required">*</span>
                </label>
                <select id="edit_goal_category" name="key" class="form-control" required>
                    <option value="produk">Produk</option>
                    <option value="penjualan">Penjualan</option>
                    <option value="bahan_baku">Bahan Baku</option>
                    <option value="produksi">Produksi</option>
                    <option value="stok">Stok</option>
                    <option value="user">User</option>
                </select>
                <small class="form-help">Kategori yang sudah digunakan goal lain akan dinonaktifkan</small>
            </div>

            <div class="form-group">
                <label for="edit_goal_target">
                    <i class="fas fa-bullseye"></i> Target
                    <span class="required">*</span>
                </label>
                <input type="number" id="edit_goal_target" name="target" class="form-control"
                       placeholder="Masukkan angka target"
                       min="0" required>
                <small class="form-help">Target harus dalam angka positif</small>
            </div>

            <div class="form-group">
                <label for="edit_goal_color">
                    <i class="fas fa-palette"></i> Warna (Opsional)
                </label>
                <input type="color" id="edit_goal_color" name="color" class="form-control">
                <small class="form-help">Warna untuk membedakan goal di dashboard</small>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end; padding-top: 15px; border-top: 1px solid #e9ecef;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let goals = @json($goals ?? []);

function updateCategoryDropdowns() {
    const usedCategories = goals.map(goal => goal.key);
    const allCategories = [
        { value: 'produk', label: '📦 Produk - Total jumlah produk dalam katalog', shortLabel: 'Produk' },
        { value: 'penjualan', label: '💰 Penjualan - Total nilai penjualan dalam Rupiah', shortLabel: 'Penjualan' },
        { value: 'bahan_baku', label: '🏭 Bahan Baku - Total jenis bahan baku tersedia', shortLabel: 'Bahan Baku' },
        { value: 'produksi', label: '⚙️ Produksi - Total unit yang diproduksi', shortLabel: 'Produksi' },
        { value: 'stok', label: '📊 Stok - Total stok produk tersedia', shortLabel: 'Stok' },
        { value: 'user', label: '👥 User - Total pengguna terdaftar', shortLabel: 'User' }
    ];

    // Update add goal form dropdown
    const addSelect = document.getElementById('goal_category');
    addSelect.innerHTML = '<option value="">Pilih kategori</option>';
    allCategories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.value;
        option.textContent = cat.label;
        if (usedCategories.includes(cat.value)) {
            option.disabled = true;
        }
        addSelect.appendChild(option);
    });

    // Update edit modal dropdown
    const editSelect = document.getElementById('edit_goal_category');
    editSelect.innerHTML = '';
    allCategories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.value;
        option.textContent = cat.shortLabel;
        editSelect.appendChild(option);
    });
}

function renderGoals() {
    const container = document.getElementById('goalsContainer');
    const goalsCountElement = document.getElementById('goalsCount');

    // Update goals count
    if (goalsCountElement) {
        goalsCountElement.textContent = `${goals.length} goals aktif`;
    }

    if (goals.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-bullseye"></i>
                <h4>Belum ada goals</h4>
                <p>Gunakan form di atas untuk menambah goal pertama Anda.</p>
                <div class="empty-tips">
                    <strong>Tips:</strong> Mulai dengan goal yang paling penting untuk bisnis Anda, seperti target penjualan bulanan atau jumlah produk aktif.
                </div>
            </div>
        `;
        return;
    }

    container.innerHTML = goals.map((goal, index) => {
        const categoryDescriptions = {
            'produk': '📦 Total produk dalam katalog',
            'penjualan': '💰 Total nilai penjualan bulan ini',
            'bahan_baku': '🏭 Total jenis bahan baku tersedia',
            'produksi': '⚙️ Total unit yang diproduksi bulan ini',
            'stok': '📊 Total stok produk tersedia',
            'user': '👥 Total pengguna terdaftar'
        };

        const categoryDesc = categoryDescriptions[goal.key] || '📋 Kategori tidak dikenal';

        return `
        <div class="goal-item" data-index="${index}">
            <div class="goal-info">
                <div class="goal-title">
                    <h5>${goal.label || 'Unnamed Goal'}</h5>
                    <span class="goal-category-badge" style="background: ${goal.color || '#007bff'};">${goal.key ? goal.key.charAt(0).toUpperCase() + goal.key.slice(1).replace('_', ' ') : 'Unknown'}</span>
                </div>
                <div class="goal-details">
                    <div class="goal-target">
                        <strong>Target:</strong> ${goal.target ? parseInt(goal.target).toLocaleString('id-ID') : 0}
                    </div>
                    <div class="goal-category-desc">
                        <small>${categoryDesc}</small>
                    </div>
                </div>
            </div>
            <div class="goal-actions">
                <button class="btn btn-primary btn-sm edit-goal" data-index="${index}" title="Edit goal ini">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm delete-goal" data-index="${index}" title="Hapus goal ini">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    `}).join('');

    // Update category dropdowns after rendering goals
    updateCategoryDropdowns();

    // Re-attach event listeners
    attachEventListeners();
}

function attachEventListeners() {
    // Edit buttons
    document.querySelectorAll('.edit-goal').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            openEditModal(index);
        });
    });

    // Delete buttons
    document.querySelectorAll('.delete-goal').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            const goal = goals[index];

            Swal.fire({
                title: 'Hapus Goal?',
                text: `Apakah Anda yakin ingin menghapus goal "${goal.label}"?`,
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteGoal(index);
                    Swal.fire(
                        'Terhapus!',
                        'Goal telah berhasil dihapus.',
                        'success'
                    );
                }
            });
        });
    });
}

function openEditModal(index) {
    const goal = goals[index];
    if (!goal) return;

    // Store original category for this goal
    document.getElementById('edit_index').value = index;
    document.getElementById('edit_goal_label').value = goal.label || '';
    document.getElementById('edit_goal_target').value = goal.target || 0;
    document.getElementById('edit_goal_color').value = goal.color || '#007bff';

    // Update edit modal dropdown, allowing current goal's category
    const usedCategories = goals.map((g, i) => i !== index ? g.key : null).filter(key => key !== null);
    const allCategories = [
        { value: 'produk', label: 'Produk' },
        { value: 'penjualan', label: 'Penjualan' },
        { value: 'bahan_baku', label: 'Bahan Baku' },
        { value: 'produksi', label: 'Produksi' },
        { value: 'stok', label: 'Stok' },
        { value: 'user', label: 'User' }
    ];

    const editSelect = document.getElementById('edit_goal_category');
    editSelect.innerHTML = '';
    allCategories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.value;
        option.textContent = cat.label;
        // Disable only if category is used by other goals (not this one)
        if (usedCategories.includes(cat.value)) {
            option.disabled = true;
        }
        editSelect.appendChild(option);
    });

    // Set current value
    document.getElementById('edit_goal_category').value = goal.key || '';

    document.getElementById('editGoalModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editGoalModal').style.display = 'none';
}

function deleteGoal(index) {
    goals.splice(index, 1);
    saveGoals();
    renderGoals();
}

// Form submit handlers
document.getElementById('addGoalForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const selectedCategory = formData.get('key');

    // Check if category is already used
    const usedCategories = goals.map(goal => goal.key);
    if (usedCategories.includes(selectedCategory)) {
        Swal.fire({
            title: 'Kategori Sudah Digunakan!',
            text: 'Kategori ini sudah digunakan untuk goal lain. Pilih kategori yang berbeda.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const newGoal = {
        label: formData.get('label'),
        key: formData.get('key'),
        target: parseInt(formData.get('target')),
        color: formData.get('color') || '#007bff'
    };

    goals.push(newGoal);
    saveGoals();
    renderGoals();
    this.reset();

    Swal.fire({
        title: 'Berhasil!',
        text: 'Goal baru telah berhasil ditambahkan.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
});

document.getElementById('editGoalForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const index = parseInt(formData.get('index'));
    const selectedCategory = formData.get('key');

    // Check if category is already used by other goals (excluding current goal)
    const usedCategories = goals.map((goal, i) => i !== index ? goal.key : null).filter(key => key !== null);
    if (usedCategories.includes(selectedCategory)) {
        Swal.fire({
            title: 'Kategori Sudah Digunakan!',
            text: 'Kategori ini sudah digunakan untuk goal lain. Pilih kategori yang berbeda.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    goals[index] = {
        label: formData.get('label'),
        key: formData.get('key'),
        target: parseInt(formData.get('target')),
        color: formData.get('color') || '#007bff'
    };

    saveGoals();
    renderGoals();
    closeEditModal();

    Swal.fire({
        title: 'Berhasil!',
        text: 'Goal telah berhasil diperbarui.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
});

function saveGoals() {
    fetch('{{ route("backoffice.pengaturan.save-goals") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ goals: goals })
    })
    .then(response => {
        // Check if response is redirect (302) which indicates success
        if (response.redirected) {
            console.log('Goals saved successfully (redirected)');
            // Reload page to show updated goals from server and display success message
            window.location.reload();
            return { success: true };
        }

        // Try to parse as JSON for other responses
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, assume success for redirect responses
            return { success: response.ok };
        }
    })
    .then(data => {
        if (data && data.success) {
            console.log('Goals saved successfully');
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan goals.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menyimpan goals.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCategoryDropdowns();
    attachEventListeners();

    // Show success message from session if exists
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>

@endsection
