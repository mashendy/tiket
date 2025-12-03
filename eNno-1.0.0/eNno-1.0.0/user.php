<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EventTix - Beli Tiket Acara</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar {
      background: linear-gradient(90deg, #111, #333);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .carousel-caption {
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(4px);
      border-radius: 12px;
      padding: 1rem;
    }
    .event-card {
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      background: white;
    }
    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.15);
      cursor: pointer;
    }
    .btn-tiket {
      background: linear-gradient(90deg, #e91e63, #f06292);
      border: none;
      font-weight: 600;
      color: white;
    }
    .btn-tiket:hover {
      background: linear-gradient(90deg, #c2185b, #d81b60);
    }
    .badge-event {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #ff4081;
      color: white;
      padding: 0.4rem 0.6rem;
      font-size: 0.75rem;
      border-radius: 20px;
      z-index: 10;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">EventTix</a>
  </div>
</nav>

<!-- Carousel -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://via.placeholder.com/1600x500?text=Acara+Spesial" class="d-block w-100" alt="Acara Spesial">
      <div class="carousel-caption">
        <h5 class="fw-bold">Event Seru Menanti Kamu</h5>
        <p>Beli tiketmu sekarang dan jangan sampai ketinggalan!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://via.placeholder.com/1600x500?text=Promo+Tiket" class="d-block w-100" alt="Promo Tiket">
      <div class="carousel-caption">
        <h5 class="fw-bold">Dapatkan Promo Spesial</h5>
        <p>Diskon tiket hingga 50% untuk event tertentu</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Event List -->
<div class="container my-5">
  <h2 class="mb-4 fw-semibold text-center">Acara Tersedia</h2>
  <div class="row g-4">
    <?php
    $koneksi = mysqli_connect("localhost", "root", "", "tiketku");
    if ($koneksi) {
      $query = mysqli_query($koneksi, "SELECT * FROM acara ORDER BY id_acara DESC");
      while ($data = mysqli_fetch_array($query)) {
        ?>
        <div class="col-md-4">
          <div class="card event-card position-relative" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $data['id_acara']; ?>">
            <span class="badge-event">Terbaru</span>
            <img src="admin/gambar/<?php echo $data['foto']; ?>" class="card-img-top" alt="<?php echo $data['judul']; ?>">
            <div class="card-body">
              <h5 class="card-title fw-bold"><?php echo $data['judul']; ?></h5>
              <p class="card-text mb-2">
                <strong>Lokasi:</strong> <?php echo $data['lokasi']; ?><br>
                <strong>Tanggal:</strong> <?php echo $data['tanggal_acara']; ?><br>
                <strong>Harga:</strong> Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?>
              </p>
              <a href="beli.php?id=<?php echo $data['id_acara']; ?>" class="btn btn-tiket w-100">Beli Tiket</a>
            </div>
          </div>
        </div>

        <!-- Modal Detail Event -->
        <div class="modal fade" id="detailModal<?php echo $data['id_acara']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?php echo $data['id_acara']; ?>" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailModalLabel<?php echo $data['id_acara']; ?>"><?php echo $data['judul']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <img src="admin/gambar/<?php echo $data['foto']; ?>" class="img-fluid mb-3" alt="<?php echo $data['judul']; ?>">
                <p><strong>Deskripsi:</strong> <?php echo $data['deskripsi']; ?></p>
                <p><strong>Lokasi:</strong> <?php echo $data['lokasi']; ?></p>
                <p><strong>Tanggal:</strong> <?php echo $data['tanggal_acara']; ?></p>
                <p><strong>Harga Tiket:</strong> Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></p>
              </div>
              <div class="modal-footer">
                <a href="beli.php?id=<?php echo $data['id_acara']; ?>" class="btn btn-tiket">Beli Tiket Sekarang</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p class='text-center text-danger'>Gagal terhubung ke database.</p>";
    }
    ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
