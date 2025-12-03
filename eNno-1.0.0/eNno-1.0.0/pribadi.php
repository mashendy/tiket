<?php
session_start();
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['member_id'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika belum login
    exit;
}

$member_id = $_SESSION['member_id'];

// Ambil tiket yang sudah dibeli oleh member
$stmt = $pdo->prepare("SELECT p.id_pembayaran, a.judul, p.dibayar_pada, p.metode_pembayaran
                       FROM pembayaran p
                       JOIN acara a ON p.id_tiket = a.id_acara
                       WHERE p.id_member = :member_id");
$stmt->bindParam(':member_id', $member_id);
$stmt->execute();
$tiket = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tiket Saya - EventTix</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .ticket-card {
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
      background-color: #ffffff;
    }
    .ticket-card h5 {
      font-weight: bold;
      color: #ff4d00;
    }
    .ticket-card .card-body {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">EventTix</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="beli.php">🏠 Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="pribadi.php">🎫 Tiket Saya</a></li>
        <li class="nav-item"><a class="nav-link" href="">📅 Event</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">👤 Profil</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">🚪 Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h2 class="text-center mb-4">Tiket Saya</h2>
  
  <?php if (count($tiket) > 0): ?>
    <div class="row">
      <?php foreach ($tiket as $item): ?>
        <div class="col-md-4">
          <div class="card ticket-card">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($item['judul']) ?></h5>
              <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($item['metode_pembayaran']) ?></p>
              <p><strong>Tanggal Pembayaran:</strong> <?= date('d M Y', strtotime($item['dibayar_pada'])) ?></p>
              <a href="checkout.php?id=<?= $item['id_pembayaran'] ?>" class="btn btn-warning">Lihat Detail</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-center">Anda belum membeli tiket apapun.</p>
  <?php endif; ?>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
