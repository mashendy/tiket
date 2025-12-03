<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}
$id_member_session = $_SESSION['id_member'];

require_once 'config.php';

// Ambil data riwayat tiket/transaksi untuk member yang login
// Urutkan berdasarkan tanggal transaksi terbaru
try {
    $stmt = $pdo->prepare("SELECT
                               t.id_tiket,
                               t.kode_tiket,
                               t.jumlah AS jumlah_tiket_dibeli,
                               t.status_tiket,
                               a.judul AS judul_acara,
                               a.tanggal_acara,
                               a.lokasi AS lokasi_acara,
                               a.foto AS foto_acara,
                               p.id_pembayaran,
                               p.total_harga AS total_pembayaran,
                               p.status_pembayaran,
                               p.tanggal_pembayaran AS tanggal_transaksi
                           FROM tiket t
                           JOIN acara a ON t.id_acara = a.id_acara
                           LEFT JOIN pembayaran p ON t.id_tiket = p.id_tiket -- LEFT JOIN jika mungkin ada tiket tanpa pembayaran (jarang)
                           WHERE t.id_member = :id_member
                           ORDER BY p.tanggal_pembayaran DESC, t.id_tiket DESC"); // Urutkan berdasarkan tanggal pembayaran terbaru
    $stmt->bindParam(':id_member', $id_member_session, PDO::PARAM_INT);
    $stmt->execute();
    $riwayat_tiket = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error (Riwayat Tiket): " . $e->getMessage());
    // Tampilkan pesan error umum ke pengguna atau redirect
    $riwayat_tiket = []; // Kosongkan array agar halaman tidak error
    $page_error = "Terjadi kesalahan saat mengambil data riwayat tiket Anda. Silakan coba lagi nanti.";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Tiket Saya - TiketKu</title>

  <link href="assets/img/favicon_tiketku.png" rel="icon">
  <link href="assets/img/apple-touch-icon_tiketku.png" rel="apple-touch-icon">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #ff4d00;
      --secondary-color: #cc3c00;
      --text-color: #333;
      --light-gray: #f8f9fa;
      --border-radius: 0.75rem;
      --box-shadow: 0 6px 12px rgba(0,0,0,0.08);
      --success-color: #198754;
      --warning-color: #ffc107;
      --pending-color: #fd7e14;
      --danger-color: #dc3545;
    }
    body { background-color: var(--light-gray); font-family: 'Poppins', sans-serif; color: var(--text-color); line-height: 1.6; }
    .page-container { padding-top: 2rem; padding-bottom: 4rem; }
    .page-header { text-align: center; margin-bottom: 30px; }
    .page-header h1 { font-weight: 600; color: var(--primary-color); font-size: 2.2rem; }
    .page-header h1 i { margin-right: 10px; }

    .ticket-card {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 25px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .ticket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }
    .ticket-card-header {
        padding: 15px 20px;
        background-color: var(--light-gray);
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ticket-card-header .event-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }
    .ticket-card-header .transaction-date {
        font-size: 0.85rem;
        color: #777;
    }

    .ticket-card-body {
        padding: 20px;
        display: flex;
        gap: 20px; /* Jarak antara gambar dan detail */
    }
    .ticket-event-img {
        width: 120px; /* Lebar gambar tetap */
        height: 120px; /* Tinggi gambar tetap */
        object-fit: cover;
        border-radius: 0.5rem;
        flex-shrink: 0; /* Agar gambar tidak menyusut */
    }
    .ticket-details {
        flex-grow: 1; /* Agar detail mengisi sisa ruang */
    }
    .ticket-details p {
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #555;
    }
    .ticket-details p strong {
        color: var(--text-color);
        min-width: 120px; /* Untuk alignment */
        display: inline-block;
    }
    .ticket-code {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        color: var(--secondary-color);
        background-color: #fff3e0; /* Warna latar belakang lembut untuk kode tiket */
        padding: 2px 6px;
        border-radius: 4px;
    }

    .status-badge {
        padding: 0.3em 0.7em;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
        text-transform: capitalize;
    }
    .status-menunggu { background-color: var(--warning-color); color: var(--text-color); }
    .status-pending { background-color: var(--pending-color); }
    .status-selesai { background-color: var(--success-color); }
    .status-gagal { background-color: var(--danger-color); }
    .status-dibatalkan { background-color: #6c757d; }
    .status-belum_digunakan { background-color: var(--success-color); } /* Untuk status tiket */
    .status-sudah_digunakan { background-color: #6c757d; } /* Untuk status tiket */


    .ticket-card-footer {
        padding: 15px 20px;
        background-color: var(--light-gray);
        border-top: 1px solid #e0e0e0;
        text-align: right;
    }
    .btn-ticket-action {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        margin-left: 10px;
        border-radius: 50px;
        font-weight: 500;
        text-decoration: none;
    }
    .btn-view-payment {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-color);
    }
    .btn-view-payment:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
    }
    .btn-download-eticket {
        background-color: var(--success-color);
        color: white;
        border: 1px solid var(--success-color);
    }
    .btn-download-eticket:hover {
        background-color: #157347;
        border-color: #146c43;
        color: white;
    }
    .btn-disabled {
        opacity: 0.65;
        pointer-events: none;
    }

    .no-tickets {
        text-align: center;
        padding: 50px 20px;
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    .no-tickets i {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    .no-tickets p {
        font-size: 1.2rem;
        color: #666;
    }
    .no-tickets .btn-find-events {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    .no-tickets .btn-find-events:hover {
        background-color: var(--secondary-color);
    }


    .footer { background: #222; color: #ccc; padding: 60px 0 20px 0; font-size: 14px; margin-top: 3rem; }
    .footer h4 { font-weight: 600; margin-bottom: 20px; color: #fff; font-size: 1.1rem; }
    .footer ul { padding-left: 0; list-style: none; }
    .footer ul li { margin-bottom: 10px; }
    .footer ul li a { color: #aaa; transition: color 0.3s ease; text-decoration: none; }
    .footer ul li a:hover { color: var(--primary-color); }
    .footer .social-links a { color: #aaa; font-size: 22px; margin-right: 15px; transition: color 0.3s ease; }
    .footer .social-links a:hover { color: var(--primary-color); }
    .footer .copyright-text { border-top: 1px solid #444; padding-top: 20px; margin-top: 30px; font-size: 0.9rem; }
    .footer .copyright-text a { color: var(--primary-color); text-decoration: none; }
  </style>
</head>
<body>

<?php
if (file_exists('index3.php')) {
    include_once 'index3.php';
} else {
    // Fallback navbar
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
            <div class="container">
              <a class="navbar-brand" href="index.php" style="color: var(--primary-color); font-weight: bold; font-size: 1.5rem;">TiketKu</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavFallback" aria-controls="navbarNavFallback" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavFallback">
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                  <li class="nav-item"><a class="nav-link" href="semua_acara.php">Semua Acara</a></li>';
    if (isset($_SESSION['id_member'])) {
        echo '<li class="nav-item"><a class="nav-link active" href="riwayat_tiket.php">Riwayat Tiket</a></li>
              <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
              <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
    } else {
        echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
    }
    echo '</ul></div></div></nav><div style="padding-top: 70px;"></div>';
}
?>

<div class="container page-container">
    <div class="page-header" data-aos="fade-down">
        <h1><i class="bi bi-receipt"></i>Riwayat Tiket Saya</h1>
        <p class="lead">Lihat semua tiket acara yang pernah Anda beli.</p>
    </div>

    <?php if (isset($page_error)): ?>
        <div class="alert alert-danger text-center" data-aos="fade-up"><?= htmlspecialchars($page_error) ?></div>
    <?php endif; ?>

    <?php if (empty($riwayat_tiket) && !isset($page_error)): ?>
        <div class="no-tickets" data-aos="fade-up" data-aos-delay="100">
            <i class="bi bi-ticket-detailed"></i>
            <p>Anda belum memiliki riwayat pembelian tiket.</p>
            <a href="semua_acara.php" class="btn-find-events">
                <i class="bi bi-search me-2"></i>Cari Acara Seru Sekarang!
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($riwayat_tiket as $index => $tiket): ?>
            <div class="ticket-card" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                <div class="ticket-card-header">
                    <h5 class="event-title"><?= htmlspecialchars($tiket['judul_acara']) ?></h5>
                    <?php if (!empty($tiket['tanggal_transaksi'])): ?>
                        <span class="transaction-date">
                            Transaksi: <?= htmlspecialchars(date('d M Y, H:i', strtotime($tiket['tanggal_transaksi']))) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="ticket-card-body">
                    <?php
                        $gambar_acara = 'assets/img/default-event.jpg'; // Gambar default
                        if (!empty($tiket['foto_acara']) && file_exists('uploads/' . $tiket['foto_acara'])) {
                            $gambar_acara = 'uploads/' . htmlspecialchars($tiket['foto_acara']);
                        }
                    ?>
                    <img src="<?= $gambar_acara ?>" alt="Gambar Acara" class="ticket-event-img">
                    <div class="ticket-details">
                        <p><strong>Tanggal Acara:</strong> <?= htmlspecialchars(date('d F Y', strtotime($tiket['tanggal_acara']))) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($tiket['lokasi_acara']) ?></p>
                        <p><strong>Jumlah Tiket:</strong> <?= htmlspecialchars($tiket['jumlah_tiket_dibeli']) ?></p>
                        <?php if (isset($tiket['total_pembayaran'])): ?>
                        <p><strong>Total Bayar:</strong> <span style="color: var(--primary-color); font-weight: 600;">Rp <?= number_format($tiket['total_pembayaran'], 0, ',', '.') ?></span></p>
                        <?php endif; ?>
                        <?php if (isset($tiket['status_pembayaran'])): ?>
                        <p>
                            <strong>Status Pembayaran:</strong>
                            <span class="status-badge status-<?= htmlspecialchars(strtolower($tiket['status_pembayaran'])) ?>">
                                <?= htmlspecialchars($tiket['status_pembayaran']) ?>
                            </span>
                        </p>
                        <?php endif; ?>
                        <?php if ($tiket['status_pembayaran'] == 'selesai' && !empty($tiket['kode_tiket'])): ?>
                        <p><strong>Kode Tiket:</strong> <span class="ticket-code"><?= htmlspecialchars($tiket['kode_tiket']) ?></span></p>
                        <p>
                            <strong>Status Tiket:</strong>
                            <span class="status-badge status-<?= htmlspecialchars(strtolower($tiket['status_tiket'])) ?>">
                                <?= str_replace('_', ' ', htmlspecialchars($tiket['status_tiket'])) ?>
                            </span>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ticket-card-footer">
                    <?php if (isset($tiket['id_pembayaran'])): ?>
                    <a href="bukti_pembayaran.php?id=<?= $tiket['id_pembayaran'] ?>" class="btn btn-sm btn-ticket-action btn-view-payment">
                        <i class="bi bi-eye-fill me-1"></i>Lihat Pembayaran
                    </a>
                    <?php endif; ?>

                    <?php if ($tiket['status_pembayaran'] == 'selesai' && $tiket['status_tiket'] == 'belum_digunakan'): ?>
                    <a href="download_etiket.php?id_tiket=<?= $tiket['id_tiket'] ?>" class="btn btn-sm btn-ticket-action btn-download-eticket" title="Unduh E-Tiket">
                        <i class="bi bi-download me-1"></i>E-Tiket
                    </a>
                    <?php else: ?>
                    <button class="btn btn-sm btn-ticket-action btn-download-eticket btn-disabled" title="E-Tiket belum tersedia" disabled>
                        <i class="bi bi-download me-1"></i>E-Tiket
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<footer class="footer">
  <div class="container">
    <div class="row footer-top" data-aos="fade-up">
      <div class="col-lg-4 col-md-6 mb-4"><h4>TiketKu</h4><p>Platform terpercaya untuk menemukan dan membeli tiket berbagai acara menarik.</p></div>
      <div class="col-lg-2 col-md-3 col-6 mb-4"><h4>Navigasi</h4><ul><li><a href="index.php"><i class="bi bi-chevron-right"></i> Beranda</a></li><li><a href="semua_acara.php"><i class="bi bi-chevron-right"></i> Semua Acara</a></li></ul></div>
      <div class="col-lg-2 col-md-3 col-6 mb-4"><h4>Akun Saya</h4><ul><li><a href="profil.php"><i class="bi bi-chevron-right"></i> Profil</a></li><li><a href="riwayat_tiket.php"><i class="bi bi-chevron-right"></i> Tiket Saya</a></li></ul></div>
      <div class="col-lg-4 col-md-12"><h4>Ikuti Kami</h4><p>Dapatkan update terbaru.</p><div class="social-links d-flex mt-3"><a href="#" class="me-3"><i class="bi bi-twitter-x"></i></a><a href="#" class="me-3"><i class="bi bi-facebook"></i></a><a href="#" class="me-3"><i class="bi bi-instagram"></i></a></div></div>
    </div>
  </div>
  <div class="container text-center copyright-text"><p>© <?= date('Y') ?> <strong><span>TiketKu</span></strong>. All Rights Reserved.</p></div>
</footer>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 600, once: true, offset: 50 });
</script>
</body>
</html>