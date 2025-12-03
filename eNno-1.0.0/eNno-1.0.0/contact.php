<?php
session_start();
include 'config.php'; // Untuk koneksi DB jika diperlukan di masa depan, atau variabel global

// Logika untuk mengirim email (contoh sederhana, perlu disesuaikan dengan mailer Anda)
$message_sent = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $message_body = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($subject) || empty($message_body)) {
        $error_message = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        // Proses pengiriman email
        // Ini adalah placeholder. Anda perlu menggunakan library seperti PHPMailer atau fungsi mail() PHP
        // dengan konfigurasi server email yang benar.
        $to = "admin@tiketku.com"; // Ganti dengan email tujuan Anda
        $headers = "From: " . $name . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // $full_message = "Nama: " . $name . "\n";
        // $full_message .= "Email: " . $email . "\n";
        // $full_message .= "Subjek: " . $subject . "\n\n";
        // $full_message .= "Pesan:\n" . $message_body;

        // if (mail($to, "Pesan dari Kontak Form TiketKu: " . $subject, $full_message, $headers)) {
        //     $message_sent = true;
        // } else {
        //     $error_message = "Gagal mengirim pesan. Silakan coba lagi nanti.";
        // }

        // Untuk sekarang, kita simulasikan berhasil
        $message_sent = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Hubungi Kami - TiketKu</title>
  <meta content="Hubungi tim TiketKu untuk pertanyaan, dukungan, atau kerjasama." name="description">
  <meta content="kontak, tiketku, hubungi, support, kerjasama" name="keywords">

  <!-- Favicons (Sesuaikan dengan favicon TiketKu Anda) -->
  <link href="assets/img/favicon_tiketku.png" rel="icon"> <!-- Ganti nama file jika perlu -->
  <link href="assets/img/apple-touch-icon_tiketku.png" rel="apple-touch-icon"> <!-- Ganti nama file jika perlu -->

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Main CSS File (Gaya TiketKu) -->
  <style>
    :root {
      --primary-color: #ff4d00; /* Warna utama TiketKu */
      --secondary-color: #cc3c00; /* Warna sekunder TiketKu */
      --text-color: #333;
      --light-gray: #f8f9fa;
      --border-radius: 0.75rem;
      --box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }

    body {
      background-color: #fdfdfd;
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-color);
      line-height: 1.6;
    }

    .navbar-custom {
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      padding: 0.8rem 1rem;
    }
    .navbar-custom .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: var(--primary-color); /* Menggunakan warna utama TiketKu */
    }
    .navbar-custom .nav-link {
      color: #555;
      font-weight: 500;
      margin-left: 15px;
    }
    .navbar-custom .nav-link.active, .navbar-custom .nav-link:hover {
      color: var(--primary-color); /* Menggunakan warna utama TiketKu */
    }
     .navbar-custom .btn-login {
        background-color: var(--primary-color); /* Menggunakan warna utama TiketKu */
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
    }
    .navbar-custom .btn-login:hover {
        background-color: var(--secondary-color); /* Menggunakan warna sekunder TiketKu */
        color: white;
    }

    .page-header {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2)), url('assets/img/contact-banner-tiketku.jpg'); /* Ganti dengan gambar banner kontak TiketKu */
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        color: white;
        text-align: center;
        border-bottom-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
    }
    .page-header h1 {
        font-size: 2.8rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .page-header p {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.9;
    }

    .section-contact {
        padding: 60px 0;
    }
    .contact-form-card, .contact-info-card {
        background-color: #fff;
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        height: 100%;
    }
    .contact-form-card h3, .contact-info-card h3 {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--primary-color); /* Menggunakan warna utama TiketKu */
        margin-bottom: 25px;
        text-align: center;
    }

    .contact-form .form-label {
        font-weight: 500;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
    }
    .contact-form .form-control {
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .contact-form .form-control:focus {
        border-color: var(--primary-color); /* Menggunakan warna utama TiketKu */
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.25); /* Sesuaikan RGBA jika perlu */
    }
    .contact-form textarea.form-control {
        min-height: 150px;
    }
    .btn-submit-message {
      background-color: var(--primary-color); /* Menggunakan warna utama TiketKu */
      border-color: var(--primary-color); /* Menggunakan warna utama TiketKu */
      color: white;
      font-weight: 600;
      border-radius: 50px;
      padding: 0.75rem 2rem;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    .btn-submit-message:hover {
      background-color: var(--secondary-color); /* Menggunakan warna sekunder TiketKu */
      border-color: var(--secondary-color); /* Menggunakan warna sekunder TiketKu */
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(255, 77, 0, 0.3); /* Sesuaikan RGBA jika perlu */
    }

    .contact-info-card ul {
        list-style: none;
        padding: 0;
    }
    .contact-info-card ul li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        font-size: 1rem;
    }
    .contact-info-card ul li i {
        font-size: 1.8rem;
        color: var(--primary-color); /* Menggunakan warna utama TiketKu */
        margin-right: 15px;
        width: 30px;
        text-align: center;
    }
    .contact-info-card ul li div h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-color);
    }
    .contact-info-card ul li div p {
        margin-bottom: 0;
        color: #555;
    }
    .contact-info-card .map-responsive {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
        border-radius: var(--border-radius);
        margin-top: 20px;
    }
    .contact-info-card .map-responsive iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
        border:0;
    }

    .alert-fixed-contact {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        min-width: 300px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .footer {
      background: #222;
      color: #ccc;
      padding: 60px 0 20px 0;
      font-size: 14px;
      margin-top: 3rem;
    }
    .footer h4 { font-weight: 600; margin-bottom: 20px; color: #fff; font-size: 1.1rem; }
    .footer ul { padding-left: 0; list-style: none; }
    .footer ul li { margin-bottom: 10px; }
    .footer ul li a { color: #aaa; transition: color 0.3s ease; text-decoration: none; }
    .footer ul li a:hover { color: var(--primary-color); } /* Menggunakan warna utama TiketKu */
    .footer .social-links a { color: #aaa; font-size: 22px; margin-right: 15px; transition: color 0.3s ease; }
    .footer .social-links a:hover { color: var(--primary-color); } /* Menggunakan warna utama TiketKu */
    .footer .copyright-text { border-top: 1px solid #444; padding-top: 20px; margin-top: 30px; font-size: 0.9rem; }
    .footer .copyright-text a { color: var(--primary-color); text-decoration: none; } /* Menggunakan warna utama TiketKu */

    .back-to-top {
      position: fixed;
      visibility: hidden;
      opacity: 0;
      right: 15px;
      bottom: 15px;
      z-index: 996;
      background: var(--primary-color); /* Menggunakan warna utama TiketKu */
      width: 40px;
      height: 40px;
      border-radius: 50px;
      transition: all 0.4s;
    }
    .back-to-top i {
      font-size: 24px;
      color: #fff;
      line-height: 0;
    }
    .back-to-top:hover {
      background: var(--secondary-color); /* Menggunakan warna sekunder TiketKu */
      color: #fff;
    }
    .back-to-top.active {
      visibility: visible;
      opacity: 1;
    }
  </style>
</head>

<body>

<?php include_once 'index3.php'; // Memanggil navbar dari index3.php (pastikan index3.php juga menggunakan "TiketKu") ?>

<header class="page-header" data-aos="fade-in">
    <div class="container">
        <h1>Hubungi Kami</h1>
        <p>Ada pertanyaan atau butuh bantuan? Tim TiketKu siap membantu Anda.</p>
    </div>
</header>

<main id="main">
    <section class="section-contact">
      <div class="container">

        <?php if ($message_sent): ?>
            <div class="alert alert-success alert-dismissible fade show alert-fixed-contact" role="alert" data-aos="fade-down">
                <strong>Pesan Terkirim!</strong> Terima kasih telah menghubungi kami. Kami akan segera merespons Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show alert-fixed-contact" role="alert" data-aos="fade-down">
                <strong>Oops!</strong> <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row gy-4">

          <div class="col-lg-6" data-aos="fade-right">
            <div class="contact-info-card">
              <h3>Informasi Kontak TiketKu</h3>
              <ul>
                <li>
                  <i class="bi bi-geo-alt-fill"></i>
                  <div>
                    <h5>Alamat Kami:</h5>
                    <p>Jl. TiketKu No. 101, Jakarta Pusat,<br>DKI Jakarta, Indonesia 10210</p>
                  </div>
                </li>
                <li>
                  <i class="bi bi-envelope-fill"></i>
                  <div>
                    <h5>Email Kami:</h5>
                    <p>support@tiketku.com (Dukungan)<br>partnership@tiketku.com (Kerjasama)</p>
                  </div>
                </li>
                <li>
                  <i class="bi bi-telephone-fill"></i>
                  <div>
                    <h5>Telepon:</h5>
                    <p>+62 21 8765 4321 (Jam Kerja)<br>+62 898 7654 3210 (WhatsApp Support)</p>
                  </div>
                </li>
                 <li>
                  <i class="bi bi-clock-fill"></i>
                  <div>
                    <h5>Jam Operasional:</h5>
                    <p>Senin - Jumat: 09:00 - 17:00 WIB<br>Sabtu: 09:00 - 13:00 WIB</p>
                  </div>
                </li>
              </ul>
              <div class="map-responsive">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521200537483!2d106.81924731534983!3d-6.194748995514927!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f427b0a7a7e1%3A0x29e630250dd9e898!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1600000000000!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
            <div class="contact-form-card">
              <h3>Kirim Pesan Langsung ke TiketKu</h3>
              <form action="contact.php" method="POST" class="contact-form">
                <div class="row gy-3">
                  <div class="col-md-6">
                    <label for="name" class="form-label">Nama Lengkap Anda</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Contoh: Budi Santoso" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                  </div>
                  <div class="col-md-6 ">
                    <label for="email" class="form-label">Alamat Email Anda</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Contoh: budi@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                  </div>
                  <div class="col-md-12">
                    <label for="subject" class="form-label">Subjek Pesan</label>
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Contoh: Pertanyaan Tiket" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                  </div>
                  <div class="col-md-12">
                    <label for="message" class="form-label">Isi Pesan Anda</label>
                    <textarea class="form-control" name="message" id="message" rows="6" placeholder="Tuliskan pesan Anda di sini..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                  </div>
                  <div class="col-md-12 text-center mt-3">
                    <button type="submit" name="send_message" class="btn-submit-message">
                        <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </section>
</main>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="row footer-top" data-aos="fade-up">
      <div class="col-lg-4 col-md-6 mb-4">
        <h4>TiketKu</h4>
        <p>Platform terpercaya untuk menemukan dan membeli tiket berbagai acara menarik. Pengalaman tak terlupakan dimulai di sini.</p>
      </div>
      <div class="col-lg-2 col-md-3 col-6 mb-4">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="index.php"><i class="bi bi-chevron-right"></i> Beranda</a></li>
          <li><a href="semua_acara.php"><i class="bi bi-chevron-right"></i> Semua Acara</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Tentang Kami</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-6 mb-4">
        <h4>Bantuan</h4>
        <ul>
          <li><a href="contact.php"><i class="bi bi-chevron-right"></i> Hubungi Kami</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> FAQ</a></li>
          <li><a href="#"><i class="bi bi-chevron-right"></i> Syarat & Ketentuan</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-12">
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
    <p>© <?= date('Y') ?> <strong><span>TiketKu</span></strong>. All Rights Reserved.</p>
    <p>Inspired by <a href="https://bootstrapmade.com/">BootstrapMade</a></p>
  </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Main JS File (jika ada script kustom TiketKu) -->
<script>
  AOS.init({
    duration: 800,
    once: true,
    offset: 50
  });

  const backToTop = document.querySelector('.back-to-top');
  if (backToTop) {
    const toggleBackToTop = () => {
      if (window.scrollY > 100) {
        backToTop.classList.add('active');
      } else {
        backToTop.classList.remove('active');
      }
    }
    window.addEventListener('load', toggleBackToTop);
    document.addEventListener('scroll', toggleBackToTop);
  }

  window.setTimeout(function() {
    let alerts = document.querySelectorAll('.alert-fixed-contact');
    alerts.forEach(function(alert) {
        if (bootstrap.Alert.getInstance(alert)) {
            bootstrap.Alert.getInstance(alert).close();
        }
    });
  }, 7000);

  <?php if ($message_sent): ?>
    document.querySelector('.contact-form').reset();
  <?php endif; ?>
</script>

</body>
</html>