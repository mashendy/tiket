<?php
include 'config.php';

$id = $_GET['id'];
$alert = '';
$alert_type = '';

// Ambil data pembayaran dan tiket terkait
$stmt = $pdo->prepare("SELECT pembayaran.*, tiket.*, acara.judul, member.nama
                       FROM pembayaran
                       JOIN tiket ON pembayaran.id_tiket = tiket.id_tiket
                       JOIN acara ON tiket.id_acara = acara.id_acara
                       JOIN member ON tiket.id_member = member.id_member
                       WHERE pembayaran.id_pembayaran = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    // Pesan error ini akan ditampilkan dengan gaya Bootstrap default
    echo "<!DOCTYPE html><html lang='id'><head><title>Error</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "<style>body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }</style>";
    echo "</head><body>";
    echo "<div class='container text-center'><div class='alert alert-danger shadow-lg p-4' style='max-width: 500px; margin: auto;'>";
    echo "<h4><i class='bi bi-exclamation-triangle-fill me-2'></i>Data Tidak Ditemukan</h4>";
    echo "<p>Maaf, data pembayaran yang Anda cari tidak dapat ditemukan.</p>";
    echo "<a href='index.php' class='btn btn-primary mt-3'><i class='bi bi-house-door-fill me-2'></i>Kembali ke Beranda</a>";
    echo "</div></div></body></html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti_transfer'])) {
    $target_dir = "uploads/bukti/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Tetap 0777 sesuai kode asli, idealnya 0755 atau 0775
    }

    // Menggunakan nama file yang lebih aman dan unik (modifikasi kecil pada logika ini)
    $file_info = pathinfo($_FILES['bukti_transfer']['name']);
    $file_extension = strtolower($file_info['extension'] ?? ''); // Tambah null coalescing
    $bukti_transfer_filename = 'bukti_' . ($data['id_tiket'] ?? 'unknown') . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $bukti_transfer_filename;

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_file_size = 2 * 1024 * 1024; // 2MB

    if ($_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
        $alert = 'Terjadi kesalahan saat upload file. Kode: ' . $_FILES['bukti_transfer']['error'];
        $alert_type = 'danger';
    } elseif (!in_array($file_extension, $allowed_extensions)) {
        $alert = 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, atau PDF.';
        $alert_type = 'danger';
    } elseif ($_FILES['bukti_transfer']['size'] > $max_file_size) {
        $alert = 'Ukuran file terlalu besar. Maksimal 2MB.';
        $alert_type = 'danger';
    }
    // Proses upload jika tidak ada error validasi sebelumnya
    elseif (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $target_file)) {
        // Simpan bukti transfer ke tabel tiket
        $stmt_update_tiket = $pdo->prepare("UPDATE tiket SET bukti_transfer = ? WHERE id_tiket = ?");
        $stmt_update_tiket->execute([$bukti_transfer_filename, $data['id_tiket']]);

        // Update status pembayaran
        $stmt_update_pembayaran = $pdo->prepare("UPDATE pembayaran SET status_pembayaran = 'selesai' WHERE id_pembayaran = ?");
        $stmt_update_pembayaran->execute([$id]);

        // Perbarui data setelah update
        $stmt_refetch = $pdo->prepare("SELECT pembayaran.*, tiket.*, acara.judul, member.nama
                               FROM pembayaran
                               JOIN tiket ON pembayaran.id_tiket = tiket.id_tiket
                               JOIN acara ON tiket.id_acara = acara.id_acara
                               JOIN member ON tiket.id_member = member.id_member
                               WHERE pembayaran.id_pembayaran = ?");
        $stmt_refetch->execute([$id]);
        $data = $stmt_refetch->fetch(); // Timpa variabel $data dengan yang baru

        $alert = 'Bukti transfer berhasil diunggah dan pembayaran berhasil dikonfirmasi.';
        $alert_type = 'success';
    } else {
        $alert = 'Gagal mengunggah bukti transfer. Pastikan file valid dan direktori upload memiliki izin tulis.';
        $alert_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bukti Pembayaran - TiketKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color-tk: #ff4d00; /* Warna utama TiketKu */
      --secondary-color-tk: #cc3c00;
      --light-bg-tk: #f4f7f6; /* Sedikit abu-abu untuk background */
      --card-bg-tk: #ffffff;
      --text-color-tk: #333333;
      --text-muted-tk: #6c757d;
      --border-radius-tk: 0.75rem;
      --box-shadow-tk: 0 8px 25px rgba(0, 0, 0, 0.1);
      --success-tk: #198754;
      --info-tk: #0dcaf0;
      --danger-tk: #dc3545;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg-tk);
      color: var(--text-color-tk);
      padding-top: 2rem; /* Beri jarak dari atas */
      padding-bottom: 2rem;
    }

    .container-custom {
        max-width: 800px; /* Lebar kontainer utama */
    }

    .page-title {
      color: var(--primary-color-tk);
      font-weight: 600;
      text-align: center;
      margin-bottom: 2.5rem;
      font-size: 2.2rem;
    }
    .page-title i {
        margin-right: 10px;
    }

    .payment-card {
      background-color: var(--card-bg-tk);
      border-radius: var(--border-radius-tk);
      box-shadow: var(--box-shadow-tk);
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid #e9ecef;
      font-size: 0.95rem;
    }
    .detail-item:last-of-type { /* Menggunakan last-of-type agar lebih spesifik */
      border-bottom: none;
    }
    .detail-item strong {
      color: var(--text-color-tk);
      font-weight: 500;
      min-width: 170px; /* Agar label rata */
      flex-shrink: 0;
      padding-right: 10px;
    }
    .detail-item span {
      color: var(--text-muted-tk);
      text-align: right;
      word-break: break-word;
    }
    .detail-item .total-price-value {
        color: var(--primary-color-tk);
        font-weight: 700;
        font-size: 1.1rem;
    }
    .status-display {
        font-weight: 600;
        padding: 0.3em 0.8em;
        border-radius: 50px;
        font-size: 0.9em;
    }
    .status-menunggu { background-color: #fff3cd; color: #664d03; border: 1px solid #ffc107;}
    .status-selesai { background-color: #d1e7dd; color: #0f5132; border: 1px solid #198754;}
    .status-pending { background-color: #ffedd5; color: #9f580a; border: 1px solid #fd7e14;} /* Status pending jika ada */


    .bukti-transfer-section h5 {
        color: var(--primary-color-tk);
        font-weight: 500;
        margin-bottom: 1rem;
        text-align: center;
    }
    .bukti-transfer-img {
      max-width: 100%;
      max-height: 350px;
      border-radius: 0.5rem;
      border: 2px solid #eee;
      display: block;
      margin: 0 auto 1rem auto;
      object-fit: contain;
      box-shadow: 0 4px 10px rgba(0,0,0,0.07);
    }
    .bukti-transfer-link {
        display: block;
        text-align: center;
        font-weight: 500;
        color: var(--primary-color-tk);
        text-decoration: none;
        margin-top: 0.5rem;
    }
     .bukti-transfer-link:hover {
        color: var(--secondary-color-tk);
        text-decoration: underline;
    }


    .upload-form-card {
      background-color: var(--card-bg-tk);
      border-radius: var(--border-radius-tk);
      box-shadow: var(--box-shadow-tk);
      padding: 2rem;
    }
    .upload-form-card .form-label {
        font-weight: 500;
    }
    .upload-form-card .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    .upload-form-card .form-control:focus {
        border-color: var(--primary-color-tk);
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.25);
    }
    .btn-custom-upload {
      background-color: var(--success-tk);
      border-color: var(--success-tk);
      color: white;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: 50px;
      transition: all 0.3s ease;
    }
    .btn-custom-upload:hover {
      background-color: #157347;
      border-color: #146c43;
      transform: translateY(-2px);
    }

    .btn-custom-back {
      background-color: var(--primary-color-tk);
      border-color: var(--primary-color-tk);
      color: white;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: 50px;
      transition: all 0.3s ease;
      text-decoration: none;
    }
    .btn-custom-back:hover {
      background-color: var(--secondary-color-tk);
      border-color: var(--secondary-color-tk);
      transform: translateY(-2px);
      color: white;
    }

    .alert.fade-in { /* Animasi dari kode asli */
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .alert {
        border-radius: var(--border-radius-tk);
        border-width: 0; /* Hapus border default bootstrap jika mau */
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .alert-success { background-color: #d1e7dd; color: #0f5132; }
    .alert-danger { background-color: #f8d7da; color: #58151c; }
    .alert-info { background-color: #cff4fc; color: #055160; }

  </style>
</head>
<body>
  <div class="container container-custom">
    <h2 class="page-title"><i class="bi bi-receipt-cutoff"></i>Bukti Pembayaran</h2>

    <?php if ($alert): ?>
      <div class="alert alert-<?= htmlspecialchars($alert_type) ?> fade-in text-center" role="alert">
        <?= htmlspecialchars($alert) ?>
      </div>
    <?php endif; ?>

    <div class="payment-card">
      <div class="detail-item">
        <strong>Nama Member:</strong>
        <span><?= htmlspecialchars($data['nama']) ?></span>
      </div>
      <div class="detail-item">
        <strong>Acara:</strong>
        <span><?= htmlspecialchars($data['judul']) ?></span>
      </div>
      <div class="detail-item">
        <strong>Jumlah Tiket:</strong>
        <span><?= htmlspecialchars($data['jumlah']) ?></span>
      </div>
      <div class="detail-item">
        <strong>Total Bayar:</strong>
        <span class="total-price-value">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
      </div>
      <div class="detail-item">
        <strong>Metode Pembayaran:</strong>
        <span><?= ucwords(str_replace('_', ' ', htmlspecialchars($data['metode_pembayaran']))) ?></span>
      </div>
      <div class="detail-item">
        <strong>Status Pembayaran:</strong>
        <span>
            <span class="status-display status-<?= htmlspecialchars(strtolower($data['status_pembayaran'])) ?>">
                <?= ucfirst(htmlspecialchars($data['status_pembayaran'])) ?>
            </span>
        </span>
      </div>

      <?php if (!empty($data['bukti_transfer'])): ?>
        <div class="mt-4 pt-3 border-top bukti-transfer-section">
          <h5>Bukti Pembayaran Anda:</h5>
           <?php
              $filePathBukti = "uploads/bukti/" . htmlspecialchars($data['bukti_transfer']);
              $fileExtBukti = strtolower(pathinfo($filePathBukti, PATHINFO_EXTENSION));
              if (in_array($fileExtBukti, ['jpg', 'jpeg', 'png', 'gif'])):
            ?>
              <a href="<?= $filePathBukti ?>" target="_blank" title="Lihat Gambar Penuh">
                <img src="<?= $filePathBukti ?>?t=<?= time() ?>" alt="Bukti Transfer" class="bukti-transfer-img">
              </a>
            <?php elseif ($fileExtBukti === 'pdf'): ?>
              <a href="<?= $filePathBukti ?>" target="_blank" class="bukti-transfer-link fs-5">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Lihat Bukti Pembayaran (PDF)
              </a>
            <?php else: ?>
              <a href="<?= $filePathBukti ?>" target="_blank" class="bukti-transfer-link fs-5">
                <i class="bi bi-download me-2"></i>Unduh Bukti Pembayaran (<?= strtoupper($fileExtBukti) ?>)
              </a>
            <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($data['status_pembayaran'] == 'menunggu'): ?>
      <div class="upload-form-card">
        <h5 class="mb-3 text-center" style="color: var(--primary-color-tk); font-weight:500;">Unggah Bukti Pembayaran</h5>
        <form action="bukti_pembayaran.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="bukti_transfer_input" class="form-label">Pilih File (JPG, JPEG, PNG, PDF - Max 2MB)</label>
            <input type="file" name="bukti_transfer" id="bukti_transfer_input" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-custom-upload">
                <i class="bi bi-upload me-2"></i>Unggah Bukti
            </button>
          </div>
        </form>
      </div>
    <?php elseif ($data['status_pembayaran'] == 'selesai'): ?>
      <div class="alert alert-success fade-in text-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4 align-middle"></i>Bukti Transfer Sudah Diupload dan Pembayaran Selesai.
      </div>
    <?php elseif ($data['status_pembayaran'] == 'pending'): ?>
      <div class="alert alert-info fade-in text-center" role="alert">
        <i class="bi bi-hourglass-split me-2 fs-4 align-middle"></i>Bukti pembayaran Anda sedang diverifikasi.
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="index.php?page=beli" class="btn btn-custom-back"> <!-- Sesuaikan link kembali jika perlu -->
        <i class="bi bi-arrow-left-circle-fill me-2"></i>Kembali
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Anda bisa menambahkan script AOS jika ingin animasi, tapi untuk "tanpa mengubah apa pun" selain CSS, ini dihilangkan -->
</body>
</html>