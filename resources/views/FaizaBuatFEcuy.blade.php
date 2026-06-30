{{-- 
  ========================================================================
  PROJECT    : Cocofarma Landing Page
  AUTHOR     : BolopaKW
  DATE       : {{ date('Y-m-d') }}
  DESCRIPTION: Halaman utama (Landing Page) modern untuk produk Briket Arang
               dan Asap Cair dengan implementasi UI/UX premium, Glassmorphism, 
               AOS animations, dan Bootstrap 5 ScrollSpy.
  ======================================================================== 
--}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coco Farma | Premium Coconut Charcoal & Liquid Smoke</title>
  <meta name="description" content="Produsen resmi arang batok kelapa dan asap cair batok kelapa berkualitas ekspor. Hubungi kami untuk kerjasama B2B.">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --primary: #1A3626; /* Deep Forest Green */
      --secondary: #2d6a4f;
      --accent: #d4a373; /* Rich amber/gold */
      --accent-hover: #b5835a;
      --dark: #0a0f0d;
      --light: #fdfbf7;
      --gray: #6b7280;
      
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    html {
      scroll-behavior: smooth;
      scroll-padding-top: 100px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--light);
      color: var(--dark);
      line-height: 1.7;
      overflow-x: hidden;
      position: relative;
    }

    h1, h2, h3, h4, h5, h6, .navbar-brand {
      font-family: 'Outfit', sans-serif;
    }

    /* Navbar - Glassmorphism */
    .navbar {
      background: rgba(255, 255, 255, 0.05) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding: 1.2rem 0;
      transition: all 0.4s ease;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 9999;
    }

    .navbar.scrolled {
      background: rgba(253, 251, 247, 0.9) !important;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding: 0.8rem 0;
    }

    .navbar-brand span {
      color: white;
      font-size: 1.4rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      transition: color 0.3s ease;
    }
    
    .navbar.scrolled .navbar-brand span {
      color: var(--primary);
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-5px); }
      100% { transform: translateY(0px); }
    }

    .logo-float {
      animation: float 3s ease-in-out infinite;
    }

    .nav-link {
      color: rgba(255,255,255,0.8) !important;
      font-weight: 500;
      font-size: 0.95rem;
      padding: 0.5rem 1.2rem !important;
      transition: all 0.3s ease;
      position: relative;
    }

    .navbar.scrolled .nav-link {
      color: var(--primary) !important;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      background: var(--accent);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after, .nav-link.active::after {
      width: 80%;
    }

    .nav-link:hover, .nav-link.active {
      color: white !important;
    }
    
    .navbar.scrolled .nav-link:hover, .navbar.scrolled .nav-link.active {
      color: var(--accent) !important;
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      min-height: 700px;
      background: url('{{ asset('bolopa/back/images/TimCocofarma.jpg') }}') center/cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(10, 15, 13, 0.85) 0%, rgba(26, 54, 38, 0.75) 100%);
      z-index: 1;
    }

    .hero-particles {
      position: absolute;
      inset: 0;
      z-index: 2;
      background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 40px 40px;
      opacity: 0.5;
    }

    .hero-content {
      position: relative;
      z-index: 3;
      text-align: center;
      color: white;
      max-width: 900px;
      padding: 0 20px;
    }

    .badge-premium {
      background: rgba(212, 163, 115, 0.2);
      border: 1px solid var(--accent);
      color: var(--accent);
      padding: 0.5rem 1.2rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      display: inline-block;
      backdrop-filter: blur(4px);
    }

    .hero h1 {
      font-size: 4.5rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 1.5rem;
      background: linear-gradient(to right, #fff, #e2e8f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero h1 span {
      color: var(--accent);
      -webkit-text-fill-color: var(--accent);
    }

    .hero p.lead {
      font-size: 1.2rem;
      color: rgba(255,255,255,0.85);
      margin-bottom: 2.5rem;
      font-weight: 300;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .btn-glow {
      position: relative;
      background: var(--accent);
      color: var(--dark);
      padding: 1.1rem 2.5rem;
      font-size: 1.05rem;
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      border: none;
      border-radius: 50px;
      text-decoration: none;
      transition: all 0.3s ease;
      overflow: hidden;
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
    }

    .btn-glow::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      transition: all 0.5s ease;
    }

    .btn-glow:hover::before {
      left: 100%;
    }

    .btn-glow:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 30px rgba(212, 163, 115, 0.4);
      color: var(--dark);
    }

    /* Sections */
    .section {
      padding: 7rem 0;
    }

    .section-title {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--primary);
      text-align: center;
      margin-bottom: 1rem;
    }

    .section-subtitle {
      text-align: center;
      color: var(--gray);
      margin-bottom: 4rem;
      font-size: 1.1rem;
    }

    /* Product Cards */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 2.5rem;
    }

    .product-card {
      background: white;
      border-radius: 24px;
      padding: 1rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.04);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      border: 1px solid rgba(0,0,0,0.02);
    }

    .product-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 30px 60px rgba(212, 163, 115, 0.15);
    }

    .product-img-wrapper {
      width: 100%;
      height: 280px;
      border-radius: 16px;
      overflow: hidden;
      position: relative;
    }

    .product-img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.7s ease;
    }

    .product-card:hover .product-img-wrapper img {
      transform: scale(1.1);
    }

    .product-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(10px);
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-weight: 700;
      font-size: 0.8rem;
      color: var(--primary);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      z-index: 2;
    }

    .product-info {
      padding: 1.8rem 1rem 1rem;
    }

    .product-info h3 {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }

    .product-info p {
      color: var(--gray);
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .btn-outline-custom {
      display: inline-block;
      border: 2px solid var(--accent);
      color: var(--accent);
      padding: 0.6rem 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      font-family: 'Outfit', sans-serif;
    }

    .btn-outline-custom:hover {
      background: var(--accent);
      color: white;
    }

    /* Stats Section - Glassmorphism on Image Background */
    .stats-section {
      background: url('{{ asset('bolopa/back/images/arangkelapa.jpg') }}') center/cover fixed;
      position: relative;
      padding: 5rem 0;
    }

    .stats-overlay {
      position: absolute;
      inset: 0;
      background: rgba(26, 54, 38, 0.85);
    }

    .stat-card {
      background: rgba(255,255,255,0.03);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 2.5rem 1.5rem;
      text-align: center;
      color: white;
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-10px);
      background: rgba(255,255,255,0.06);
    }

    .stat-number {
      font-family: 'Outfit', sans-serif;
      font-size: 3.5rem;
      font-weight: 800;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 1.1rem;
      font-weight: 500;
      color: rgba(255,255,255,0.8);
    }

    /* Contact Section */
    .contact-wrapper {
      background: white;
      border-radius: 30px;
      overflow: hidden;
      box-shadow: 0 30px 60px rgba(0,0,0,0.05);
      display: flex;
      flex-wrap: wrap;
    }

    .contact-info {
      flex: 1;
      min-width: 300px;
      padding: 4rem;
      background: var(--primary);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .contact-info::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(212,163,115,0.2) 0%, transparent 70%);
    }

    .contact-info h3 {
      font-size: 2.2rem;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .contact-detail {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
      position: relative;
    }

    .contact-icon {
      width: 45px;
      height: 45px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      font-size: 1.2rem;
      color: var(--accent);
      flex-shrink: 0;
    }

    .contact-text h5 {
      font-size: 1.1rem;
      margin-bottom: 0.2rem;
      color: white;
    }
    
    .contact-text p {
      color: rgba(255,255,255,0.7);
      margin: 0;
    }

    .map-container {
      flex: 1.5;
      min-width: 300px;
      min-height: 400px;
    }

    /* Footer */
    footer {
      background: var(--dark);
      color: rgba(255,255,255,0.6);
      padding: 2rem 0;
      text-align: center;
      border-top: 1px solid rgba(255,255,255,0.05);
    }

    footer p {
      margin: 0;
    }

    /* Responsive */
    @media (max-width: 991px) {
      .hero h1 { font-size: 3.5rem; }
      .contact-info { padding: 3rem 2rem; }
    }
    @media (max-width: 768px) {
      .hero h1 { font-size: 2.8rem; }
      .hero p.lead { font-size: 1rem; }
      .section { padding: 5rem 0; }
      .section-title { font-size: 2.2rem; }
      .stat-card { margin-bottom: 1.5rem; }
      .navbar { background: rgba(253, 251, 247, 0.95) !important; padding: 0.8rem 0; }
      .navbar-brand span, .nav-link { color: var(--primary) !important; }
      .navbar-toggler-icon { filter: invert(1); }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg" id="mainNav">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}" alt="Coco Farma" class="me-2 logo-float" style="width: 40px; height: 40px; border-radius: 50%;">
        <span>Cocofarma</span>
      </a>
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto gap-1">
          <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#produk">Produk Unggulan</a></li>
          <li class="nav-item"><a class="nav-link" href="#kualitas">Kualitas Kami</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-overlay"></div>
    <div class="hero-particles"></div>
    <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
      <div class="badge-premium">Pabrik & Produsen Resmi</div>
      <h1>Ekspor Kualitas <span>Terbaik</span> dari Batok Kelapa</h1>
      <p class="lead">Kami memproduksi Briket Arang Batok Kelapa dan Asap Cair Grade A dengan standar internasional untuk memenuhi kebutuhan industri dan retail global Anda.</p>
      <a href="#produk" class="btn-glow">
        Lihat Katalog Produk <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </section>


  <!-- Products Section -->
  <section class="section" id="produk">
    <div class="container">
      <h2 class="section-title" data-aos="fade-up">Katalog Produk</h2>
      <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Diproses dengan teknologi modern untuk menghasilkan kualitas pembakaran dan filtrasi terbaik.</p>

      <div class="product-grid">
        <!-- Product 1: Briket -->
        <div class="product-card" data-aos="fade-up" data-aos-delay="100">
          <div class="product-badge">Best Seller</div>
          <div class="product-img-wrapper">
            <img src="{{ asset('bolopa/back/images/arangbatok.jpg') }}" alt="Briket Arang Batok Kelapa">
          </div>
          <div class="product-info">
            <h3>Briket Arang Premium</h3>
            <p>Terbuat dari 100% arang batok kelapa pilihan. Menghasilkan panas tinggi, tanpa asap, tanpa bau, dan durasi pembakaran yang lama. Sangat cocok untuk Shisha & BBQ.</p>
            <a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20tertarik%20untuk%20memesan%20produk%20Briket%20Arang%20Premium.%20Bisa%20minta%20informasi%20lebih%20lanjut?" target="_blank" class="btn-outline-custom">
              <i class="fab fa-whatsapp me-2"></i>Pesan
            </a>
          </div>
        </div>

        <!-- Product 2: Asap Cair Food Grade -->
        <div class="product-card" data-aos="fade-up" data-aos-delay="150">
          <div class="product-badge">Grade A</div>
          <div class="product-img-wrapper">
            <img src="{{ asset('bolopa/back/images/AsapCairFoodGrade.png') }}" alt="Asap Cair Food Grade">
          </div>
          <div class="product-info">
            <h3>Asap Cair Food Grade</h3>
            <p>Pengawet makanan alami yang diproses dengan kondensasi dan filtrasi tingkat tinggi. Sangat aman dikonsumsi untuk memperpanjang umur simpan daging dan ikan.</p>
            <a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20tertarik%20untuk%20memesan%20produk%20Asap%20Cair%20Food%20Grade.%20Bisa%20minta%20informasi%20lebih%20lanjut?" target="_blank" class="btn-outline-custom">
              <i class="fab fa-whatsapp me-2"></i>Pesan
            </a>
          </div>
        </div>

        <!-- Product 3: Asap Cair Penguat Rasa -->
        <div class="product-card" data-aos="fade-up" data-aos-delay="200">
          <div class="product-badge">Penyedap</div>
          <div class="product-img-wrapper">
            <img src="{{ asset('bolopa/back/images/AsapCairPenguatRasa.png') }}" alt="Asap Cair Penguat Rasa">
          </div>
          <div class="product-info">
            <h3>Asap Cair Penguat Rasa</h3>
            <p>Memberikan aroma asap (smokey flavor) yang khas dan otentik pada masakan Anda tanpa harus melakukan proses pengasapan secara tradisional.</p>
            <a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20tertarik%20untuk%20memesan%20produk%20Asap%20Cair%20Penguat%20Rasa.%20Bisa%20minta%20informasi%20lebih%20lanjut?" target="_blank" class="btn-outline-custom">
              <i class="fab fa-whatsapp me-2"></i>Pesan
            </a>
          </div>
        </div>

        <!-- Product 4: Asap Cair Pertanian -->
        <div class="product-card" data-aos="fade-up" data-aos-delay="250">
          <div class="product-badge">Grade C</div>
          <div class="product-img-wrapper">
            <img src="{{ asset('bolopa/back/images/AsapCairPertanian.jpg') }}" alt="Asap Cair Pertanian">
          </div>
          <div class="product-info">
            <h3>Asap Cair Pertanian</h3>
            <p>Solusi pestisida dan insektisida organik. Sangat efektif membasmi hama tanaman, mengusir tikus, serta memperbaiki kualitas dan keasaman tanah.</p>
            <a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20tertarik%20untuk%20memesan%20produk%20Asap%20Cair%20Pertanian.%20Bisa%20minta%20informasi%20lebih%20lanjut?" target="_blank" class="btn-outline-custom">
              <i class="fab fa-whatsapp me-2"></i>Pesan
            </a>
          </div>
        </div>

        <!-- Product 5: Hensa -->
        <div class="product-card" data-aos="fade-up" data-aos-delay="300">
          <div class="product-badge">Anti-Bakteri</div>
          <div class="product-img-wrapper">
            <img src="{{ asset('bolopa/back/images/Hensa.jpg') }}" alt="Hensa (Hand Sanitizer)">
          </div>
          <div class="product-info">
            <h3>Hensa Hand Sanitizer</h3>
            <p>Pembersih tangan higienis berbahan dasar turunan asap cair organik. Membunuh kuman dan bakteri 99.9% secara instan, aman bagi kulit serta ramah lingkungan.</p>
            <a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20tertarik%20untuk%20memesan%20produk%20Hensa%20Hand%20Sanitizer.%20Bisa%20minta%20informasi%20lebih%20lanjut?" target="_blank" class="btn-outline-custom">
              <i class="fab fa-whatsapp me-2"></i>Pesan
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section (Kualitas Kami) -->
  <section class="stats-section" id="kualitas">
    <div class="stats-overlay"></div>
    <div class="container position-relative z-index-1">
      <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Bahan Alami Pilihan</div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="stat-card">
            <div class="stat-number">Grade A</div>
            <div class="stat-label">Kualitas Ekspor Global</div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="stat-card">
            <div class="stat-number">B2B</div>
            <div class="stat-label">Suplai Partai Besar</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact & Map -->
  <section class="section bg-light" id="kontak">
    <div class="container">
      <div class="contact-wrapper" data-aos="zoom-in" data-aos-duration="1000">
        <div class="contact-info">
          <h3>Hubungi Kami</h3>
          
          <div class="contact-detail">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="contact-text">
              <h5>Alamat Pabrik</h5>
              <p>Kadisoka Purwomartani Kalasan Sleman Yogyakarta,<br>Kalasan, Indonesia, 55571</p>
            </div>
          </div>
          
          <div class="contact-detail">
            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
            <div class="contact-text">
              <h5>Telepon / WhatsApp</h5>
              <p><a href="https://wa.me/6289616374345?text=Halo%20Cocofarma,%20saya%20ingin%20bertanya%20seputar%20produk%20Anda." target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">+62 896-1637-4345</a><br>Senin - Sabtu, 08:00 - 17:00</p>
            </div>
          </div>

          <div class="contact-detail">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <div class="contact-text">
              <h5>Email</h5>
              <p>sales@cocofarma.com<br>info@cocofarma.com</p>
            </div>
          </div>
        </div>
        
        <div class="map-container">
          <iframe src="https://maps.google.com/maps?q=Kadisoka%20Purwomartani%20Kalasan%20Sleman%20Yogyakarta,%20Indonesia&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; {{ date('Y') }} Cocofarma. All Rights Reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    // Initialize AOS
    AOS.init({
      once: true,
      offset: 50
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Custom Bulletproof ScrollSpy
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

    const observerOptions = {
      root: null,
      rootMargin: "-20% 0px -70% 0px",
      threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute("id");
          navLinks.forEach((link) => {
            link.classList.remove("active");
            if (link.getAttribute("href") === "#" + id) {
              link.classList.add("active");
            }
          });
        }
      });
    }, observerOptions);

    sections.forEach((section) => {
      observer.observe(section);
    });
  </script>
</body>
</html>