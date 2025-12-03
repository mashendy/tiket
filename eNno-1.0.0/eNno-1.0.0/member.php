<?php
require 'config.php';

$pesan = '';
function tampilPesan($tipe, $pesan) {
    return "<div id='alertPesan' class='alert alert-$tipe alert-dismissible fade show text-center pesan-anim' role='alert'>
                $pesan
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Tambah Member
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT INTO member (nama, email, password, no_hp, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $password, $no_hp, $alamat]);
        $pesan = tampilPesan('success', 'Data member berhasil ditambahkan.');
    } else {
        $pesan = tampilPesan('danger', 'Email tidak valid.');
    }
}

// Edit Member
if (isset($_POST['edit'])) {
    $id = $_POST['id_member'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("UPDATE member SET nama=?, email=?, no_hp=?, alamat=? WHERE id_member=?");
        $stmt->execute([$nama, $email, $no_hp, $alamat, $id]);
        $pesan = tampilPesan('success', 'Data member berhasil diperbarui.');
    } else {
        $pesan = tampilPesan('danger', 'Data tidak valid.');
    }
}

// Hapus Member
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Pastikan ID ada dan valid
    if (is_numeric($id)) {
        $stmt = $pdo->prepare("DELETE FROM member WHERE id_member = ?");
        $stmt->execute([$id]);
        $pesan = tampilPesan('success', 'Data member berhasil dihapus.');
    } else {
        $pesan = tampilPesan('danger', 'ID member tidak valid.');
    }
}

// Cari
$cari = $_GET['cari'] ?? '';
if ($cari != '') {
    $stmt = $pdo->prepare("SELECT * FROM member WHERE nama LIKE ? OR email LIKE ? ORDER BY dibuat_pada DESC");
    $stmt->execute(["%$cari%", "%$cari%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM member ORDER BY dibuat_pada DESC");
}
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Member</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Animasi fade-in untuk alert */
        .pesan-anim {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
        /* Animasi fade-out */
        .fade-out {
            animation: fadeOut 0.5s ease forwards;
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                height: 0;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
        }
    </style>
</head>
<body class="p-4">
    <?php include 'index2.html'; ?>
    <div class="container">
        <h2>Data Member</h2>

        <!-- Tampilkan alert jika ada pesan -->
        <?= $pesan ?>

        <form method="GET" action="index.php">
    <input type="hidden" name="page" value="member">
    <div class="input-group">
        <input type="text" name="cari" class="form-control" placeholder="Cari nama/email..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-primary">Cari</button>
        <a href="index.php?page=member" class="btn btn-secondary">Reset</a>
    </div>
</form>


        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Member</button>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; foreach ($members as $m): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($m['nama']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td><?= htmlspecialchars($m['no_hp']) ?></td>
                    <td><?= htmlspecialchars($m['alamat']) ?></td>
                    <td><?= $m['dibuat_pada'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $m['id_member'] ?>">Edit</button>
                        <a href="member_delete.php?id=<?= $m['id_member'] ?>" onclick="return confirm('Yakin hapus member <?= htmlspecialchars($m['nama']) ?>?')" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Member -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Member Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit (ditempatkan di luar tabel) -->
    <?php foreach ($members as $m): ?>
    <div class="modal fade" id="editModal<?= $m['id_member'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header"><h5>Edit Member</h5></div>
                <div class="modal-body">
                    <input type="hidden" name="id_member" value="<?= $m['id_member'] ?>">
                    <input name="nama" class="form-control mb-2" value="<?= htmlspecialchars($m['nama']) ?>" required>
                    <input name="email" type="email" class="form-control mb-2" value="<?= htmlspecialchars($m['email']) ?>" required>
                    <input name="no_hp" class="form-control mb-2" value="<?= htmlspecialchars($m['no_hp']) ?>" required>
                    <textarea name="alamat" class="form-control" required><?= htmlspecialchars($m['alamat']) ?></textarea>
                </div>
                <div class="modal-footer">
                    <button name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto close alert setelah 5 detik dengan animasi fade-out
        window.addEventListener('DOMContentLoaded', () => {
            const alertPesan = document.getElementById('alertPesan');
            if(alertPesan){
                setTimeout(() => {
                    alertPesan.classList.add('fade-out');
                    // Setelah animasi selesai, remove elemen alert
                    alertPesan.addEventListener('animationend', () => {
                        alertPesan.remove();
                    });
                }, 5000);
            }
        });
    </script>
</body>
</html>
