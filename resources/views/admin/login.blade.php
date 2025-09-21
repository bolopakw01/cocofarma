<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cocofarma Backoffice</title>
    <!-- Use Poppins for consistency with admin backoffice -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bolopa/back/css/admin-login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="left">
                <div class="login">
                    <img src="{{ asset('bolopa/back/images/gif/COCOFARMAbyLoopA.gif') }}" class="logo-image">
                </div>
                <div class="eula">Management System</div>
                <div class="admin-login">Admin Login</div>
            </div>
            <div class="right">
                <svg viewBox="0 0 320 300">
                    <defs>
                        <linearGradient inkscape:collect="always" id="linearGradient" x1="13" y1="193.49992"
                            x2="307" y2="193.49992" gradientUnits="userSpaceOnUse">
                            <stop style="stop-color:#ff00ff;" offset="0" id="stop876" />
                            <stop style="stop-color:#ff0000;" offset="1" id="stop878" />
                        </linearGradient>
                    </defs>
                    <path
                        d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
                </svg>
                <form method="POST" action="{{ route('backoffice.login') }}" id="loginForm" class="form">
                    @csrf
                    <label for="username">Username</label>
                    <div class="input-container">
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            autofocus>
                    </div>
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required style="padding-right:40px;">
                        <button type="button" id="togglePassword" class="password-toggle" tabindex="-1" aria-label="Show password" style="position:absolute;top:50%;right:10px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#6b7280;">
                            <img id="eyeIcon" src="{{ asset('bolopa/back/images/icon/el--eye-close.svg') }}" alt="Show password" style="width:20px;height:20px;">
                        </button>
                    </div>
                    <button type="submit" id="submit" class="submit-btn">
                        <img src="{{ asset('bolopa/back/images/icon/mingcute--safe-lock-fill.svg') }}" alt="Lock" style="width:20px;height:20px;filter:brightness(0) invert(1);">
                        <span style="color: white">Secure Login</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show/hide password logic
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        let passwordVisible = false;

        // Make sure toggle button is always visible
        togglePassword.style.display = 'block';
        togglePassword.style.visibility = 'visible';
        togglePassword.style.opacity = '1';

        togglePassword.addEventListener('mousedown', function(e) {
            e.preventDefault();
            passwordVisible = !passwordVisible;
            passwordInput.type = passwordVisible ? 'text' : 'password';
            eyeIcon.src = passwordVisible ? '{{ asset('bolopa/back/images/icon/el--eye-open.svg') }}' : '{{ asset('bolopa/back/images/icon/el--eye-close.svg') }}';
        });

        // Ensure toggle button remains visible
        passwordInput.addEventListener('focus', function() {
            togglePassword.style.display = 'block';
            togglePassword.style.visibility = 'visible';
            togglePassword.style.opacity = '1';
        });

        passwordInput.addEventListener('blur', function() {
            setTimeout(() => {
                togglePassword.style.display = 'block';
                togglePassword.style.visibility = 'visible';
                togglePassword.style.opacity = '1';
            }, 100);
        });

        // Prevent button from losing focus on click
        togglePassword.addEventListener('blur', function() {
            setTimeout(() => {
                togglePassword.style.display = 'block';
                togglePassword.style.visibility = 'visible';
                togglePassword.style.opacity = '1';
            }, 100);
        });
        // Check for session messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '<i class="fas fa-check-circle text-success"></i> Login Berhasil!',
                html: '<strong>{{ session('success') }}</strong><br><small>Anda akan diarahkan ke dashboard dalam <span id="countdown">3</span> detik...</small>',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-check-circle"></i> OK, Lanjutkan',
                confirmButtonColor: '#28a745',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    // Countdown timer
                    let countdown = 3;
                    const countdownElement = document.getElementById('countdown');
                    const timer = setInterval(() => {
                        countdown--;
                        if (countdownElement) {
                            countdownElement.textContent = countdown;
                        }
                        if (countdown <= 0) {
                            clearInterval(timer);
                        }
                    }, 1000);
                },
                willClose: () => {
                    window.location.href = '{{ route('backoffice.dashboard') }}';
                }
            }).then((result) => {
                // If user clicks OK button, redirect immediately
                if (result.isConfirmed) {
                    window.location.href = '{{ route('backoffice.dashboard') }}';
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: '<i class="fas fa-exclamation-triangle text-danger"></i> Login Gagal!',
                html: '<strong>{{ session('error') }}</strong><br><small>Silakan periksa kembali username dan password Anda.</small>',
                confirmButtonText: '<i class="fas fa-redo"></i> Coba Lagi',
                confirmButtonColor: '#dc3545',
                allowOutsideClick: false
            });
        @endif

        // SVG Animation
        var current = null;
        document.querySelector('#username').addEventListener('focus', function(e) {
            if (current) current.pause();
            current = anime({
                targets: 'path',
                strokeDashoffset: {
                    value: 0,
                    duration: 700,
                    easing: 'easeOutQuart'
                },
                strokeDasharray: {
                    value: '240 1386',
                    duration: 700,
                    easing: 'easeOutQuart'
                }
            });
        });
        document.querySelector('#password').addEventListener('focus', function(e) {
            if (current) current.pause();
            current = anime({
                targets: 'path',
                strokeDashoffset: {
                    value: -336,
                    duration: 700,
                    easing: 'easeOutQuart'
                },
                strokeDasharray: {
                    value: '240 1386',
                    duration: 700,
                    easing: 'easeOutQuart'
                }
            });
        });
        document.querySelector('#submit').addEventListener('focus', function(e) {
            if (current) current.pause();
            current = anime({
                targets: 'path',
                strokeDashoffset: {
                    value: -730,
                    duration: 700,
                    easing: 'easeOutQuart'
                },
                strokeDasharray: {
                    value: '530 1386',
                    duration: 700,
                    easing: 'easeOutQuart'
                }
            });
        });
    </script>
</body>

</html>
