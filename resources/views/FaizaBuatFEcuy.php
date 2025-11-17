<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coco Farma | Arang Batok & Asap Cair Kelapa</title>
  <meta name="description" content="Produsen resmi arang batok kelapa dan asap cair batok kelapa. Informasi real-time, dapat dipertanggungjawabkan. Hubungi kami untuk kerjasama B2B.">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTYiIGN5PSIxNiIgcj0iMTYiIGZpbGw9IiM4YjVhMmIiLz4KPHRleHQgeD0iMTYiIHk9IjIwIiBmb250LXNpemU9IjEwIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QzwvdGV4dD4KPC9zdmc+">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Poppins Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --coco-brown: #8B5A2B;
      --coco-light: #A67C52;
      --green: #2d6a4f;
      --green-pale: #95d5b2;
      --gold: #D4A017;
      --white: #ffffff;
      --light: #f9f7f3;
      --gray: #6b7280;
      --dark: #1f2937;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      line-height: 1.7;
    }

    /* Navbar */
    .navbar {
      background: transparent !important;
      backdrop-filter: none;
      box-shadow: none;
      padding: 1.2rem 0;
      transition: all 0.4s ease;
      position: fixed;
      width: 100%;
      z-index: 1000;
    }

    .navbar.scrolled {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(139, 90, 43, 0.15);
      padding: 0.8rem 0;
    }

    .navbar-brand img {
      width: 40px;
      animation: float 3s ease-in-out infinite;
      transition: all 0.3s ease;
    }

    .navbar.scrolled .navbar-brand img {
      width: 36px;
    }

    .nav-link {
      color: white !important;
      font-weight: 500;
      font-size: 1rem;
      padding: 0.5rem 1rem !important;
      transition: color 0.3s ease;
    }

    .navbar.scrolled .nav-link {
      color: var(--coco-brown) !important;
    }

    .nav-link:hover {
      color: var(--green-pale) !important;
    }

    .navbar.scrolled .nav-link:hover {
      color: var(--coco-light) !important;
    }

    .nav-link.active {
      color: var(--gold) !important;
      font-weight: 600;
    }

    .navbar.scrolled .nav-link.active {
      color: var(--gold) !important;
    }

    /* Hamburger Icon */
    .navbar-toggler {
      border: none;
      padding: 0.25rem 0.5rem;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar.scrolled .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%238B5A2B' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Hero */
    .hero {
      height: 90vh;
      min-height: 550px;
      padding-top: 6rem;
      background: 
        linear-gradient(rgba(139, 90, 43, 0.7), rgba(45, 106, 79, 0.85)),
        url('https://via.placeholder.com/1600x900?text=Hero+Placeholder') center/cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
    }

    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at center, rgba(166, 124, 82, 0.3), transparent 70%);
    }

    .hero h1 {
      font-size: 3.8rem;
      font-weight: 700;
      background: linear-gradient(135deg, #ffffff, #f9f7f3);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero p.lead {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 1.5rem auto;
      opacity: 0.95;
    }

    .btn-cta {
      background: var(--coco-light);
      color: white;
      padding: 1rem 2.5rem;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      box-shadow: 0 8px 20px rgba(139, 90, 43, 0.3);
      transition: all 0.3s ease;
    }

    .btn-cta:hover {
      background: var(--coco-brown);
      color: white;
      transform: translateY(-3px);
    }

    /* Section */
    .section { padding: 5rem 0; background: var(--light); }
    .section.bg-light { background: white; }

    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--coco-brown);
      text-align: center;
      margin-bottom: 3rem;
      position: relative;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -12px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--gold);
      border-radius: 2px;
    }

    /* Product Card */
    .product-card {
      background: white;
      border-radius: 1.2rem;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(139, 90, 43, 0.1);
      transition: all 0.4s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .product-card:hover {
      transform: translateY(-12px);
      box-shadow: 0 20px 40px rgba(139, 90, 43, 0.18);
    }

    .product-img-container {
      height: 220px;
      background: #f0e6d6;
      position: relative;
      overflow: hidden;
    }

    .product-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .product-img-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0e6d6;
      color: var(--coco-brown);
      font-size: 3rem;
    }

    .product-card:hover .product-img {
      transform: scale(1.08);
    }

    .product-body {
      padding: 1.2rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .product-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--coco-brown);
      margin-bottom: 0.4rem;
    }

    .product-desc {
      font-size: 0.85rem;
      color: var(--gray);
      margin-bottom: 0.8rem;
    }

    .wa-product-btn {
      background: var(--green);
      color: white;
      padding: 0.5rem 0.9rem;
      border-radius: 50px;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      text-decoration: none;
      transition: all 0.3s ease;
      align-self: flex-start;
    }

    .wa-product-btn:hover {
      background: var(--coco-light);
      transform: translateY(-2px);
    }

    /* Kegiatan - Desktop: 3 Kotak */
    .activity-item {
      position: relative;
      overflow: hidden;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(139, 90, 43, 0.1);
      transition: transform 0.4s ease;
      height: 100%;
    }

    .activity-item:hover {
      transform: translateY(-8px);
    }

    .activity-item img {
      height: 250px;
      object-fit: cover;
      transition: transform 0.6s ease;
    }

    .activity-item:hover img {
      transform: scale(1.1);
    }

    .activity-caption {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(139, 90, 43, 0.9));
      color: white;
      padding: 1.5rem 1rem 1rem;
      transform: translateY(20px);
      transition: transform 0.4s ease;
    }

    .activity-item:hover .activity-caption {
      transform: translateY(0);
    }

    /* Carousel Kegiatan - Mobile */
    .carousel-item img {
      height: 200px;
      object-fit: cover;
      border-radius: 0.8rem;
    }

    .carousel-caption {
      background: rgba(139, 90, 43, 0.85);
      border-radius: 0.6rem;
      padding: 0.8rem;
      bottom: 1rem;
      font-size: 0.9rem;
    }

    .carousel-caption h5 {
      font-size: 1rem;
      margin-bottom: 0.2rem;
    }

    /* Lokasi & Kontak - Kanan Kiri */
    .contact-info {
      background: white;
      padding: 2rem;
      border-radius: 1.2rem;
      box-shadow: 0 10px 30px rgba(139, 90, 43, 0.1);
      height: 100%;
      border: 1px solid #f0e6d6;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .contact-info h4, .contact-info h5 {
      color: var(--coco-brown);
    }

    .wa-btn {
      background: var(--green);
      color: white;
      padding: 0.7rem 1.2rem;
      border-radius: 50px;
      font-size: 0.95rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .wa-btn:hover {
      background: var(--coco-light);
      transform: translateY(-2px);
    }

    .map-container {
      height: 380px;
      border-radius: 1.2rem;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(139, 90, 43, 0.12);
    }

    /* Footer - Gradasi seperti Hero */
    .footer {
      background: linear-gradient(135deg, rgba(139, 90, 43, 0.95), rgba(45, 106, 79, 0.95));
      color: white;
      padding: 3rem 0 1.5rem;
      position: relative;
    }

    .footer::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top center, rgba(166, 124, 82, 0.2), transparent 70%);
      z-index: 0;
    }

    .footer > .container {
      position: relative;
      z-index: 1;
    }

    .footer a {
      color: var(--green-pale);
      text-decoration: none;
    }

    .footer a:hover {
      color: white;
    }

    .social-icons a {
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 0.5rem;
      transition: all 0.3s ease;
    }

    .social-icons a:hover {
      background: white;
      color: var(--coco-brown);
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
      .hero { min-height: 70vh; }
      .hero h1 { font-size: 2.3rem; }
      .hero p.lead { font-size: 1rem; }
      .section { padding: 2.5rem 0; }
      .section-title { font-size: 1.9rem; margin-bottom: 2rem; }

      /* Produk lebih kecil */
      .product-img-container { height: 150px; }
      .product-body { padding: 1rem; }
      .product-title { font-size: 1rem; }
      .product-desc { font-size: 0.8rem; }
      .wa-product-btn { font-size: 0.8rem; padding: 0.4rem 0.8rem; }

      /* Kegiatan: Sembunyikan 3 kotak, tampilkan carousel */
      .activity-desktop { display: none !important; }
      .activity-mobile { display: block !important; }

      /* Lokasi & Kontak: Full width, kontak di atas */
      .contact-info { margin-bottom: 1.5rem; padding: 1.5rem; }
      .map-container { height: 300px; margin-bottom: 1rem; }
    }

    @media (max-width: 480px) {
      .hero h1 { font-size: 1.9rem; }
      .btn-cta { padding: 0.7rem 1.5rem; font-size: 0.95rem; }
      .product-img-container { height: 130px; }
      .contact-info { padding: 1.2rem; }
      .map-container { height: 250px; }
    }

    /* Desktop: Sembunyikan carousel */
    @media (min-width: 769px) {
      .activity-mobile { display: none !important; }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNmMGY2ZTMiLz4KPHRleHQgeD0iMjAiIHk9IjI1IiBmb250LXNpemU9IjEwIiBmaWxsPSIjOGI1YTJiIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Db2NvPC90ZXh0Pgo8L3N2Zz4=" alt="Coco Farma" class="me-2">
        <span class="fw-bold">Cocofarma</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
          <li class="nav-item"><a class="nav-link" href="#kegiatan">Kegiatan</a></li>
          <li class="nav-item"><a class="nav-link" href="#lokasi-kontak">Lokasi & Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero" id="hero">
    <div class="hero-content container" data-aos="fade-up">
      <h1>Cocofarma</h1>
      <p class="lead">
        Produsen skala besar <strong>arang batok kelapa</strong> dan <strong>asap cair batok kelapa</strong> melalui metode pirolisis. 
        Kami menjaga standar mutu tinggi untuk bahan baku briket, karbon aktif, pengawet makanan, dan penyubur tanah.
      </p>
      <a href="#produk" class="btn btn-cta">
        Lihat Produk
      </a>
    </div>
  </section>

  <!-- Produk -->
  <section class="section bg-light" id="produk">
    <div class="container">
      <h2 class="section-title" data-aos="fade-up">Produk Kami</h2>
      <div class="row g-3">
        <!-- Produk 1 -->
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="product-card">
            <div class="product-img-container">
              <img src="https://via.placeholder.com/600x400?text=Arang+Batok+Kelapa" alt="Arang Batok Kelapa" class="product-img">
            </div>
            <div class="product-body">
              <h3 class="product-title">Arang Batok Kelapa</h3>
              <p class="product-desc">Briket, BBQ, shisha, karbon aktif.</p>
              <a href="https://wa.me/6289616374345?text=Halo,%20saya%20ingin%20pesan%20Arang%20Batok%20Kelapa" class="wa-product-btn">
                WhatsApp Pesan
              </a>
            </div>
          </div>
        </div>

        <!-- Produk 2 -->
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="product-card">
            <div class="product-img-container">
              <img src="https://via.placeholder.com/600x400?text=Asap+Cair+Pertanian" alt="Asap Cair Pertanian" class="product-img">
            </div>
            <div class="product-body">
              <h3 class="product-title">Asap Cair Pertanian</h3>
              <p class="product-desc">Penyubur tanah & pestisida alami.</p>
              <a href="https://wa.me/6289616374345?text=Halo,%20saya%20ingin%20pesan%20Asap%20Cair%20untuk%20Pertanian" class="wa-product-btn">
                WhatsApp Pesan
              </a>
            </div>
          </div>
        </div>

        <!-- Produk 3 -->
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="product-card">
            <div class="product-img-container">
              <img src="https://via.placeholder.com/600x400?text=Asap+Cair+Food+Grade" alt="Asap Cair Food Grade" class="product-img">
            </div>
            <div class="product-body">
              <h3 class="product-title">Asap Cair Food Grade</h3>
              <p class="product-desc">Pengawet makanan aman.</p>
              <a href="https://wa.me/6289616374345?text=Halo,%20saya%20ingin%20pesan%20Asap%20Cair%20Food%20Grade" class="wa-product-btn">
                WhatsApp Pesan
              </a>
            </div>
          </div>
        </div>

        <!-- Produk 4 -->
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="400">
          <div class="product-card">
            <div class="product-img-container">
              <img src="https://via.placeholder.com/600x400?text=Penyubur+Tanah" alt="Penyubur Tanah" class="product-img">
            </div>
            <div class="product-body">
              <h3 class="product-title">Penyubur Tanah</h3>
              <p class="product-desc">Ramah lingkungan.</p>
              <a href="https://wa.me/6289616374345?text=Halo,%20saya%20ingin%20pesan%20Asap%20Cair%20Penyubur%20Tanah" class="wa-product-btn">
                WhatsApp Pesan
              </a>
            </div>
          </div>
        </div>

        <!-- Produk 5 -->
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="500">
          <div class="product-card">
            <div class="product-img-container">
              <img src="https://via.placeholder.com/600x400?text=Antrakill" alt="Antrakill" class="product-img">
            </div>
            <div class="product-body">
              <h3 class="product-title">Antrakill</h3>
              <p class="product-desc">Fungisida organik cabai.</p>
              <a href="https://wa.me/6289616374345?text=Halo,%20saya%20ingin%20pesan%20Antrakill" class="wa-product-btn">
                WhatsApp Pesan
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kegiatan Kami -->
  <section class="section" id="kegiatan">
    <div class="container">
      <h2 class="section-title" data-aos="fade-up">Kegiatan Kami</h2>

      <!-- Desktop: 3 Kotak -->
      <div class="row g-4 activity-desktop">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="activity-item">
            <img src="https://via.placeholder.com/1200x800?text=Proses+Pirolisis" alt="Proses Pirolisis" class="w-100">
            <div class="activity-caption">
              <h5 class="mb-0">Proses Pirolisis</h5>
              <small>Produksi asap cair berkualitas tinggi</small>
            </div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="activity-item">
            <img src="https://via.placeholder.com/1200x800?text=Pengolahan+Arang" alt="Pengolahan Arang" class="w-100">
            <div class="activity-caption">
              <h5 class="mb-0">Pengolahan Arang</h5>
              <small>Arang batok kelapa siap ekspor</small>
            </div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="activity-item">
            <img src="https://via.placeholder.com/1200x800?text=Tim+Cocofarma" alt="Tim Kami" class="w-100">
            <div class="activity-caption">
              <h5 class="mb-0">Tim Cocofarma</h5>
              <small>Komitmen pada kualitas & lingkungan</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile: Carousel -->
      <div class="activity-mobile" style="display: none;">
        <div id="kegiatanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#kegiatanCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#kegiatanCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#kegiatanCarousel" data-bs-slide-to="2"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://via.placeholder.com/1200x800?text=Proses+Pirolisis" alt="Proses Pirolisis" class="d-block w-100">
              <div class="carousel-caption">
                <h5>Proses Pirolisis</h5>
                <p>Produksi asap cair berkualitas tinggi</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="https://via.placeholder.com/1200x800?text=Pengolahan+Arang" alt="Pengolahan Arang" class="d-block w-100">
              <div class="carousel-caption">
                <h5>Pengolahan Arang</h5>
                <p>Arang batok kelapa siap ekspor</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="https://via.placeholder.com/1200x800?text=Tim+Cocofarma" alt="Tim Kami" class="d-block w-100">
              <div class="carousel-caption">
                <h5>Tim Cocofarma</h5>
                <p>Komitmen pada kualitas & lingkungan</p>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#kegiatanCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#kegiatanCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </div>
  </section>

<!-- Lokasi & Kontak - 1 Div -->
<section class="section bg-light" id="lokasi-kontak">
  <div class="container">
    <h2 class="section-title text-center mb-5" data-aos="fade-up">Lokasi & Kontak</h2>

    <!-- Satu Card: Maps Lebih Lebar (70%) -->
    <div class="bg-white shadow-sm rounded p-3" data-aos="fade-up" data-aos-delay="100">
      <div class="d-flex flex-column flex-lg-row align-items-stretch gap-4" style="min-height: 480px;">

        <!-- Maps (Kiri - 70%) -->
        <div class="flex-grow-1" style="flex: 2;">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.095!2d110.367!3d-7.783!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwNDYnNTguOCJTIDExMMKwMjInMDEuMiJF!5e0!3m2!1sid!2sid!4v1690000000000" 
            width="100%" 
            height="100%" 
            style="border:0; border-radius:10px;" 
            allowfullscreen 
            loading="lazy">
          </iframe>
        </div>

        <!-- Kontak (Kanan - 30%) -->
        <div class="flex-grow-1 p-3 d-flex flex-column justify-content-center" style="flex: 1;">
          <h4 class="mb-3">Kunjungi Kami</h4>
          <p class="mb-2"><strong>Alamat:</strong><br>Dusun Ngentak, RT 03, Bangunjiwo, Kasihan, Bantul, Yogyakarta 55751</p>
          <p class="mb-3"><strong>Jam Operasional:</strong><br>Senin–Sabtu: 08.00–17.00 WIB</p>

          <h5 class="mt-4 mb-3">Hubungi via WhatsApp</h5>
          <div class="d-grid gap-2">
            <a href="https://wa.me/6289616374345" class="btn btn-success" target="_blank">WhatsApp +62 896-1637-4345</a>
            <a href="https://wa.me/6282138426817" class="btn btn-success" target="_blank">WhatsApp 0821-3842-6817</a>
            <a href="https://wa.me/6285225309891" class="btn btn-success" target="_blank">WhatsApp +62 852-2530-9891</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

  <!-- Footer - Gradasi seperti Hero -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5 class="fw-bold">Cocofarma</h5>
          <p>Produsen arang batok & asap cair kelapa berkualitas tinggi.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>
      </div>
      <hr class="my-4 opacity-25">
      <p class="text-center mb-0">© 2025 Coco Farma. Hak cipta dilindungi.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ once: true, duration: 800 });

    window.addEventListener('scroll', () => {
      const navbar = document.querySelector('.navbar');
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
      });
    });

    // Active section highlighting
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 100; // Offset for navbar height
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
          link.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>