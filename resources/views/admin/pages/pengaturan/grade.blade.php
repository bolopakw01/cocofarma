@extends('admin.layouts.app')

@php
    $pageTitle = 'Pengaturan Grade';
    $breadcrumb = [
        ['title' => 'BackOffice', 'url' => route('backoffice.dashboard')],
        ['title' => 'Pengaturan', 'url' => route('backoffice.pengaturan.index')],
        ['title' => 'Grade', 'active' => true]
    ];
    use App\Models\Pengaturan;
@endphp

@section('title', 'Pengaturan Grade Produk - Cocofarma')

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
        <h2><i class="fas fa-star"></i> Pengaturan Grade Produk</h2>
        <a href="{{ route('backoffice.pengaturan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form untuk menambah grade baru -->
    <div class="goal-form">
        <h4><i class="fas fa-plus"></i> Tambah Grade Baru</h4>
        <form id="addGradeForm">
            <div class="form-group">
                <label for="grade_name">Nama Grade</label>
                <input type="text" id="grade_name" name="name" class="form-control" placeholder="Masukkan nama grade (contoh: Grade A, Premium, dll)" required>
            </div>

            <div class="form-group">
                <label for="grade_label">Label Grade</label>
                <input type="text" id="grade_label" name="label" class="form-control" placeholder="Masukkan label grade (contoh: Premium Quality, Standard, dll)" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Grade
            </button>
        </form>
    </div>

    <!-- Daftar grade -->
    <div class="goals-list">
        <div id="gradesContainer">
            @if(isset($grades) && count($grades) > 0)
                @foreach($grades as $index => $grade)
                    <div class="goal-item" data-index="{{ $index }}">
                        <div class="goal-info">
                            <h5>{{ $grade['name'] ?? 'Unnamed Grade' }}</h5>
                            <p>Label: <strong>{{ $grade['label'] ?? '' }}</strong></p>
                        </div>
                        <div class="goal-actions">
                            <button class="btn btn-primary btn-sm edit-grade" data-index="{{ $index }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-grade" data-index="{{ $index }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h4>Belum ada grade</h4>
                    <p>Gunakan form di atas untuk menambah grade pertama Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk edit grade -->
<div id="editGradeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
        <h4 style="margin-bottom: 20px; color: #2d3748; font-size: 20px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-edit" style="color: #4299e1;"></i> Edit Grade
        </h4>
        <form id="editGradeForm">
            <input type="hidden" id="edit_index" name="index">

            <div class="form-group">
                <label for="edit_grade_name">Nama Grade</label>
                <input type="text" id="edit_grade_name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="edit_grade_label">Label Grade</label>
                <input type="text" id="edit_grade_label" name="label" class="form-control" required>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let grades = @json($grades ?? []);

function renderGrades() {
    const container = document.getElementById('gradesContainer');

    if (grades.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-star"></i>
                <h4>Belum ada grade</h4>
                <p>Gunakan form di atas untuk menambah grade pertama Anda.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = grades.map((grade, index) => `
        <div class="goal-item" data-index="${index}">
            <div class="goal-info">
                <h5>${grade.name || 'Unnamed Grade'}</h5>
                <p>Label: <strong>${grade.label || ''}</strong></p>
            </div>
            <div class="goal-actions">
                <button class="btn btn-primary btn-sm edit-grade" data-index="${index}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm delete-grade" data-index="${index}">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    `).join('');

    // Re-attach event listeners
    attachEventListeners();
}

function attachEventListeners() {
    // Edit grade buttons
    document.querySelectorAll('.edit-grade').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            const grade = grades[index];

            document.getElementById('edit_index').value = index;
            document.getElementById('edit_grade_name').value = grade.name || '';
            document.getElementById('edit_grade_label').value = grade.label || '';

            document.getElementById('editGradeModal').style.display = 'block';
        });
    });

    // Delete grade buttons
    document.querySelectorAll('.delete-grade').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            const grade = grades[index];

            Swal.fire({
                title: 'Hapus Grade?',
                text: `Apakah Anda yakin ingin menghapus grade "${grade.name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteGrade(index);
                    Swal.fire(
                        'Terhapus!',
                        'Grade telah berhasil dihapus.',
                        'success'
                    );
                }
            });
        });
    });
}

function closeEditModal() {
    document.getElementById('editGradeModal').style.display = 'none';
}

function deleteGrade(index) {
    // Store original grades in case deletion fails
    const originalGrades = [...grades];

    // Remove the grade temporarily
    const deletedGrade = grades.splice(index, 1)[0];

    // Try to save, if it fails, restore the original array
    saveGrades()
        .then(success => {
            if (!success) {
                // Restore original grades if save failed
                grades.splice(0, grades.length, ...originalGrades);
                renderGrades();
            }
        })
        .catch(error => {
            // Restore original grades if save failed
            grades.splice(0, grades.length, ...originalGrades);
            renderGrades();
        });
}

// Form submit handlers
document.getElementById('addGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const newGrade = {
        name: formData.get('name'),
        label: formData.get('label')
    };

    grades.push(newGrade);
    saveGrades();
    renderGrades();
    this.reset();

    Swal.fire({
        title: 'Berhasil!',
        text: 'Grade baru telah berhasil ditambahkan.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
});

document.getElementById('editGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const index = parseInt(formData.get('index'));

    grades[index] = {
        name: formData.get('name'),
        label: formData.get('label')
    };

    saveGrades();
    renderGrades();
    closeEditModal();

    Swal.fire({
        title: 'Berhasil!',
        text: 'Grade telah berhasil diperbarui.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
});

function saveGrades() {
    return fetch('{{ route("backoffice.pengaturan.save-grades") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ grades: grades })
    })
    .then(response => {
        // Check if response is redirect (302) which indicates success
        if (response.redirected) {
            console.log('Grades saved successfully (redirected)');
            return true;
        }

        // Try to parse as JSON for other responses
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                if (data.success) {
                    console.log('Grades saved successfully');
                    return true;
                } else {
                    // Show specific error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Terjadi kesalahan saat menyimpan grades.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });
        } else {
            // If not JSON, assume success for redirect responses
            return response.ok;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menyimpan grades.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
});

// Show success message if form was submitted
@if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        timer: 3000,
        showConfirmButton: false
    });
@endif

// Show error message if form had errors
@if($errors->any())
    Swal.fire({
        title: 'Error!',
        text: 'Terjadi kesalahan saat menyimpan data.',
        icon: 'error'
    });
@endif
</script>
@endpush

@endsection