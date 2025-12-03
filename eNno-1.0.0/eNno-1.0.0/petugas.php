<?php
// Aktifkan error reporting untuk debugging (hapus atau komentari di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
// Jika index4.html adalah template penuh, include di tempat yang tepat.
// Jika hanya header/sidebar, ini oke.
if (file_exists('index4.html')) {
    include 'index4.html';
}

$cari = $_GET['cari'] ?? '';

// 1. PERBAIKI QUERY SQL: Aktifkan pengambilan tiket.status_tiket
$sql = "
    SELECT pembayaran.id_pembayaran, tiket.id_tiket, tiket.jumlah, tiket.total_harga, tiket.metode,
           tiket.bukti_transfer, pembayaran.metode_pembayaran, pembayaran.status_pembayaran,
           pembayaran.dibayar_pada, acara.judul, member.nama AS nama_member,
           tiket.status_tiket -- HAPUS KOMENTAR DI SINI
    FROM pembayaran
    JOIN tiket ON pembayaran.id_tiket = tiket.id_tiket
    JOIN acara ON tiket.id_acara = acara.id_acara
    JOIN member ON tiket.id_member = member.id_member
";

$params = [];
if (!empty($cari)) {
    // Pertimbangkan untuk mencari juga berdasarkan status pembayaran atau status tiket jika relevan
    $sql .= " WHERE (acara.judul LIKE :cari OR member.nama LIKE :cari OR pembayaran.status_pembayaran LIKE :cari)";
    $params[':cari'] = '%' . $cari . '%';
}

$sql .= " ORDER BY pembayaran.dibayar_pada DESC";

try {
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        echo "<div class='container mt-3'><div class='alert alert-danger'>Error preparing statement: "; print_r($pdo->errorInfo()); echo "</div></div>";
        // Mungkin perlu exit atau penutup HTML jika index4.html tidak lengkap
        exit;
    }
    $executeResult = $stmt->execute($params);
    if (!$executeResult) {
        echo "<div class='container mt-3'><div class='alert alert-danger'>Error executing statement: "; print_r($stmt->errorInfo()); echo "</div></div>";
        exit;
    }
    // Gunakan FETCH_ASSOC untuk array yang lebih bersih
    $dataPembayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div class='container mt-3'><div class='alert alert-danger'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pembayaran Petugas - TiketKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .btn-custom-search {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .btn-custom-search:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        .btn-custom-reset {
             background-color: #6c757d;
             color: white;
        }
        .btn-custom-reset:hover {
            background-color: #5c636a;
        }
        .container-custom {
            max-width: 1300px; /* Disesuaikan */
            margin-top: 1rem; /* Disesuaikan jika index4.html punya padding */
            margin-bottom: 2rem;
        }
        .card-header-custom {
            background-color: #0d6efd;
            color: white;
            border-bottom: none;
        }
        .action-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.4em 0.7em;
        }
        .btn-send-ticket {
            background-color: #198754;
            color: white;
        }
        .btn-send-ticket:hover {
            background-color: #157347;
        }
        .btn-ticket-processed {
            background-color: #6c757d;
            color: white;
        }
         .main-content-area { /* Wrapper jika index4.html adalah template penuh */
            padding: 15px;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    // Jika index4.html belum di-include dan merupakan template dasar,
    // pastikan konten di bawah masuk ke area konten yang benar.
    ?>
    <div class="main-content-area"> <!-- Sesuaikan dengan struktur index4.html -->
        <div class="container-fluid container-custom mt-4">

            <?php if (isset($_GET['status_update']) && $_GET['status_update'] === 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Status pembayaran berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($_GET['status_update']) && $_GET['status_update'] === 'error'): ?>
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal memperbarui status pembayaran. <?= htmlspecialchars($_GET['message'] ?? '') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['kirim_tiket_status']) && $_GET['kirim_tiket_status'] === 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Tiket berhasil diproses/dikirim untuk ID Tiket: <strong><?= htmlspecialchars($_GET['id_tiket'] ?? '') ?></strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($_GET['kirim_tiket_status']) && $_GET['kirim_tiket_status'] === 'error'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal memproses/mengirim tiket (ID: <?= htmlspecialchars($_GET['id_tiket'] ?? 'Tidak diketahui') ?>).
                    Pesan: <?= htmlspecialchars($_GET['message'] ?? 'Terjadi kesalahan.') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-header card-header-custom d-flex flex-wrap justify-content-between align-items-center p-3">
                    <h4 class="mb-2 mb-md-0"><i class="bi bi-cash-stack me-2"></i>Data Pembayaran TiketKu</h4>
                    <form method="GET" action="petugas.php" class="d-flex ms-md-auto" style="max-width: 400px;">
                        <input type="text" name="cari" class="form-control form-control-sm me-2" placeholder="Cari judul, nama, status..." value="<?= htmlspecialchars($cari) ?>">
                        <button class="btn btn-sm btn-custom-search" type="submit"><i class="bi bi-search"></i></button>
                        <?php if (!empty($cari)): ?>
                        <a href="petugas.php" class="btn btn-sm btn-custom-reset ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Member</th>
                                    <th>Acara</th>
                                    <th>Jml</th>
                                    <th>Total Bayar</th>
                                    <th>Metode Bayar</th>
                                    <th>Status Bayar</th>
                                    <th>Tgl. Bayar</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($dataPembayaran) > 0) {
                                    $no = 1;
                                    foreach ($dataPembayaran as $data) {
                                        // 2. PERBAIKI LOGIKA TOMBOL: Gunakan $data['status_tiket']
                                        // Ambil status tiket dari data, beri default 'belum_diproses' jika NULL atau tidak ada
                                        $status_tiket_db = $data['status_tiket'] ?? 'belum_diproses';
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($data['nama_member']) ?></td>
                                            <td><?= htmlspecialchars($data['judul']) ?></td>
                                            <td><?= htmlspecialchars($data['jumlah']) ?></td>
                                            <td>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td>
                                            <td><?= htmlspecialchars($data['metode_pembayaran']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $data['status_pembayaran'] == 'selesai' ? 'success' : ($data['status_pembayaran'] == 'menunggu' ? 'warning text-dark' : ($data['status_pembayaran'] == 'pending' ? 'info text-dark' : 'danger')) ?>">
                                                    <?= ucfirst(htmlspecialchars($data['status_pembayaran'])) ?>
                                                </span>
                                            </td>
                                            <td><?= $data['dibayar_pada'] ? htmlspecialchars(date('d M Y, H:i', strtotime($data['dibayar_pada']))) : '-' ?></td>
                                            <td>
                                                <?php if (!empty($data['bukti_transfer'])): ?>
                                                    <a href="uploads/bukti/<?= htmlspecialchars($data['bukti_transfer']) ?>" target="_blank" class="btn btn-outline-info btn-sm" title="Lihat Bukti">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="action-buttons">
                                                <?php if (in_array($data['status_pembayaran'], ['menunggu', 'pending'])): ?>
                                                    <a href="update_status.php?id_pembayaran=<?= $data['id_pembayaran'] ?>&status=selesai" class="btn btn-success btn-sm" title="Setujui Pembayaran" onclick="return confirm('Anda yakin ingin menyetujui pembayaran ini?')">
                                                        <i class="bi bi-check-circle"></i> Setujui
                                                    </a>
                                                    <a href="update_status.php?id_pembayaran=<?= $data['id_pembayaran'] ?>&status=gagal" class="btn btn-danger btn-sm" title="Tolak Pembayaran" onclick="return confirm('Anda yakin ingin menolak pembayaran ini?')">
                                                        <i class="bi bi-x-circle"></i> Tolak
                                                    </a>
                                                <?php endif; ?>

                                                <?php
                                                // Logika untuk tombol Kirim Tiket atau status Tiket Terkirim
                                                if ($data['status_pembayaran'] === 'selesai') {
                                                    // Periksa status tiket dari database ($status_tiket_db)
                                                    if ($status_tiket_db !== 'terkirim') { // Ganti 'terkirim' jika Anda menggunakan status lain di kirim_tiket_proses.php
                                                ?>
                                                        <a href="kirim_tiket_proses.php?id_tiket=<?= $data['id_tiket'] ?>" class="btn btn-send-ticket btn-sm" title="Proses & Kirim Tiket ke Pengguna" onclick="return confirm('Anda yakin ingin memproses dan mengirim tiket ini (ID: <?= $data['id_tiket'] ?>) ke pengguna?')">
                                                            <i class="bi bi-send-check-fill"></i> Kirim Tiket
                                                        </a>
                                                <?php
                                                    } else { // Jika status_tiket adalah 'terkirim'
                                                ?>
                                                        <button class="btn btn-ticket-processed btn-sm" disabled title="Tiket sudah diproses/dikirim">
                                                            <i class="bi bi-check2-all"></i> Tiket Terkirim
                                                        </button>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <a href="bukti_pembayaran.php?id=<?= $data['id_pembayaran'] ?>" class="btn btn-outline-primary btn-sm" title="Detail Pembayaran">
                                                    <i class="bi bi-receipt"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    $colspan = 10;
                                    echo "<tr><td colspan='{$colspan}' class='text-center p-4'>Tidak ada data pembayaran ditemukan".(!empty($cari) ? " untuk pencarian '".htmlspecialchars($cari)."'." : ".")."</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Penutup .main-content-area -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Otomatis tutup alert setelah beberapa detik
        window.setTimeout(function() {
            var alerts = document.querySelectorAll(".alert-dismissible.fade.show");
            alerts.forEach(function(alert) {
                var bsAlert = bootstrap.Alert.getInstance(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            });
        }, 7000); // 7 detik
    </script>
</body>
</html>