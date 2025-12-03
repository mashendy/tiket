<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>TiketKu - Gerbang Menuju Pengalaman Tak Terlupakan!</title>
  <meta name="description" content="Pesan tiket konser, festival, seminar, dan acara favoritmu dengan mudah, cepat, dan aman di TiketKu. Jangan lewatkan keseruannya!">
  <meta name="keywords" content="tiket, acara, event, konser, seminar, festival, workshop, beli tiket, tiket online, TiketKu, hiburan">

  <!-- Favicons (Ganti dengan path favicon TiketKu Anda) -->
  <link href="assets/img/favicon_tiketku.png" rel="icon">
  <link href="assets/img/apple-touch-icon_tiketku.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Custom CSS for TiketKu Theme -->
  <style>
    :root {
      --tiketku-primary: #E94560; /* Merah muda yang kuat dan energik */
      --tiketku-secondary: #16213E; /* Biru tua sebagai kontras elegan */
      --tiketku-accent: #FFC107; /* Kuning untuk CTA atau highlight */
      --tiketku-light: #F5F7FA; /* Latar belakang yang sangat terang/putih keabuan */
      --tiketku-dark-text: #333333; /* Teks gelap utama */
      --tiketku-light-text: #f1f1f1; /* Teks terang (misalnya di atas latar gelap) */
      --tiketku-secondary-rgb: 22, 33, 62; /* RGB untuk warna sekunder, digunakan dalam box-shadow */
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--tiketku-light);
      color: var(--tiketku-dark-text);
      line-height: 1.7;
    }

    /* Header Styling */
    .header {
        background-color: rgba(255, 255, 255, 0.95); /* Sedikit lebih solid */
        backdrop-filter: blur(10px); /* Efek blur lebih kuat */
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); /* Shadow lebih halus */
        padding: 15px 0;
    }
    .header .sitename {
        color: var(--tiketku-primary);
        font-weight: 700;
        font-size: 30px;
        letter-spacing: -0.5px;
    }
    .navmenu a {
        font-weight: 500;
        color: var(--tiketku-secondary);
        padding: 10px 15px;
        transition: color 0.3s ease;
    }
    .navmenu a:hover, .navmenu .active > a, .navmenu .active { /* Perbaikan untuk .active */
        color: var(--tiketku-primary);
    }
    .btn-getstarted { /* Tombol Login/Daftar di Header */
      background-color: var(--tiketku-primary);
      color: white !important; /* Pastikan warna teks putih */
      padding: 10px 28px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(233, 69, 96, 0.3);
    }
    .btn-getstarted:hover {
      background-color: #D43750;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(233, 69, 96, 0.5);
    }

    /* Hero Section Styling */
    .hero {
      background: linear-gradient(135deg, rgba(233, 69, 96, 0.85) 0%, rgba(22, 33, 62, 0.9) 100%), url('assets/img/hero-bg-crowd.jpg') no-repeat center center;
      background-size: cover;
      min-height: 95vh; /* Sedikit lebih tinggi */
      display: flex;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    .hero h1 {
      font-size: clamp(2.5rem, 5vw, 3.8rem); /* Ukuran font responsif */
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 25px;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.4);
    }
    .hero p {
      font-size: clamp(1rem, 2.5vw, 1.25rem); /* Ukuran font responsif */
      margin-bottom: 35px;
      color: rgba(255,255,255,0.95);
      max-width: 600px; /* Batasi lebar paragraf */
    }
    .hero .btn-hero-primary {
      background-color: var(--tiketku-accent);
      color: var(--tiketku-secondary);
      padding: 15px 40px; /* Padding lebih besar */
      border-radius: 50px;
      font-weight: 700;
      font-size: 1.05rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      transition: all 0.3s ease;
      border: none;
      box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }
    .hero .btn-hero-primary:hover {
      background-color: #FFD700;
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5);
    }
    .hero .btn-hero-secondary {
        color: white;
        border: 2px solid rgba(255,255,255,0.8);
        padding: 13px 35px; /* Disesuaikan dengan primary */
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .hero .btn-hero-secondary:hover {
        background-color: rgba(255,255,255,0.15);
        border-color: white;
        transform: translateY(-2px);
    }
    .hero-img img {
        max-width: 100%;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        /* Animasi untuk gambar hero */
        /* animation: floatAnimation 6s ease-in-out infinite; */
    }
    /* @keyframes floatAnimation { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } } */

    /* Section Title Styling */
    .section-title {
        margin-bottom: 50px; /* Jarak lebih besar */
        text-align: center;
    }
    .section-title span {
        display: block;
        font-size: 1rem;
        color: var(--tiketku-primary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px; /* Spasi antar huruf lebih besar */
        margin-bottom: 8px;
    }
    .section-title h2 {
        font-size: clamp(2rem, 4vw, 2.8rem); /* Ukuran font responsif */
        font-weight: 700;
        color: var(--tiketku-secondary);
        margin-bottom: 15px;
    }
    .section-title p {
        color: #555; /* Warna teks deskripsi lebih gelap sedikit */
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
        font-size: 1.05rem;
    }

    /* Services/Keunggulan Section Styling */
    .services .service-item {
        padding: 35px 30px; /* Padding lebih besar */
        background: white;
        border-radius: 20px; /* Lebih rounded */
        box-shadow: 0 8px 30px rgba(var(--tiketku-secondary-rgb), 0.07);
        transition: all 0.35s ease-in-out;
        height: 100%;
        text-align: center;
    }
    .services .service-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(var(--tiketku-secondary-rgb), 0.12);
    }
    .services .service-item .icon {
        width: 75px; /* Ikon lebih besar */
        height: 75px;
        background: linear-gradient(135deg, var(--tiketku-primary), #F47C7C); /* Gradien untuk ikon */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto; /* Agar ikon di tengah */
        margin-right: auto;
        margin-bottom: 25px;
        transition: background 0.3s, transform 0.3s;
        box-shadow: 0 5px 15px rgba(233, 69, 96, 0.3);
    }
    .services .service-item:hover .icon {
        background: linear-gradient(135deg, var(--tiketku-secondary), #2C3E50);
        transform: rotate(15deg) scale(1.1);
    }
    .services .service-item .icon i {
        color: white;
        font-size: 2.5rem; /* Ukuran ikon di dalam lingkaran */
    }
    .services .service-item h3 {
        color: var(--tiketku-secondary);
        font-weight: 600;
        font-size: 1.4rem; /* Judul layanan lebih besar */
        margin-bottom: 12px;
    }
    .services .service-item p {
        font-size: 0.95rem;
        color: #555;
    }

    /* Carousel Acara Styling */
    #carousel-section {
        padding-top: 0;
    }
    .carousel-inner img {
      height: 550px; /* Ditingkatkan */
      object-fit: cover;
      border-radius: 20px; /* Lebih rounded */
      transition: opacity 0.7s ease-in-out, transform 5s ease; /* Efek Ken Burns sederhana */
    }
    .carousel-item.active img { /* Efek Ken Burns */
        /* transform: scale(1.05); */ /* Mulai sedikit zoom atau sebaliknya */
    }
    .carousel-caption {
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.05) 100%);
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px 25px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }
    .carousel-caption h5 {
        font-size: 2rem; /* Judul di caption lebih besar */
        font-weight: 700;
        text-shadow: 2px 2px 5px rgba(0,0,0,0.6);
        margin-bottom: 10px;
    }
    .carousel-caption p {
        font-size: 1rem;
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(var(--tiketku-secondary-rgb), 0.6); /* Warna kontrol disesuaikan */
        border-radius: 50%;
        padding: 18px; /* Ukuran kontrol */
        width: 30px; /* Agar ikonnya pas */
        height: 30px;
    }

    /* Styling untuk Kartu Acara Pilihan */
    #acara-populer .acara-card {
      border: none; /* Hilangkan border default */
      border-radius: 18px; /* Lebih rounded */
      transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
      background-color: #fff;
      box-shadow: 0 10px 30px -15px rgba(var(--tiketku-secondary-rgb), 0.1);
      overflow: hidden;
    }
    #acara-populer .acara-card:hover {
      transform: translateY(-12px);
      box-shadow: 0 20px 40px -10px rgba(var(--tiketku-secondary-rgb), 0.2);
    }
    #acara-populer .acara-card-img-link {
        position: relative;
        display: block;
        overflow: hidden;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    #acara-populer .acara-img {
      height: 240px; /* Tinggi gambar disesuaikan */
      object-fit: cover;
      width: 100%;
      transition: transform 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    #acara-populer .acara-card:hover .acara-img {
      transform: scale(1.08); /* Efek zoom lebih dramatis */
    }
    #acara-populer .acara-kategori-badge {
        position: absolute;
        top: 18px; /* Posisi disesuaikan */
        left: 18px;
        background-color: var(--tiketku-primary);
        color: white;
        padding: 6px 12px; /* Padding lebih besar */
        border-radius: 8px; /* Lebih rounded */
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        z-index: 1;
        box-shadow: 0 3px 8px rgba(0,0,0,0.25);
    }
    #acara-populer .card-body {
        padding: 25px; /* Padding lebih besar */
    }
    #acara-populer .acara-title {
      font-size: 1.2rem;
      font-weight: 700; /* Lebih tebal */
      color: var(--tiketku-secondary);
      margin-bottom: 10px;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      min-height: calc(1.4em * 2);
    }
    #acara-populer .acara-title a:hover {
        color: var(--tiketku-primary);
    }
    #acara-populer .acara-info {
      font-size: 0.9rem; /* Sedikit lebih besar */
      color: #555;
      margin-bottom: 5px; /* Jarak antar info */
    }
    #acara-populer .acara-info i {
      color: var(--tiketku-primary);
      margin-right: 8px; /* Jarak ikon dan teks */
    }
    #acara-populer .acara-harga {
      font-size: 1.4rem; /* Harga lebih besar */
      font-weight: 700;
      color: var(--tiketku-primary);
    }
    #acara-populer .btn-pesan-tiket {
      background-color: var(--tiketku-primary);
      color: white;
      font-weight: 600; /* Lebih tebal */
      padding: 10px 22px;
      border-radius: 50px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      border: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    #acara-populer .btn-pesan-tiket:hover {
      background-color: #D43750;
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 5px 15px rgba(233, 69, 96, 0.4);
    }
    .btn-lihat-semua {
        background-color: var(--tiketku-secondary);
        color: white;
        padding: 14px 35px; /* Disesuaikan */
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        transition: all 0.3s ease;
    }
    .btn-lihat-semua:hover {
        background-color: #0F172A;
        color: white;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 6px 20px rgba(var(--tiketku-secondary-rgb), 0.25);
    }

    /* About Section Styling */
    .about .content h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--tiketku-secondary);
    }
    .about .content ul i {
        color: var(--tiketku-primary); /* Warna ikon disamakan */
        font-size: 1.2rem;
    }
    .about .content .btn-outline-primary:hover {
        background-color: var(--tiketku-primary);
        color: white;
    }

    /* Video Promo Styling */
    #video-promo .video-section video {
      border: 8px solid white; /* Border lebih tebal */
      box-shadow: 0 15px 60px rgba(0,0,0,0.25);
      border-radius: 20px; /* Lebih rounded */
    }

    /* Contact Section Styling */
    .contact .info-wrap {
        background-color: white;
        padding: 35px; /* Padding lebih besar */
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(var(--tiketku-secondary-rgb), 0.07);
        height: 100%;
    }
    .contact .info-item i {
        color: var(--tiketku-primary);
        font-size: 2.2rem;
    }
    .contact .info-item h3 {
        color: var(--tiketku-secondary);
        font-weight: 600;
        font-size: 1.3rem;
    }
    .contact .php-email-form {
        background-color: white;
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(var(--tiketku-secondary-rgb), 0.07);
        height: 100%;
    }
    .contact .php-email-form .form-control {
        border-radius: 10px; /* Input lebih rounded */
        padding: 12px 15px; /* Padding input */
    }
    .contact .php-email-form button[type="submit"] {
        background: var(--tiketku-primary);
        border-radius: 50px;
        padding: 12px 35px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .contact .php-email-form button[type="submit"]:hover {
        background: #D43750;
        transform: translateY(-2px);
    }

    /* Footer Styling */
    .footer {
        background-color: var(--tiketku-secondary);
        color: rgba(255,255,255,0.85); /* Teks footer lebih terang */
        padding-top: 70px; /* Padding atas lebih besar */
        padding-bottom: 30px;
        font-size: 0.95rem;
    }
    .footer .footer-newsletter {
        background-color: rgba(255,255,255,0.08); /* Latar newsletter lebih subtle */
        padding: 50px 0;
        margin-bottom: 50px;
        border-radius: 15px; /* Lebih rounded */
    }
    .footer .footer-newsletter h4{
        color: white;
        font-weight: 700; /* Lebih tebal */
        font-size: 1.8rem; /* Lebih besar */
        margin-bottom: 15px;
    }
    .footer .footer-newsletter p {
        color: rgba(255,255,255,0.9);
    }
    .footer .footer-newsletter .newsletter-form input[type="email"] {
        border-radius: 50px 0 0 50px;
        border: none;
        padding: 14px 22px; /* Padding lebih besar */
        background-color: rgba(255,255,255,0.95);
        color: var(--tiketku-dark-text);
    }
    .footer .footer-newsletter .newsletter-form button[type="submit"], /* Mengganti input submit dengan button */
    .footer .footer-newsletter .newsletter-form input[type="submit"] { /* Untuk kompatibilitas jika masih input */
        background-color: var(--tiketku-primary);
        color: white;
        border: none;
        border-radius: 0 50px 50px 0;
        padding: 14px 28px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: background-color 0.3s ease;
    }
    .footer .footer-newsletter .newsletter-form button[type="submit"]:hover,
    .footer .footer-newsletter .newsletter-form input[type="submit"]:hover {
        background-color: #D43750;
    }
    .footer .sitename, .footer .footer-links h4 {
        color: white;
        font-weight: 600;
        font-size: 1.1rem; /* Sedikit lebih besar */
        margin-bottom: 15px;
    }
    .footer .footer-links ul i {
        color: var(--tiketku-primary);
        margin-right: 5px;
    }
    .footer .footer-links ul a {
        color: rgba(255,255,255,0.85);
        transition: color 0.3s ease;
    }
    .footer .footer-links ul a:hover {
        color: var(--tiketku-primary);
    }
    .footer .social-links a {
        background-color: rgba(255,255,255,0.15);
        color: white;
        width: 42px; /* Sedikit lebih besar */
        height: 42px;
        border-radius: 50%;
        font-size: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .footer .social-links a:hover {
        background-color: var(--tiketku-primary);
        transform: translateY(-3px);
    }
    .footer .copyright {
        border-top: 1px solid rgba(255,255,255,0.15);
        padding-top: 25px;
        margin-top: 30px;
    }
    .footer .copyright strong {
        color: white;
    }
    .footer .credits a {
        color: var(--tiketku-accent);
        transition: color 0.3s ease;
    }
    .footer .credits a:hover {
        color: #FFD700;
    }

    /* Preloader (jika masih digunakan) */
    #preloader {
        background: var(--tiketku-secondary) url('assets/img/preloader-logo-tiketku.gif') no-repeat center center; /* Ganti dengan GIF preloader Anda */
        background-size: 80px; /* Sesuaikan ukuran logo preloader */
    }

  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="landing.php" class="logo d-flex align-items-center me-auto">
        <!-- Jika punya logo gambar: -->
        <!-- <img src="assets/img/logo_tiketku_white.png" alt="TiketKu Logo"> -->
        <h1 class="sitename">TiketKu</h1>
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#acara-populer">Acara</a></li>
          <li><a href="#services">Keunggulan</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#contact">Kontak</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted" href="login.php">Login / Daftar</a>
    </div>
  </header>

  <main class="main">

    <section id="hero" class="hero section">
      <div class="container">
        <div class="row gy-5 align-items-center">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start" data-aos="fade-up" data-aos-delay="100">
            <h1>Pesan Tiket Acara Impianmu, <span style="color: var(--tiketku-accent);">Sekarang!</span></h1>
            <p>Jelajahi ribuan konser, festival, seminar, dan workshop seru. Pengalaman tak terlupakan menantimu di TiketKu.</p>
            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 justify-content-center justify-content-lg-start">
              <a href="#acara-populer" class="btn-hero-primary">Temukan Acara Terbaik</a>
              <a href="#video-promo" class="btn-hero-secondary d-flex align-items-center justify-content-center"><i class="bi bi-play-circle-fill me-2"></i><span>Lihat Promo Video</span></a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
            <img src="assets/img/hero-event-dynamic.png" class="img-fluid animated" alt="Ilustrasi Dinamis Tiket Acara">
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="services section">
      <div class="container section-title" data-aos="fade-up">
        <span>Keunggulan TiketKu</span>
        <h2>Kenapa Harus TiketKu?</h2>
        <p>Kami memberikan lebih dari sekadar tiket, kami memberikan kemudahan dan pengalaman.</p>
      </div>
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon mx-auto">
                <i class="bi bi-ticket-detailed-fill"></i>
              </div>
              <h3>Pilihan Acara Terlengkap</h3>
              <p>Dari konser musik hingga workshop edukatif, temukan semua jenis acara favoritmu di satu tempat.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon mx-auto">
                <i class="bi bi-shield-check"></i>
              </div>
              <h3>Transaksi Aman & Cepat</h3>
              <p>Nikmati proses pembelian tiket yang aman dengan berbagai metode pembayaran dan konfirmasi instan.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon mx-auto">
                <i class="bi bi-phone-vibrate-fill"></i>
              </div>
              <h3>E-Ticket Praktis</h3>
              <p>Tiket elektronik langsung dikirim ke perangkatmu, mudah diakses dan ramah lingkungan.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php
    include_once 'config.php';
    $acaraPilihan = [];
    try {
        $queryAcara = "SELECT id_acara, judul, lokasi, tanggal_acara, harga, foto, kategori
                       FROM acara
                       WHERE tanggal_acara >= CURDATE()
                       ORDER BY tanggal_acara ASC
                       LIMIT 6";
        $stmtAcara = $pdo->prepare($queryAcara);
        $stmtAcara->execute();
        $acaraPilihan = $stmtAcara->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // error_log("Error fetching 'Acara Pilihan': " . $e->getMessage());
    }
    ?>

    <?php if (!empty($acaraPilihan)): ?>
    <section id="carousel-section" class="section pt-0 bg-white"> <!-- bg-white untuk kontras jika perlu -->
        <div id="carouselAcara" class="carousel slide container py-5" data-bs-ride="carousel" data-bs-interval="3500">
          <div class="carousel-inner">
            <?php
            $activeSet = false;
            foreach ($acaraPilihan as $indexCarousel => $itemCarousel): ?>
              <?php if (!empty($itemCarousel['foto'])): ?>
                <div class="carousel-item <?php if (!$activeSet) { echo 'active'; $activeSet = true; } ?>">
                  <a href="login.php">
                    <img src="uploads/<?= htmlspecialchars($itemCarousel['foto']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($itemCarousel['judul']) ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?= htmlspecialchars($itemCarousel['judul']) ?></h5>
                        <p><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($itemCarousel['lokasi']) ?> | <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($itemCarousel['tanggal_acara'])) ?></p>
                    </div>
                  </a>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <?php if (count(array_filter($acaraPilihan, fn($a) => !empty($a['foto']))) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselAcara" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselAcara" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
          <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="acara-populer" class="section">
        <div class="container section-title" data-aos="fade-up">
          <span>Jangan Ketinggalan!</span>
          <h2>Acara Pilihan Untukmu</h2>
          <p>Temukan dan pesan tiket untuk acara-acara paling seru yang akan datang.</p>
        </div>
        <div class="container">
          <div class="row gy-4">
            <?php if (!empty($acaraPilihan)): ?>
                <?php foreach ($acaraPilihan as $indexCard => $itemAcara): ?>
                  <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in-up" data-aos-delay="<?= ($indexCard % 3) * 100 ?>">
                    <div class="card h-100 acara-card"> <!-- Hapus shadow-sm jika sudah ada di .acara-card -->
                      <a href="login.php" class="text-decoration-none d-block acara-card-img-link">
                        <?php if (!empty($itemAcara['foto'])): ?>
                          <img src="uploads/<?= htmlspecialchars($itemAcara['foto']) ?>" class="card-img-top acara-img" alt="<?= htmlspecialchars($itemAcara['judul']) ?>">
                        <?php else: ?>
                          <img src="assets/img/default-event-placeholder.jpg" class="card-img-top acara-img" alt="Tidak Ada Gambar Acara">
                        <?php endif; ?>
                        <?php if (!empty($itemAcara['kategori'])): ?>
                            <span class="acara-kategori-badge"><?= htmlspecialchars($itemAcara['kategori']) ?></span>
                        <?php endif; ?>
                      </a>
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title acara-title mb-2">
                            <a href="login.php" class="text-decoration-none">
                                <?= htmlspecialchars($itemAcara['judul']) ?>
                            </a>
                        </h5>
                        <p class="card-text acara-info text-muted small mb-1">
                            <i class="bi bi-calendar3"></i> <?= date('D, d M Y', strtotime($itemAcara['tanggal_acara'])) ?>
                        </p>
                        <p class="card-text acara-info text-muted small mb-3">
                            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($itemAcara['lokasi']) ?>
                        </p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <p class="acara-harga mb-0">Rp <?= number_format($itemAcara['harga'], 0, ',', '.') ?></p>
                            <a href="login.php" class="btn btn-pesan-tiket">Pesan Tiket</a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown fs-1 text-muted mb-3" data-aos="zoom-in"></i>
                    <h4 class="text-muted mb-3" data-aos="fade-up" data-aos-delay="100">Oops! Belum Ada Acara Pilihan.</h4>
                    <p class="text-muted" data-aos="fade-up" data-aos-delay="200">Silakan cek kembali nanti untuk menemukan acara seru lainnya!</p>
                </div>
            <?php endif; ?>
          </div>

          <?php
          $totalAcaraMendatang = 0;
          try {
              if (isset($pdo)) { // Pastikan $pdo ada
                $queryTotal = "SELECT COUNT(*) FROM acara WHERE tanggal_acara >= CURDATE()";
                $stmtTotal = $pdo->query($queryTotal);
                if ($stmtTotal) {
                    $totalAcaraMendatang = $stmtTotal->fetchColumn();
                }
              }
          } catch (PDOException $e) { /* Abaikan error */ }

          if ($totalAcaraMendatang > 6) :
          ?>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="semua_acara.php" class="btn btn-lihat-semua btn-lg">Lihat Semua Acara <i class="bi bi-arrow-right-circle-fill ms-2"></i></a>
            </div>
          <?php endif; ?>
        </div>
    </section>

    <section id="about" class="about section light-background">
      <div class="container section-title" data-aos="fade-up">
        <span>Tentang Platform Kami</span>
        <h2>Lebih Dekat Dengan TiketKu</h2>
      </div>
      <div class="container">
        <div class="row gy-4 align-items-center">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
            <img src="assets/img/about-us-tiketku.png" class="img-fluid rounded-4 shadow-lg" alt="Tim TiketKu atau Ilustrasi Layanan">
          </div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <h3 class="fw-bold" style="color: var(--tiketku-secondary);">Misi Kami: Menghubungkan Anda dengan Setiap Momen Berharga.</h3>
            <p class="fst-italic mt-3">
              TiketKu lahir dari semangat untuk mempermudah setiap orang menemukan dan menjadi bagian dari acara yang mereka cintai.
            </p>
            <p>
              Kami adalah platform digital yang berdedikasi untuk menyediakan akses tiket yang transparan, aman, dan menyenangkan. Dengan teknologi terkini dan tim yang bersemangat, kami berkomitmen untuk terus berinovasi dan memberikan pengalaman terbaik bagi pengguna maupun penyelenggara acara di seluruh Indonesia.
            </p>
            <ul class="list-unstyled mt-3">
              <li class="mb-2 d-flex align-items-start"><i class="bi bi-check-circle-fill text-success me-2 fs-5 pt-1"></i> <span>Platform Terintegrasi dan Mudah Digunakan</span></li>
              <li class="mb-2 d-flex align-items-start"><i class="bi bi-check-circle-fill text-success me-2 fs-5 pt-1"></i> <span>Dukungan Penuh untuk Penyelenggara Acara</span></li>
              <li class="mb-2 d-flex align-items-start"><i class="bi bi-check-circle-fill text-success me-2 fs-5 pt-1"></i> <span>Inovasi Berkelanjutan untuk Pengalaman Terbaik</span></li>
            </ul>
             <a href="tentang_kami_lengkap.php" class="btn btn-outline-primary mt-3" style="border-color: var(--tiketku-primary); color: var(--tiketku-primary); border-radius:50px; padding: 10px 25px; font-weight: 500;">Pelajari Lebih Lanjut <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </section>

    <section id="video-promo" class="section">
        <div class="container video-section text-center" data-aos="fade-up">
          <div class="section-title">
            <span>Saksikan Keseruannya</span>
            <h2>Cuplikan Acara di TiketKu</h2>
          </div>
          <div class="ratio ratio-16x9 mx-auto" style="max-width: 800px;">
            <video controls autoplay muted loop poster="assets/img/video-thumbnail-tiketku.jpg" class="rounded-3 shadow-lg">
              <source src="video/promo_event_highlights.mp4" type="video/mp4">
              Browser Anda tidak mendukung tag video.
            </video>
          </div>
        </div>
    </section>

    <section id="contact" class="contact section light-background">
      <div class="container section-title" data-aos="fade-up">
        <span>Butuh Bantuan?</span>
        <h2>Hubungi Tim Dukungan Kami</h2>
        <p>Kami siap membantu Anda dengan pertanyaan atau kendala yang Anda hadapi.</p>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-5">
            <div class="info-wrap">
              <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h3>Alamat Kami</h3>
                  <p>Gedung TiketKu, Jl. Raya Acara No. 1, Jakarta Pusat, Indonesia</p>
                </div>
              </div>
              <div class="info-item d-flex align-items-center mt-4" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0"></i>
                <div>
                  <h3>Telepon & WhatsApp</h3>
                  <p>+62 812 3456 7890 (Senin - Jumat, 09:00 - 17:00 WIB)</p>
                </div>
              </div>
              <div class="info-item d-flex align-items-center mt-4" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h3>Email Dukungan</h3>
                  <p>dukungan@tiketku.com</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <form action="https://wa.me/6281234567890" method="get" target="_blank" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <input type="hidden" name="text" id="whatsapp-message-contact">
              <div class="row gy-4">
                <div class="col-md-6 form-group">
                  <input type="text" id="contact-name" class="form-control" placeholder="Nama Lengkap Anda" required>
                </div>
                <div class="col-md-6 form-group">
                  <input type="email" id="contact-email" class="form-control" placeholder="Alamat Email Anda (Opsional)">
                </div>
                <div class="col-md-12 form-group">
                  <input type="text" id="contact-subject" class="form-control" placeholder="Subjek Pesan" required>
                </div>
                <div class="col-md-12 form-group">
                  <textarea id="contact-message" class="form-control" rows="6" placeholder="Detail pertanyaan atau pesan Anda" required></textarea>
                </div>
                <div class="col-md-12 text-center">
                  <button type="submit">Kirim via WhatsApp</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer id="footer" class="footer">
    <div class="footer-newsletter">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-7">
            <h4>Jangan Ketinggalan Info Acara Keren!</h4>
            <p>Daftarkan email Anda untuk mendapatkan berita terbaru, penawaran spesial, dan rekomendasi acara langsung dari TiketKu.</p>
            <form action="proses_newsletter.php" method="post" class="php-email-form mt-4"> <!-- Ganti proses_newsletter.php dengan skrip Anda -->
              <div class="newsletter-form input-group">
                <input type="email" name="email_newsletter" class="form-control" placeholder="Masukkan alamat email Anda" required>
                <button type="submit" class="btn">Berlangganan Sekarang</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-12 footer-about">
          <a href="landing.php" class="logo d-flex align-items-center mb-3">
            <span class="sitename">TiketKu</span>
          </a>
          <p>TiketKu adalah platform terpercaya untuk menemukan dan membeli tiket berbagai acara menarik di seluruh Indonesia. Nikmati kemudahan dan keamanan bertransaksi bersama kami.</p>
          <div class="social-links d-flex mt-4">
            <a href="#" title="Twitter TiketKu"><i class="bi bi-twitter-x"></i></a>
            <a href="#" title="Facebook TiketKu"><i class="bi bi-facebook"></i></a>
            <a href="#" title="Instagram TiketKu"><i class="bi bi-instagram"></i></a>
            <a href="#" title="YouTube TiketKu"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Navigasi</h4>
          <ul>
            <li><a href="#hero">Beranda</a></li>
            <li><a href="#acara-populer">Acara</a></li>
            <li><a href="#about">Tentang Kami</a></li>
            <li><a href="faq.html">FAQ</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Layanan</h4>
          <ul>
            <li><a href="login.php">Beli Tiket</a></li>
            <li><a href="buat_event.php">Buat Event</a></li>
            <li><a href="#contact">Hubungi Kami</a></li>
            <li><a href="pusat_bantuan.html">Pusat Bantuan</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
          <h4>Kontak Kami</h4>
          <p>
            Gedung TiketKu Lantai 7 <br>
            Jl. Raya Acara No. 1, Suite 707<br>
            Jakarta Pusat, 10110<br>
            Indonesia <br><br>
            <strong>Telepon:</strong> +62 21 555 0123<br>
            <strong>Email:</strong> info@tiketku.com<br>
          </p>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© Hak Cipta <script>document.write(new Date().getFullYear());</script> <strong class="px-1 sitename">TiketKu</strong>. Seluruh Hak Dilindungi.</p>
      <div class="credits">
        Dirancang dengan <i class="bi bi-heart-fill text-danger"></i> oleh Tim TiketKu (Template dasar oleh <a href="https://bootstrapmade.com/">BootstrapMade</a>)
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    function composeWhatsAppContactMessage() {
        const name = document.getElementById('contact-name').value;
        const email = document.getElementById('contact-email').value;
        const subject = document.getElementById('contact-subject').value;
        const message = document.getElementById('contact-message').value;

        let waText = "Halo Tim Dukungan TiketKu,\n\n";
        waText += "Saya " + name + ".\n";
        if (email && email.trim() !== "") {
            waText += "Email saya: " + email + "\n";
        }
        waText += "Subjek: " + subject + "\n\n";
        waText += message + "\n\n";
        waText += "Terima kasih.";

        const hiddenInput = document.getElementById('whatsapp-message-contact');
        if (hiddenInput) {
            hiddenInput.value = waText;
        }
        return true;
    }

    const contactForm = document.querySelector('#contact .php-email-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            // Kita panggil compose di sini, karena action form adalah ke wa.me
            // Jika action adalah ke skrip PHP, kita mungkin perlu event.preventDefault() dan submit manual
            composeWhatsAppContactMessage();
        });
    }
  </script>

</body>
</html>