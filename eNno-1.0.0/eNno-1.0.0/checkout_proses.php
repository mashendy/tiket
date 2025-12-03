<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi login
    if (!isset($_SESSION['id_member'])) {
        echo "<script>alert('Anda harus login terlebih dahulu.'); window.location.href='login.php';</script>";
        exit;
    }

    $id_member = $_SESSION['id_member'];
    $id_acara = $_POST['id_acara'];
    $jumlah = (int)$_POST['jumlah'];
    $metode = $_POST['metode']; // Sesuai dengan name="metode" dari form

    // Validasi jumlah tiket minimal 1
    if ($jumlah < 1) {
        echo "<script>alert('Jumlah tiket minimal 1.'); window.history.back();</script>";
        exit;
    }

    // Ambil data acara
    $stmt = $pdo->prepare("SELECT * FROM acara WHERE id_acara = ?");
    $stmt->execute([$id_acara]);
    $acara = $stmt->fetch();

    if (!$acara) {
        echo "<script>alert('Acara tidak ditemukan.'); window.location.href='index.php';</script>";
        exit;
    }

    // Validasi dan upload bukti transfer
    if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== 0) {
        echo "<script>alert('Gagal mengunggah bukti transfer.'); window.history.back();</script>";
        exit;
    }

    $target_dir = "uploads/bukti_transfer/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $original_name = basename($_FILES["bukti_transfer"]["name"]);
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (!in_array($extension, $allowed_ext)) {
        echo "<script>alert('Format file tidak didukung. Gunakan jpg, jpeg, atau png.'); window.history.back();</script>";
        exit;
    }

    if ($_FILES["bukti_transfer"]["size"] > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran file terlalu besar. Maksimal 2MB.'); window.history.back();</script>";
        exit;
    }

    $file_name = time() . "_" . uniqid() . "." . $extension;
    $target_file = $target_dir . $file_name;

    if (!move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
        echo "<script>alert('Upload bukti transfer gagal.'); window.history.back();</script>";
        exit;
    }

    // Hitung total harga
    $total_harga = $acara['harga'] * $jumlah;
    $tanggal_pembelian = date("Y-m-d H:i:s");

    // Simpan ke tabel tiket
    $stmt = $pdo->prepare("INSERT INTO tiket (id_member, id_acara, jumlah, metode, total_harga, bukti_transfer, tanggal_pembelian) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_member, $id_acara, $jumlah, $metode, $total_harga, $file_name, $tanggal_pembelian]);
    $id_tiket = $pdo->lastInsertId();

    // Simpan ke tabel pembayaran
    $stmt = $pdo->prepare("INSERT INTO pembayaran (id_tiket, metode_pembayaran, status_pembayaran) VALUES (?, ?, 'menunggu')");
    $stmt->execute([$id_tiket, $metode]);
    $id_pembayaran = $pdo->lastInsertId();

    // Tampilkan pesan sukses
    echo "
    <html>
    <head>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
      <meta http-equiv='refresh' content='3;url=bukti_pembayaran.php?id=$id_pembayaran'>
      <style>
        .alert {
          margin-top: 100px;
          animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(-20px); }
          to { opacity: 1; transform: translateY(0); }
        }
      </style>
    </head>
    <body>
      <div class='container text-center'>
        <div class='alert alert-success shadow p-4'>
          <h4 class='alert-heading'>Pembayaran Diproses!</h4>
          <p>Data tiket dan pembayaran berhasil disimpan. Anda akan dialihkan ke halaman bukti pembayaran...</p>
          <hr>
          <p class='mb-0'>Jika tidak dialihkan, klik <a href='bukti_pembayaran.php?id=$id_pembayaran'>di sini</a>.</p>
        </div>
      </div>
    </body>
    </html>
    ";
    exit;

} else {
    echo "<script>alert('Metode tidak diizinkan.'); window.location.href='index.php';</script>";
}
?>
