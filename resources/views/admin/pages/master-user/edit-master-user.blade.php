@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit User';
@endphp

@section('title', 'Edit User - Cocofarma')

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
        max-width: 800px;
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

    .password-strength {
        margin-top: 5px;
        font-size: 0.8rem;
    }

    .password-strength.weak { color: var(--danger); }
    .password-strength.medium { color: var(--warning); }
    .password-strength.strong { color: var(--success); }

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

    .input-group {
        position: relative;
    }

    .input-group-text {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        cursor: pointer;
        z-index: 5;
    }

    .password-toggle {
        cursor: pointer;
        user-select: none;
    }

    .password-note {
        font-size: 0.85rem;
        color: var(--gray);
        margin-top: 5px;
        font-style: italic;
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
    }
</style>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit User</h1>
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backoffice.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backoffice.master-user.index') }}">Master User</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav> --}}
    </div>

    <div class="form-container">
        <form action="{{ route('backoffice.master-user.update', $user->id) }}" method="POST" id="userForm">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3><i class="fas fa-user"></i> Informasi Dasar</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span style="color: var(--danger);">*</span></label>
                        <select id="role" name="role" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-lock"></i> Keamanan & Status</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="password-note">Kosongkan jika tidak ingin mengubah password</div>
                        <div class="password-strength" id="passwordStrength"></div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status <span style="color: var(--danger);">*</span></label>
                        <select id="status" name="status" required>
                            <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('backoffice.master-user.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Password toggle functionality
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('passwordStrength');

        if (password.length === 0) {
            strengthIndicator.textContent = '';
            return;
        }

        let strength = 0;
        let feedback = [];

        if (password.length >= 8) strength++;
        else feedback.push('Minimal 8 karakter');

        if (/[a-z]/.test(password)) strength++;
        else feedback.push('Huruf kecil');

        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('Huruf besar');

        if (/[0-9]/.test(password)) strength++;
        else feedback.push('Angka');

        if (/[^A-Za-z0-9]/.test(password)) strength++;
        else feedback.push('Karakter khusus');

        strengthIndicator.className = 'password-strength';

        if (strength < 3) {
            strengthIndicator.classList.add('weak');
            strengthIndicator.textContent = 'Lemah: ' + feedback.join(', ');
        } else if (strength < 4) {
            strengthIndicator.classList.add('medium');
            strengthIndicator.textContent = 'Sedang: Tambahkan ' + feedback.join(', ');
        } else {
            strengthIndicator.classList.add('strong');
            strengthIndicator.textContent = 'Kuat';
        }
    });

    // Form validation
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }

        if (password && password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            return false;
        }
    });

    // Store original values for comparison
    const oldUsername = '{{ old('username', $user->username) }}';
    const oldEmail = '{{ old('email', $user->email) }}';
</script>
@endsection