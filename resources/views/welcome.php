<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cocofarma</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="/bolopa/back/images/icon/twemoji--coconut.svg">
  <link rel="apple-touch-icon" href="/bolopa/back/images/icon/twemoji--coconut.svg">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }
    .bg-hero {
      background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80')
                 center/cover no-repeat;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      position: relative;
    }
    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.4);
    }
    .welcome-content {
      position: relative;
      z-index: 2;
    }
    .welcome-content h1 {
      font-size: 3rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .welcome-content p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>

  <section class="bg-hero">
    <div class="overlay"></div>
    <div class="welcome-content">
      <!-- Ikon "kelapa" sementara pakai daun -->
      <i class="fa-solid fa-leaf fa-3x mb-3"></i>
      <h1>Backoffice Cocofarma</h1>
      <p>Website Manajemen Sistem</p>
      <?php $adminUrl = url('/mimin'); ?>
      <a href="<?= $adminUrl ?>" class="btn btn-success btn-lg">
        <i class="fa-solid fa-circle-arrow-right me-2"></i>Test
      </a>
    </div>
  </section>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
