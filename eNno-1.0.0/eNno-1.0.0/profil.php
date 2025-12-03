<?php
session_start();
require_once 'config.php';

// ... (include navbar jika ada atau placeholder) ...

if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['id_member'];

$stmt = $pdo->prepare("SELECT * FROM member WHERE id_member = :member_id");
$stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    die("Data member tidak ditemukan.");
}

$errors = [];
$success = "";

// Proses update data profil (nama & email)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profil'])) {
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        // Anda mungkin juga ingin menambahkan no_hp dan alamat jika ada di form
        // $no_hp = trim($_POST['no_hp']);
        // $alamat = trim($_POST['alamat']);


        if (empty($nama)) $errors[] = 'Nama lengkap tidak boleh kosong.';
        if (empty($email)) {
            $errors[] = 'Email tidak boleh kosong.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }
        // Validasi untuk no_hp dan alamat jika ditambahkan

        if (empty($errors) && $email !== $member['email']) {
            $stmtCheckEmail = $pdo->prepare("SELECT id_member FROM member WHERE email = :email AND id_member != :member_id");
            $stmtCheckEmail->bindParam(':email', $email);
            $stmtCheckEmail->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmtCheckEmail->execute();
            if ($stmtCheckEmail->fetch()) {
                $errors[] = 'Email sudah terdaftar untuk akun lain.';
            }
        }

        if (empty($errors)) {
            // Tambahkan no_hp dan alamat ke query jika ada di form
            $stmtUpdate = $pdo->prepare("UPDATE member SET nama = :nama, email = :email /*, no_hp = :no_hp, alamat = :alamat */ WHERE id_member = :member_id");
            $stmtUpdate->bindParam(':nama', $nama);
            $stmtUpdate->bindParam(':email', $email);
            // $stmtUpdate->bindParam(':no_hp', $no_hp);
            // $stmtUpdate->bindParam(':alamat', $alamat);
            $stmtUpdate->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            if ($stmtUpdate->execute()) {
                $stmt->execute(); 
                $member = $stmt->fetch(PDO::FETCH_ASSOC);
                $success = "Profil berhasil diperbarui!";
            } else {
                $errors[] = "Gagal memperbarui profil. Silakan coba lagi.";
            }
        }
    }
    // Proses upload foto (sama seperti sebelumnya)
    elseif (isset($_POST['upload_foto']) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // ... (logika upload foto tetap sama) ...
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0775, true)) {
                $errors[] = "Gagal membuat direktori upload.";
            }
        }

        if (empty($errors)) {
            $fotoName = basename($_FILES['foto']['name']);
            $fileExtension = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
            $uniqueFotoName = 'profile_' . $member_id . '_' . time() . '.' . $fileExtension;
            $fotoPath = $uploadDir . $uniqueFotoName;

            $allowedTypes = ['jpeg', 'jpg', 'png', 'gif'];
            $fileMimeType = mime_content_type($_FILES['foto']['tmp_name']);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; 

            if (!in_array($fileExtension, $allowedTypes) || !in_array($fileMimeType, $allowedMimeTypes)) {
                $errors[] = "Format foto hanya boleh JPG, JPEG, PNG, atau GIF.";
            } elseif ($_FILES['foto']['size'] > $maxFileSize) {
                $errors[] = "Ukuran foto maksimal 2MB.";
            } else {
                if (!empty($member['foto']) && $member['foto'] !== 'default-avatar.png' && file_exists($uploadDir . $member['foto'])) {
                    unlink($uploadDir . $member['foto']);
                }

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath)) {
                    $stmtFoto = $pdo->prepare("UPDATE member SET foto = :foto WHERE id_member = :member_id");
                    $stmtFoto->bindParam(':foto', $uniqueFotoName);
                    $stmtFoto->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                    if ($stmtFoto->execute()) {
                        $stmt->execute(); 
                        $member = $stmt->fetch(PDO::FETCH_ASSOC);
                        $success = "Foto profil berhasil diperbarui!";
                    } else {
                        $errors[] = "Gagal menyimpan path foto ke database.";
                        if (file_exists($fotoPath)) unlink($fotoPath); 
                    }
                } else {
                    $errors[] = "Gagal mengunggah foto. Periksa izin folder 'uploads'.";
                }
            }
        }
    }
}

$fotoProfilUrl = 'assets/img/default-avatar.png'; 
if (!empty($member['foto']) && file_exists('uploads/' . $member['foto'])) {
    $fotoProfilUrl = 'uploads/' . htmlspecialchars($member['foto']);
}

// Menggunakan kolom 'dibuat_pada'
$tanggalDaftarFormatted = "Data tidak tersedia"; 
if (isset($member['dibuat_pada']) && !empty($member['dibuat_pada'])) {
    try {
        $tanggalObj = new DateTime($member['dibuat_pada']);
        $tanggalDaftarFormatted = $tanggalObj->format('d F Y'); 
    } catch (Exception $e) {
        $tanggalDaftarFormatted = htmlspecialchars($member['dibuat_pada']) . " (format tidak dikenal)";
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya - <?= htmlspecialchars($member['nama'] ?? 'Member') ?> | EventTix</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ... (CSS styles tetap sama seperti sebelumnya) ... */
    :root {
      --primary-color: #ff4d00;
      --secondary-color: #cc3c00;
      --text-color: #333;
      --light-gray: #f8f9fa;
      --border-radius: 0.75rem;
      --box-shadow: 0 6px 12px rgba(0,0,0,0.08);
      --box-shadow-hover: 0 8px 16px rgba(0,0,0,0.12);
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
    .navbar-custom .btn-login, .navbar-custom .btn-profile {
        background-color: var(--primary-color);
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }
    .navbar-custom .btn-login:hover, .navbar-custom .btn-profile:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .profile-page-container {
        padding-top: 2rem;
        padding-bottom: 4rem;
    }

    .profile-header-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 40px 20px 80px 20px;
        text-align: center;
        border-radius: var(--border-radius);
        margin-bottom: -60px;
        position: relative;
        box-shadow: var(--box-shadow);
    }
    .profile-header-card h2 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .profile-header-card p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .profile-content-card {
      background-color: #fff;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 20px;
      margin-top: 0;
      position: relative;
      z-index: 2;
    }

    .avatar-section {
        text-align: center;
        margin-top: -75px;
        margin-bottom: 20px;
        position: relative;
        z-index: 3;
    }
    .avatar-img-wrapper {
        position: relative;
        display: inline-block;
    }
    .avatar-img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid white;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      background-color: var(--light-gray);
    }
    .btn-upload-avatar-label {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background-color: var(--primary-color);
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-upload-avatar-label:hover {
        background-color: var(--secondary-color);
        transform: scale(1.1);
    }
    #foto_profil_input {
        display: none;
    }
    .form-upload-avatar {
        display: inline-block;
        margin-top: 10px;
    }
    .btn-submit-avatar {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        font-weight: 500;
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-size: 0.9rem;
    }
    .btn-submit-avatar:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .profile-form .form-label {
        font-weight: 500;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
    }
    .profile-form .form-control {
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .profile-form .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.25);
    }
    .profile-form .form-control:disabled {
        background-color: var(--light-gray);
        opacity: 0.7;
    }
    .profile-form .input-group-text {
        background-color: var(--light-gray);
        border-right: 0;
        border-radius: 0.5rem 0 0 0.5rem;
    }
    .profile-form .form-control-icon {
        border-left: 0;
        padding-left: 0.5rem;
    }
    .profile-form textarea.form-control {
        min-height: 100px; /* Atur tinggi default untuk textarea */
    }

    .btn-custom-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
      font-weight: 600;
      border-radius: 50px;
      padding: 0.6rem 1.5rem;
      transition: all 0.3s ease;
    }
    .btn-custom-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(255, 77, 0, 0.3);
    }
    .btn-custom-outline {
        color: var(--primary-color);
        border-color: var(--primary-color);
        font-weight: 500;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        transition: all 0.3s ease;
    }
    .btn-custom-outline:hover {
        background-color: var(--primary-color);
        color: white;
    }
    .btn-custom-danger-outline {
        color: #dc3545;
        border-color: #dc3545;
        font-weight: 500;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        transition: all 0.3s ease;
    }
    .btn-custom-danger-outline:hover {
        background-color: #dc3545;
        color: white;
    }

    .alert-fixed {
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
    .footer h4 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #fff;
      font-size: 1.1rem;
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
  </style>
</head>
<body>

<?php include_once 'index3.php'; // Memanggil navbar dari index3.php ?>

<div class="container profile-page-container">
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger alert-dismissible fade show alert-fixed" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show alert-fixed" role="alert">
        <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="profile-header-card" data-aos="fade-down">
        <h2>Pengaturan Akun</h2>
        <p>Kelola informasi profil dan preferensi akun Anda di sini.</p>
    </div>

    <div class="profile-content-card" data-aos="fade-up" data-aos-delay="100">
        <div class="avatar-section">
            <form method="POST" enctype="multipart/form-data" class="form-upload-avatar">
                <div class="avatar-img-wrapper">
                    <img src="<?= $fotoProfilUrl ?>?t=<?= time() ?>" alt="Foto Profil" class="avatar-img" id="avatarPreview">
                    <label for="foto_profil_input" class="btn-upload-avatar-label" title="Ubah Foto Profil">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <input type="file" name="foto" id="foto_profil_input" accept="image/jpeg, image/png, image/gif" onchange="previewFoto(this);">
                </div>
                <button type="submit" name="upload_foto" class="btn btn-sm btn-submit-avatar mt-2" id="btnSubmitAvatar" style="display: none;">
                    <i class="bi bi-upload"></i> Simpan Foto
                </button>
            </form>
        </div>

        <h5 class="mb-4 text-center" style="font-weight: 600;"><?= htmlspecialchars($member['nama'] ?? 'Nama Member') ?></h5>

        <form method="POST" class="profile-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control form-control-icon" id="nama" name="nama" value="<?= htmlspecialchars($member['nama'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control form-control-icon" id="email" name="email" value="<?= htmlspecialchars($member['email'] ?? '') ?>" required>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" class="form-control form-control-icon" id="no_hp" name="no_hp" value="<?= htmlspecialchars($member['no_hp'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_daftar" class="form-label">Tanggal Terdaftar</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-check-fill"></i></span>
                        <input type="text" class="form-control form-control-icon" id="tanggal_daftar" value="<?= $tanggalDaftarFormatted ?>" disabled>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($member['alamat'] ?? '') ?></textarea>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="update_profil" class="btn btn-custom-primary">
                    <i class="bi bi-save-fill me-2"></i>Simpan Perubahan Profil
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
            <a href="ubah_password.php" class="btn btn-custom-outline w-100 w-md-auto">
                <i class="bi bi-key-fill me-2"></i>Ubah Password
            </a>
            <a href="riwayat_tiket.php" class="btn btn-custom-outline w-100 w-md-auto">
                <i class="bi bi-receipt-cutoff me-2"></i>Riwayat Tiket
            </a>
            <a href="logout.php" class="btn btn-custom-danger-outline w-100 w-md-auto">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="row footer-top" data-aos="fade-up">
      <div class="col-lg-4 col-md-6 mb-4">
        <h4>EventTix</h4>
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
        <h4>Akun Saya</h4>
        <ul>
          <li><a href="profil.php"><i class="bi bi-chevron-right"></i> Profil</a></li>
          <li><a href="riwayat_transaksi.php"><i class="bi bi-chevron-right"></i> Tiket Saya</a></li>
          <li><a href="login.php"><i class="bi bi-chevron-right"></i> Login</a></li>
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
    <p>© <?= date('Y') ?> <strong><span>EventTix</span></strong>. All Rights Reserved.</p>
    <p>Inspired by <a href="https://bootstrapmade.com/">BootstrapMade</a></p>
  </div>
</footer>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true,
    offset: 50
  });

  function previewFoto(input) {
    const preview = document.getElementById('avatarPreview');
    const btnSubmitAvatar = document.getElementById('btnSubmitAvatar');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
      btnSubmitAvatar.style.display = 'inline-block';
    } else {
        btnSubmitAvatar.style.display = 'none';
    }
  }

  window.setTimeout(function() {
    let alerts = document.querySelectorAll('.alert-fixed');
    alerts.forEach(function(alert) {
        if (bootstrap.Alert.getInstance(alert)) {
            bootstrap.Alert.getInstance(alert).close();
        }
    });
  }, 5000);
</script>
</body>
</html>