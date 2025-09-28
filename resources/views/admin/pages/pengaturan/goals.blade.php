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
    }

    .goals-header h2 {
        color: #2d3748;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
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

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
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

    .goals-list {
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .goal-item {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        transition: all 0.2s ease;
    }

    .goal-item:hover {
        background: #f8f9fa;
        transform: translateX(2px);
    }

    .goal-item:last-child {
        border-bottom: none;
    }

    .goal-info h5 {
        margin: 0 0 5px 0;
        color: #495057;
    }

    .goal-info p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

    .goal-actions {
        display: flex;
        gap: 10px;
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

    .empty-state h4 {
        color: #4a5568;
        margin-bottom: 10px;
        font-size: 20px;
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
        <h4><i class="fas fa-plus"></i> Tambah Goal Baru</h4>
        <form id="addGoalForm">
            <div class="form-group">
                <label for="goal_label">Nama Goal</label>
                <input type="text" id="goal_label" name="label" class="form-control" placeholder="Masukkan nama goal" required>
            </div>

            <div class="form-group">
                <label for="goal_category">Kategori</label>
                <select id="goal_category" name="key" class="form-control" required>
                    <option value="">Pilih kategori</option>
                    <option value="produk">Produk</option>
                    <option value="penjualan">Penjualan</option>
                    <option value="bahan_baku">Bahan Baku</option>
                    <option value="produksi">Produksi</option>
                    <option value="stok">Stok</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="form-group">
                <label for="goal_target">Target</label>
                <input type="number" id="goal_target" name="target" class="form-control" placeholder="Masukkan target" min="0" required>
            </div>

            <div class="form-group">
                <label for="goal_color">Warna (Opsional)</label>
                <input type="color" id="goal_color" name="color" class="form-control" value="#007bff">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Goal
            </button>
        </form>
    </div>

    <!-- Daftar goals -->
    <div class="goals-list">
        <div id="goalsContainer">
            @if(isset($goals) && count($goals) > 0)
                @foreach($goals as $index => $goal)
                    <div class="goal-item" data-index="{{ $index }}">
                        <div class="goal-info">
                            <h5>{{ $goal['label'] ?? 'Unnamed Goal' }}</h5>
                            <p>
                                <strong>Kategori:</strong> {{ ucfirst($goal['key'] ?? 'unknown') }} |
                                <strong>Target:</strong> {{ number_format($goal['target'] ?? 0) }}
                                @if(isset($goal['color']))
                                    <span style="display: inline-block; width: 12px; height: 12px; background: {{ $goal['color'] }}; border-radius: 50%; margin-left: 10px; vertical-align: middle;"></span>
                                @endif
                            </p>
                        </div>
                        <div class="goal-actions">
                            <button class="btn btn-primary btn-sm edit-goal" data-index="{{ $index }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-goal" data-index="{{ $index }}">
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
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk edit goal -->
<div id="editGoalModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
        <h4 style="margin-bottom: 20px; color: #2d3748; font-size: 20px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-edit" style="color: #4299e1;"></i> Edit Goal
        </h4>
        <form id="editGoalForm">
            <input type="hidden" id="edit_index" name="index">

            <div class="form-group">
                <label for="edit_goal_label">Nama Goal</label>
                <input type="text" id="edit_goal_label" name="label" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="edit_goal_category">Kategori</label>
                <select id="edit_goal_category" name="key" class="form-control" required>
                    <option value="produk">Produk</option>
                    <option value="penjualan">Penjualan</option>
                    <option value="bahan_baku">Bahan Baku</option>
                    <option value="produksi">Produksi</option>
                    <option value="stok">Stok</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edit_goal_target">Target</label>
                <input type="number" id="edit_goal_target" name="target" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label for="edit_goal_color">Warna (Opsional)</label>
                <input type="color" id="edit_goal_color" name="color" class="form-control">
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
let goals = @json($goals ?? []);

function renderGoals() {
    const container = document.getElementById('goalsContainer');

    if (goals.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-bullseye"></i>
                <h4>Belum ada goals</h4>
                <p>Gunakan form di atas untuk menambah goal pertama Anda.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = goals.map((goal, index) => `
        <div class="goal-item" data-index="${index}">
            <div class="goal-info">
                <h5>${goal.label || 'Unnamed Goal'}</h5>
                <p>
                    <strong>Kategori:</strong> ${goal.key ? goal.key.charAt(0).toUpperCase() + goal.key.slice(1) : 'Unknown'} |
                    <strong>Target:</strong> ${goal.target ? parseInt(goal.target).toLocaleString('id-ID') : 0}
                    ${goal.color ? `<span style="display: inline-block; width: 12px; height: 12px; background: ${goal.color}; border-radius: 50%; margin-left: 10px; vertical-align: middle;"></span>` : ''}
                </p>
            </div>
            <div class="goal-actions">
                <button class="btn btn-primary btn-sm edit-goal" data-index="${index}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm delete-goal" data-index="${index}">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    `).join('');

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

    document.getElementById('edit_index').value = index;
    document.getElementById('edit_goal_label').value = goal.label || '';
    document.getElementById('edit_goal_category').value = goal.key || '';
    document.getElementById('edit_goal_target').value = goal.target || 0;
    document.getElementById('edit_goal_color').value = goal.color || '#007bff';

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
    attachEventListeners();
});
</script>

@endsection
