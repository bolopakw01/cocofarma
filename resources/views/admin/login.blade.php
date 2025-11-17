<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Cocofarma</title>

  <!-- Favicon Cocofarma -->
  <link rel="icon" type="image/svg+xml" href="/bolopa/back/images/icon/twemoji--coconut.svg">
  <link rel="apple-touch-icon" href="/bolopa/back/images/icon/twemoji--coconut.svg">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- AOS Animation -->
  {{-- <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet"> --}}

  <style>
    :root {
      --coco-dark: #8B5A2B;
      --coconut-brown: #8B5A2B;
      --coconut-light: #A67C52;
      --palm-green: #2d6a4f;
      --palm-light: #40916c;
      --cream: #F5F5F5;
      --white: #FFFFFF;
      --gray: #6b7280;
      --border: #e5e7eb;
      --shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.75)),
                  url('https://images.unsplash.com/photo-1599599810694-b5b37304c972?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
      overflow: hidden;
      color: white;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at center, rgba(45, 106, 79, 0.25), transparent 70%);
      z-index: 1;
    }

    .container {
      position: relative;
      z-index: 2;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    /* Login Card */
    .login-card {
      background: white;
      color: #1f2937;
      padding: 2.5rem 2rem;
      border-radius: 1.5rem;
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 420px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .coconut-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1rem;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
      animation: float 3s ease-in-out infinite;
    }

    .login-card h1 {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--palm-green);
      margin-bottom: 0.5rem;
    }

    .login-card p {
      color: var(--gray);
      font-size: 0.95rem;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.25rem;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-weight: 500;
      color: #374151;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
      font-size: 1.1rem;
      z-index: 1;
    }

    .input-wrapper input {
      width: 100%;
      padding: 0.9rem 1rem 0.9rem 2.8rem;
      border: 1.5px solid var(--border);
      border-radius: 0.75rem;
      font-size: 1rem;
      background: #fdfdfb;
      transition: all 0.3s ease;
    }

    .input-wrapper input:focus {
      outline: none;
      border-color: var(--palm-green);
      background: white;
      box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.15);
    }

    .btn-login {
      background: var(--palm-green);
      color: white;
      padding: 0.9rem;
      border: none;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1.1rem;
      width: 100%;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
      box-shadow: 0 6px 15px rgba(45, 106, 79, 0.3);
    }

    .btn-login:hover {
      background: var(--palm-light);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(45, 106, 79, 0.35);
    }

    .register-link {
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: var(--gray);
    }

    .register-link a {
      color: var(--palm-green);
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    /* Floating Animation */
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-card {
        padding: 2rem 1.5rem;
      }
      .coconut-icon {
        width: 70px;
        height: 70px;
      }
      .login-card h1 {
        font-size: 1.5rem;
      }
    }
    /* Prevent layout shift when SweetAlert modal opens */
    html.swal2-shown, body.swal2-shown {
      padding-right: 0 !important;
      padding-bottom: 0 !important;
      margin-bottom: 0 !important;
      overflow: visible !important;
    }
    .swal2-container {
      z-index: 99999 !important;
    }
  </style>
</head>
<body>

  <div class="hero-overlay"></div>

  <div class="container">
    <div class="login-card" data-aos="fade-up" data-aos-duration="800">
      <!-- Ikon Kelapa dari Homepage -->
    <img src="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}" alt="Cocofarma" class="coconut-icon">
      <h1>Selamat Datang</h1>
      <p>Masuk ke akun Cocofarma Anda</p>

      <form method="POST" action="{{ route('backoffice.login') }}">
        @csrf

        {{-- Flash messages will be shown as SweetAlert popups --}}

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Username atau email">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" required placeholder="••••••">
          </div>
        </div>

        <button type="submit" class="btn-login">
          Login
        </button>
      </form>

      <!-- <div class="register-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
      </div> -->
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true,
      offset: 50
    });

    // Parallax halus (disabled to prevent layout interference)
    // window.addEventListener('scroll', () => {
    //   const scrolled = window.pageYOffset;
    //   document.body.style.backgroundPositionY = `${scrolled * 0.4}px`;
    // });
    
    // Show SweetAlert modal (blocking popup) for flash messages
    (function(){
      try {
        var successMsg = {!! json_encode(session('success') ?? '') !!};
        var errorMsg = {!! json_encode(session('error') ?? '') !!};
        var firstError = {!! json_encode($errors->first() ?? '') !!};

        const showModal = (icon, title, message) => {
          if (!message) return;
          Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonText: 'Tutup',
            allowOutsideClick: false,
            heightAuto: false,
            scrollbarPadding: false
          }).then(function(){
            // refocus username input after popup closed
            try { var u = document.getElementById('username'); if (u) u.focus(); } catch(e) {}
          });
        };

        if (successMsg) {
          showModal('success', 'Berhasil', successMsg);
        } else if (errorMsg) {
          showModal('error', 'Login Gagal', errorMsg);
        } else if (firstError) {
          showModal('error', 'Terjadi Kesalahan', firstError);
        }
      } catch (e) {
        // silent
      }
    })();
  </script>
</body>
</html>