// Professional Admin Login JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const submitBtn = document.querySelector('.btn-login');

    // Loading state for submit button
    loginForm.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span>Signing In...</span>';
        submitBtn.disabled = true;
    });

    // Enhanced input interactions
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        // Focus enhancement
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });

        // Real-time validation feedback
        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.borderColor = '#3b82f6';
            } else {
                this.style.borderColor = '#d1d5db';
            }
        });
    });

    // Password visibility toggle
    const passwordGroup = passwordInput.parentElement;
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
    toggleBtn.className = 'password-toggle';
    toggleBtn.title = 'Toggle password visibility';

    passwordGroup.style.position = 'relative';
    passwordGroup.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
            toggleBtn.title = 'Hide password';
        } else {
            passwordInput.type = 'password';
            toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
            toggleBtn.title = 'Show password';
        }
    });

    // Auto-focus management
    if (usernameInput.value === '') {
        usernameInput.focus();
    } else if (passwordInput.value === '') {
        passwordInput.focus();
    }

    // Professional form validation
    loginForm.addEventListener('submit', function(e) {
        let isValid = true;
        const inputs = [usernameInput, passwordInput];

        inputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                input.style.borderColor = '#10b981';
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Reset button state if validation fails
            submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i><span>Sign In</span>';
            submitBtn.disabled = false;
        }
    });
});