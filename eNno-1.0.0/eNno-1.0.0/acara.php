
        <?php
include 'config.php';
include 'index2.html';
$query = "SELECT * FROM acara ORDER BY id_acara DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
// Cari
$cari = $_GET['cari'] ?? '';
if ($cari != '') {
    $stmt = $pdo->prepare("SELECT * FROM acara WHERE judul LIKE ? OR lokasi LIKE ? ORDER BY dibuat_pada DESC");
    $stmt->execute(["%$cari%", "%$cari%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM acara ORDER BY dibuat_pada DESC");
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Acara</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2>Data Acara</h2>
  <form method="GET" action="index.php">
    <input type="hidden" name="page" value="acara">
    <div class="input-group">
        <input type="text" name="cari" class="form-control" placeholder="Cari judul acara..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-primary">Cari</button>
        <a href="index.php?page=acara" class="btn btn-secondary">Reset</a>
    </div>
</form>
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAcara">Tambah Acara</button>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Lokasi</th>
        <th>Tanggal Acara</th>
        <th>Harga</th>
        <th>Foto</th>
        <th>Dibuat Pada</th>
        <th>Dibuat Oleh</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$no}</td>";
        echo "<td>{$row['judul']}</td>";
        echo "<td>{$row['lokasi']}</td>";
        echo "<td>{$row['tanggal_acara']}</td>";
        echo "<td>Rp " . number_format($row['harga'], 2, ',', '.') . "</td>";
        echo "<td><img src='uploads/{$row['foto']}' width='100'></td>";
        echo "<td>{$row['dibuat_pada']}</td>";
        echo "<td>{$row['dibuat_oleh']}</td>";
        echo "<td>
                <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#modalEditAcara'
                  onclick=\"isiFormEdit(
                    '{$row['id_acara']}',
                    '{$row['judul']}',
                    '{$row['lokasi']}',
                    '{$row['tanggal_acara']}',
                    '{$row['harga']}'
                  )\">Edit</button>
                <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modalHapusAcara'
                  onclick=\"setFormHapus('{$row['id_acara']}')\">Hapus</button>
              </td>";
        echo "</tr>";
        $no++;
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Modal Tambah Acara -->
<div class="modal fade" id="modalTambahAcara" tabindex="-1">
  <div class="modal-dialog">
    <form action="acara_simpan.php" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Acara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul Acara</label>
            <input type="text" class="form-control" name="judul" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" class="form-control" name="lokasi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Acara</label>
            <input type="date" class="form-control" name="tanggal_acara" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Acara -->
<div class="modal fade" id="modalEditAcara" tabindex="-1">
  <div class="modal-dialog">
    <form action="acara_edit.php" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Acara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_acara" id="edit-id">
          <div class="mb-3">
            <label class="form-label">Judul Acara</label>
            <input type="text" class="form-control" name="judul" id="edit-judul" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" class="form-control" name="lokasi" id="edit-lokasi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Acara</label>
            <input type="date" class="form-control" name="tanggal_acara" id="edit-tanggal" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="edit-harga" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Foto (Kosongkan jika tidak diubah)</label>
            <input type="file" class="form-control" name="foto" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapusAcara" tabindex="-1">
  <div class="modal-dialog">
    <form action="acara_hapus.php" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_acara" id="hapus-id">
          <p>Apakah Anda yakin ingin menghapus acara ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function isiFormEdit(id, judul, lokasi, tanggal, harga) {
  document.getElementById('edit-id').value = id;
  document.getElementById('edit-judul').value = judul;
  document.getElementById('edit-lokasi').value = lokasi;
  document.getElementById('edit-tanggal').value = tanggal;
  document.getElementById('edit-harga').value = harga;
}
function setFormHapus(id) {
  document.getElementById('hapus-id').value = id;
}
</script>

</body>
</html>

