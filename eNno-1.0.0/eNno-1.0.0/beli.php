<?php
include 'config.php';
// include 'index3.php'; // Asumsikan index3.php berisi navbar atau header lain. Kita akan buat placeholder.

// Ambil semua kategori unik
$stmtKategori = $pdo->query("SELECT DISTINCT kategori FROM acara");
$kategoriList = $stmtKategori->fetchAll(PDO::FETCH_COLUMN);

// Filter kategori dan pencarian
$kategoriFilter = $_GET['kategori'] ?? '';
$searchKeyword = $_GET['search'] ?? '';

// Query dasar
$query = "SELECT * FROM acara WHERE 1=1";
$params = [];

if (!empty($kategoriFilter)) {
    $query .= " AND kategori = :kategori";
    $params[':kategori'] = $kategoriFilter;
}

if (!empty($searchKeyword)) {
    $query .= " AND judul LIKE :search";
    $params[':search'] = '%' . $searchKeyword . '%';
}

$query .= " ORDER BY tanggal_acara ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$acara = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil banner acara terdekat
$stmtBanner = $pdo->query("SELECT * FROM acara WHERE tanggal_acara >= CURDATE() ORDER BY tanggal_acara ASC LIMIT 1");
$upcomingEvent = $stmtBanner->fetch(PDO::FETCH_ASSOC);
$bannerImageUrl = ($upcomingEvent && !empty($upcomingEvent['foto'])) ? "uploads/" . htmlspecialchars($upcomingEvent['foto']) : "assets/img/banner-event-default.jpg"; // Sediakan default banner jika tidak ada
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beli Tiket Acara - EventTix</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet"> <!-- Jika Anda punya file main.css kustom -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #ff4d00; /* Oranye khas Anda */
      --secondary-color: #cc3c00; /* Oranye lebih gelap untuk hover */
      --text-color: #333;
      --light-gray: #f8f9fa;
      --border-radius: 0.75rem; /* Sedikit lebih bulat */
      --box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      --box-shadow-hover: 0 8px 16px rgba(0,0,0,0.15);
    }

    body {
      background-color: #fdfdfd; /* Sedikit off-white */
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-color);
      line-height: 1.6;
    }

    /* Navbar Placeholder Styling */
    .navbar-custom {
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      padding: 0.8rem 1rem;
    }
    .navbar-custom .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: var(--primary-color);
    }
    .navbar-custom .nav-link {
      color: #555;
      font-weight: 500;
      margin-left: 15px;
      transition: color 0.3s ease;
    }
    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
      color: var(--primary-color);
    }
    .navbar-custom .btn-login {
        background-color: var(--primary-color);
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .navbar-custom .btn-login:hover {
        background-color: var(--secondary-color);
        color: white;
    }


    /* Hero Section */
    .hero-section {
      background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)), url('<?= $bannerImageUrl ?>');
      background-size: cover;
      background-position: center;
      padding: 80px 0;
      color: white;
      text-align: center;
      min-height: 450px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      border-bottom-left-radius: 30px;
      border-bottom-right-radius: 30px;
      margin-bottom: 2rem; /* Jarak dari banner ke konten */
    }
    .hero-section h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .hero-section p.lead {
      font-size: 1.3rem;
      font-weight: 300;
      max-width: 600px;
      margin: 0 auto 1.5rem auto;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }


    /* Section Title */
    .section-title {
      font-size: 2.2rem;
      text-align: center;
      font-weight: 600;
      margin-bottom: 30px;
      color: var(--text-color);
      position: relative;
      padding-bottom: 15px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        display: block;
        width: 80px;
        height: 4px;
        background: var(--primary-color);
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    /* Filter and Search Form */
    .filter-search-section {
      background-color: var(--light-gray);
      padding: 25px;
      border-radius: var(--border-radius);
      margin-bottom: 40px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .filter-search-section .form-control,
    .filter-search-section .form-select {
      border-radius: 50px; /* Rounded inputs */
      padding: 0.6rem 1rem;
      border: 1px solid #ddd;
    }
    .filter-search-section .form-control:focus,
    .filter-search-section .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.25);
    }
    .filter-search-section .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      border-radius: 50px;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .filter-search-section .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      transform: translateY(-2px);
    }

    /* Card Styling */
    .card {
      border: none;
      border-radius: var(--border-radius);
      overflow: hidden;
      transition: all 0.3s ease-in-out;
      box-shadow: var(--box-shadow);
      background-color: #fff;
    }
    .card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: var(--box-shadow-hover);
    }
    .card-img-top {
      height: 220px;
      object-fit: cover;
    }
    .card-body {
        padding: 1.5rem;
    }
    .card-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-color);
      margin-bottom: 0.5rem;
    }
    .badge-event {
      background-color: var(--primary-color);
      opacity: 0.85;
      color: white;
      padding: 6px 12px;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
      display: inline-block;
      margin-bottom: 0.75rem;
    }
    .card-text {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 0.5rem;
    }
    .card-text i {
        color: var(--primary-color);
        margin-right: 8px;
    }
    .card-text strong {
        color: var(--text-color);
    }
    .btn-beli {
      background-color: var(--primary-color);
      color: white;
      font-weight: 600;
      border-radius: 50px;
      padding: 0.6rem 1.5rem;
      transition: all 0.3s ease;
      border: none;
      display: block; /* Make it full width or use text-center on parent */
      text-align: center;
    }
    .btn-beli:hover {
      background-color: var(--secondary-color);
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(255, 77, 0, 0.3);
    }

    /* No Events Found */
    .no-events {
        text-align: center;
        padding: 50px 20px;
        background-color: var(--light-gray);
        border-radius: var(--border-radius);
    }
    .no-events i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    .no-events p {
        font-size: 1.2rem;
        color: #666;
    }


    /* Footer Styling */
    .footer {
      background: #222; /* Darker footer */
      color: #ccc;
      padding: 60px 0 20px 0;
      font-size: 14px;
    }
    .footer h4 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #fff;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .footer .footer-newsletter {
        background-color: #333;
        padding: 30px;
        border-radius: var(--border-radius);
        margin-bottom: 40px;
    }
    .footer .footer-newsletter h4{
        color: var(--primary-color);
        text-align: center;
    }
    .footer .footer-newsletter p{
        color: #ddd;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .footer .footer-newsletter form {
        display: flex;
        justify-content: center;
    }
    .footer .footer-newsletter input[type="email"] {
      padding: 10px 15px;
      width: 70%;
      max-width: 300px;
      border: 1px solid #444;
      background-color: #2a2a2a;
      color: #fff;
      border-radius: 50px 0 0 50px;
    }
    .footer .footer-newsletter input[type="submit"] {
      padding: 10px 25px;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 0 50px 50px 0;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .footer .footer-newsletter input[type="submit"]:hover {
        background: var(--secondary-color);
    }
    .footer ul {
      padding-left: 0;
      list-style: none;
    }
    .footer ul li {
      margin-bottom: 10px;
    }
    .footer ul li a {
      color: #aaa;
      transition: color 0.3s ease;
      text-decoration: none;
    }
    .footer ul li a:hover {
      color: var(--primary-color);
    }
    .footer .social-links a {
      color: #aaa;
      font-size: 22px;
      margin-right: 15px;
      transition: color 0.3s ease;
    }
    .footer .social-links a:hover {
      color: var(--primary-color);
    }
    .footer .copyright-text {
        border-top: 1px solid #444;
        padding-top: 20px;
        margin-top: 30px;
        font-size: 0.9rem;
    }
    .footer .copyright-text a {
        color: var(--primary-color);
        text-decoration: none;
    }
    .footer .copyright-text a:hover {
        text-decoration: underline;
    }

    /* AOS Animation tweaks - pastikan AOS diinisialisasi */
    [data-aos] {
        transition-property: transform, opacity, box-shadow; /* include box-shadow if you want it animated by AOS */
    }
  </style>
</head>
<body>

<!-- Navbar Placeholder -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">TiketKu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php?page=beli">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=contact">contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=profil">profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">logout</a>
        </li>
      </ul>
    
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container" data-aos="fade-up">
    <h1>Temukan Acara Terbaikmu</h1>
    <p class="lead">Jelajahi ribuan konser, festival, workshop, dan acara seru lainnya di EventTix. Pengalaman tak terlupakan menantimu!</p>
    <a href="#event-list" class="btn btn-lg btn-beli">Lihat Semua Acara <i class="bi bi-arrow-down-circle-fill ms-2"></i></a>
  </div>
</section>


<div class="container mt-5">

  <!-- Filter dan Pencarian -->
  <section class="filter-search-section" data-aos="fade-up" data-aos-delay="100">
    <h3 class="text-center mb-4 fw-semibold">Cari Acara Impianmu</h3>
    <form method="GET" class="row g-3 align-items-center justify-content-center">
      <div class="col-md-5">
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik nama acara, artis, atau tempat..." value="<?= htmlspecialchars($searchKeyword) ?>">
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags-fill"></i></span>
            <select name="kategori" class="form-select border-start-0">
              <option value="">Semua Kategori</option>
              <?php foreach ($kategoriList as $kategori): ?>
                <option value="<?= htmlspecialchars($kategori) ?>" <?= $kategoriFilter === $kategori ? 'selected' : '' ?>>
                  <?= htmlspecialchars($kategori) ?>
                </option>
              <?php endforeach; ?>
            </select>
        </div>
      </div>
      <div class="col-md-auto text-center">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-funnel-fill me-1"></i> Filter
        </button>
      </div>
    </form>
  </section>

  <!-- Daftar Acara -->
  <section id="event-list" class="mt-5">
    <h2 class="section-title" data-aos="fade-up">Pilihan Acara Untukmu</h2>
    <div class="row">
      <?php if (!empty($acara)): ?>
        <?php foreach ($acara as $index => $item): ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 + 200 ?>">
              <?php if (!empty($item['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['judul']) ?>">
              <?php else: ?>
                <img src="assets/img/default-event.jpg" class="card-img-top" alt="Gambar Default Acara"> <!-- Sediakan gambar default event -->
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <span class="badge-event"><?= htmlspecialchars($item['kategori']) ?></span>
                <h5 class="card-title mt-2"><?= htmlspecialchars($item['judul']) ?></h5>
                <p class="card-text mb-1"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($item['lokasi']) ?></p>
                <p class="card-text mb-1"><i class="bi bi-calendar-event-fill"></i> <?= date('d M Y, H:i', strtotime($item['tanggal_acara'])) ?> WIB</p>
                <p class="card-text mb-3"><strong>Harga:</strong> Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                <a href="checkout.php?id=<?= $item['id_acara'] ?>" class="btn btn-beli mt-auto">
                  <i class="bi bi-ticket-detailed-fill me-2"></i>Beli Tiket
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
            <div class="no-events" data-aos="fade-up">
                <i class="bi bi-emoji-frown"></i>
                <p>Oops! Tidak ada acara yang sesuai dengan pencarianmu.</p>
                <p class="text-muted small">Coba kata kunci atau filter kategori yang lain.</p>
            </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</div>

<!-- Footer -->
<footer class="footer mt-5">
  <div class="container">
    <div class="footer-newsletter" data-aos="fade-up">
      <h4>Langganan Newsletter Kami!</h4>
      <p>Dapatkan info acara terbaru, diskon eksklusif, dan berita menarik langsung ke email kamu.</p>
      <form action="forms/newsletter.php" method="post">
        <input type="email" name="email" placeholder="Alamat email kamu..." required>
        <input type="submit" value="Subscribe">
      </form>
    </div>

    <div class="row footer-top" data-aos="fade-up" data-aos-delay="100">
      <div class="col-lg-3 col-md-6 mb-4">
        <h4>EventTix</h4>
        <p>Platform terpercaya untuk menemukan dan membeli tiket berbagai acara menarik. Pengalaman tak terlupakan dimulai di sini.</p>
      </div>
      <div class="col-lg-2 col-md-3 col-6 mb-4">
        <h4>Link Cepat</h4>
        <ul>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Beranda</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Semua Acara</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Tentang Kami</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> FAQ</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6 mb-4">
        <h4>Bantuan</h4>
        <ul>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Cara Beli Tiket</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Syarat & Ketentuan</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Kebijakan Privasi</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Hubungi Kami</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 mb-4">
        <h4>Kategori Populer</h4>
        <ul>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Konser Musik</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Festival</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Workshop</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Olahraga</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h4>Ikuti Kami</h4>
        <p>Dapatkan update terbaru melalui sosial media kami:</p>
        <div class="social-links d-flex mt-3">
          <a href="#" class="me-3"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
          <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
          <a href="#" class="me-3"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container text-center copyright-text">
    <p>© <?= date('Y') ?> <strong><span>EventTix</span></strong>. All Rights Reserved.</p>
    <p>Designed with <i class="bi bi-heart-fill text-danger"></i> by <a href="https://bootstrapmade.com/">BootstrapMade</a> & Enhanced</p>
  </div>
</footer>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800, // Durasi animasi
    once: true,    // Animasi hanya sekali
    offset: 50     // Offset (dalam px) dari original trigger point
  });

  // Smooth scroll untuk link internal (jika ada)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      if (this.hash !== "") {
        e.preventDefault();
        const hash = this.hash;
        const targetElement = document.querySelector(hash);
        if(targetElement){
            const headerOffset = 70; // Sesuaikan jika ada fixed navbar
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: "smooth"
            });
        }
      }
    });
  });
</script>
</body>
</html>