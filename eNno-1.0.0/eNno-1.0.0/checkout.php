<?php
session_start();
// include 'index3.php'; // Navbar akan dipanggil di dalam tag <body>

if (!isset($_SESSION['id_member'])) {
  header("Location: login.php"); // Pastikan login.php adalah halaman login Anda
  exit;
}

include 'config.php';

$id_member = $_SESSION['id_member'];
// Ambil email dari database berdasarkan id_member untuk data yang lebih akurat
$stmt_member_email = $pdo->prepare("SELECT email, nama FROM member WHERE id_member = ?");
$stmt_member_email->execute([$id_member]);
$member_data = $stmt_member_email->fetch();

if (!$member_data) {
    // Handle jika data member tidak ditemukan (seharusnya tidak terjadi jika session valid)
    echo "Data member tidak ditemukan. Silakan login ulang.";
    // unset($_SESSION['id_member']); // Hapus session jika data tidak valid
    // header("Location: login.php");
    exit;
}

$email_user = $member_data['email'];
$nama_user = $member_data['nama'];


if (!isset($_GET['id'])) {
    echo "ID Acara tidak ditemukan.";
    // Mungkin redirect ke halaman acara atau halaman utama
    // header("Location: semua_acara.php");
    exit;
}
$id_acara = $_GET['id'];

$stmt_acara = $pdo->prepare("SELECT * FROM acara WHERE id_acara = ?");
$stmt_acara->execute([$id_acara]);
$acara = $stmt_acara->fetch();

if (!$acara) {
  echo "Acara tidak ditemukan.";
  // header("Location: semua_acara.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout Tiket - <?= htmlspecialchars($acara['judul']) ?> | TiketKu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicons (Sesuaikan dengan favicon TiketKu Anda) -->
  <link href="assets/img/favicon_tiketku.png" rel="icon">
  <link href="assets/img/apple-touch-icon_tiketku.png" rel="apple-touch-icon">

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
      background-color: var(--light-gray); /* Background lebih lembut */
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-color);
      line-height: 1.6;
    }

    .checkout-container {
      padding-top: 2rem;
      padding-bottom: 4rem;
    }

    .checkout-card {
      background-color: #fff;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 30px 40px; /* Padding lebih besar */
      margin: 30px auto;
      max-width: 700px; /* Sedikit lebih lebar untuk tampilan yang lebih baik */
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .checkout-header h2 {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 2rem;
    }
    .checkout-header p {
        color: #666;
        font-size: 0.95rem;
    }
    .checkout-header .event-title {
        font-size: 1.3rem;
        font-weight: 500;
        color: var(--text-color);
        margin-top: 5px;
    }


    .form-label {
      font-weight: 500;
      color: var(--text-color);
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    .form-control, .form-select {
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.25);
    }
    .form-control[readonly] {
        background-color: var(--light-gray);
        opacity: 0.8;
    }

    .input-group-text-custom {
        background-color: var(--light-gray);
        border-right: 0;
        border-radius: 0.5rem 0 0 0.5rem;
        padding: 0.75rem 1rem;
        color: var(--primary-color);
    }
    .form-control-icon {
        border-left: 0;
        padding-left: 0.5rem;
    }


    .btn-confirm-checkout {
      background-color: var(--primary-color);
      color: #fff;
      padding: 0.75rem 1.5rem;
      font-size: 1.1rem;
      border-radius: 50px;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-confirm-checkout:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(255, 77, 0, 0.3);
    }

    .upload-note {
      font-size: 0.8rem;
      color: #777;
      display: block;
      margin-top: 5px;
    }
    .upload-note strong {
        color: var(--primary-color);
    }

    .order-summary {
        background-color: var(--light-gray);
        padding: 20px;
        border-radius: var(--border-radius);
        margin-bottom: 25px;
    }
    .order-summary h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--text-color);
    }
    .order-summary .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }
    .order-summary .summary-item span:first-child {
        color: #555;
    }
    .order-summary .summary-item span:last-child {
        font-weight: 500;
    }
    .order-summary .total-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        border-top: 1px dashed #ccc;
        padding-top: 10px;
        margin-top: 10px;
    }

    /* Footer (sama seperti halaman lain) */
    .footer {
      background: #222; color: #ccc; padding: 60px 0 20px 0; font-size: 14px; margin-top: 3rem;
    }
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

<?php include_once 'index3.php'; // Memanggil navbar dari index3.php (pastikan index3.php menggunakan "TiketKu") ?>

<div class="container checkout-container">
  <div class="checkout-card" data-aos="fade-up">
    <div class="checkout-header">
      <h2><i class="bi bi-bag-check-fill me-2"></i>Checkout Pembelian</h2>
      <p>Selesaikan pembelian tiket Anda untuk acara:</p>
      <p class="event-title"><?= htmlspecialchars($acara['judul']) ?></p>
    </div>

    <div class="order-summary">
        <h5>Ringkasan Pesanan</h5>
        <div class="summary-item">
            <span>Harga per Tiket:</span>
            <span>Rp <?= number_format($acara['harga'], 0, ',', '.') ?></span>
        </div>
        <div class="summary-item">
            <span>Jumlah Tiket:</span>
            <span id="summary_jumlah_tiket">1</span>
        </div>
        <hr class="my-2">
        <div class="summary-item total-price">
            <span>Total Pembayaran:</span>
            <span id="summary_total_harga">Rp <?= number_format($acara['harga'], 0, ',', '.') ?></span>
        </div>
    </div>


    <form action="checkout_proses.php" method="POST" enctype="multipart/form-data" id="checkoutForm">
      <input type="hidden" name="id_acara" value="<?= $acara['id_acara'] ?>">
      <input type="hidden" name="id_member" value="<?= $id_member ?>">
      <input type="hidden" name="harga_satuan" id="harga_satuan" value="<?= $acara['harga'] ?>">
      <input type="hidden" name="total_harga_hidden" id="total_harga_hidden" value="<?= $acara['harga'] ?>">


      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap Pemesan</label>
        <div class="input-group">
            <span class="input-group-text-custom"><i class="bi bi-person-circle"></i></span>
            <input type="text" name="nama" id="nama" class="form-control form-control-icon" value="<?= htmlspecialchars($nama_user) ?>" readonly>
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Pemesan</label>
        <div class="input-group">
            <span class="input-group-text-custom"><i class="bi bi-envelope-at-fill"></i></span>
            <input type="email" name="email" id="email" class="form-control form-control-icon" value="<?= htmlspecialchars($email_user) ?>" readonly>
        </div>
      </div>

      <div class="mb-3">
        <label for="jumlah" class="form-label">Jumlah Tiket</label>
        <div class="input-group">
            <span class="input-group-text-custom"><i class="bi bi-ticket-detailed-fill"></i></span>
            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-icon" placeholder="Min. 1 tiket" value="1" min="1" max="10" required> <!-- Batas max bisa disesuaikan -->
        </div>
      </div>

      <!-- Total Harga tidak perlu input field yang bisa diubah user, cukup di summary -->
      <!-- <div class="mb-3">
        <label for="total_harga_display" class="form-label">Total Harga</label>
        <input type="text" id="total_harga_display" class="form-control" value="Rp <?= number_format($acara['harga'], 0, ',', '.') ?>" readonly>
      </div> -->

      <div class="mb-3">
        <label for="metode" class="form-label">Metode Pembayaran</label>
        <select name="metode" id="metode" class="form-select" required>
          <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
          <option value="transfer_bca">Transfer Bank BCA</option>
          <option value="transfer_mandiri">Transfer Bank Mandiri</option>
          <option value="qris">QRIS</option>
          <!-- Tambahkan opsi lain jika perlu -->
        </select>
      </div>

      <div class="mb-3" id="buktiTransferSection" style="display: none;"> <!-- Awalnya disembunyikan -->
        <label for="bukti_transfer" class="form-label">Upload Bukti Pembayaran</label>
        <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.pdf"> <!-- Tambah PDF jika diizinkan -->
        <small class="upload-note">Format file: <strong>JPG, JPEG, PNG, PDF</strong>. Ukuran maksimal: <strong>2MB</strong>.</small>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn-confirm-checkout">
            <i class="bi bi-shield-lock-fill me-2"></i>LANJUTKAN PEMBAYARAN
        </button>
      </div>
    </form>
  </div>
</div>

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
        </ul>
      </div>
      <div class="col-lg-4 col-md-12">
        <h4>Ikuti Kami</h4>
        <p>Dapatkan update terbaru melalui sosial media kami:</p>
        <div class="social-links d-flex mt-3">
          <a href="#" class="me-3"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
          <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="container text-center copyright-text">
    <p>© <?= date('Y') ?> <strong><span>TiketKu</span></strong>. All Rights Reserved.</p>
  </div>
</footer>


<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 600,
    once: true
  });

  const hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);
  const jumlahInput = document.getElementById('jumlah');
  const summaryJumlahTiket = document.getElementById('summary_jumlah_tiket');
  const summaryTotalHarga = document.getElementById('summary_total_harga');
  const totalHargaHiddenInput = document.getElementById('total_harga_hidden');
  const metodePembayaranSelect = document.getElementById('metode');
  const buktiTransferSection = document.getElementById('buktiTransferSection');
  const buktiTransferInput = document.getElementById('bukti_transfer');
  const checkoutForm = document.getElementById('checkoutForm');


  function updateTotal() {
    const jumlah = parseInt(jumlahInput.value) || 0;
    const total = jumlah * hargaSatuan;

    summaryJumlahTiket.textContent = jumlah;
    summaryTotalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
    totalHargaHiddenInput.value = total; // Simpan nilai numerik untuk dikirim ke server
  }

  function toggleBuktiTransfer() {
    const metode = metodePembayaranSelect.value;
    if (metode === 'transfer_bca' || metode === 'transfer_mandiri') {
      buktiTransferSection.style.display = 'block';
      buktiTransferInput.required = true;
    } else {
      buktiTransferSection.style.display = 'none';
      buktiTransferInput.required = false;
      buktiTransferInput.value = ''; // Kosongkan file jika metode diubah
    }
  }

  jumlahInput.addEventListener('input', updateTotal);
  metodePembayaranSelect.addEventListener('change', toggleBuktiTransfer);

  // Panggil fungsi saat halaman dimuat
  updateTotal();
  toggleBuktiTransfer();

  // Validasi sebelum submit (opsional, karena HTML5 `required` sudah ada)
  checkoutForm.addEventListener('submit', function(event) {
    if (buktiTransferSection.style.display === 'block' && !buktiTransferInput.files.length) {
      // Anda bisa menambahkan alert atau pesan error kustom di sini
      // alert('Mohon unggah bukti pembayaran Anda.');
      // event.preventDefault(); // Hentikan submit jika validasi gagal
    }
    // Tambahkan validasi lain jika perlu
  });

</script>

</body>
</html>