<?php
include 'config.php';

// Ambil id_pembayaran dari query string
$id_pembayaran = $_GET['id_pembayaran'] ?? null;

if (!$id_pembayaran) {
    echo "ID pembayaran tidak ditemukan.";
    exit;
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    if ($status === 'selesai') {
        // Jika status selesai, update juga waktu pembayaran
        $stmt = $pdo->prepare("UPDATE pembayaran SET status_pembayaran = ?, dibayar_pada = CURRENT_TIMESTAMP WHERE id_pembayaran = ?");
    } else {
        // Jika status bukan selesai, jangan ubah waktu pembayaran
        $stmt = $pdo->prepare("UPDATE pembayaran SET status_pembayaran = ? WHERE id_pembayaran = ?");
    }

    $stmt->execute([$status, $id_pembayaran]);

    // Redirect setelah update dengan tanda sukses
    header("Location: petugas.php?status_update=success");
    exit;
}

// Ambil data pembayaran untuk ditampilkan di form
$stmt = $pdo->prepare("SELECT * FROM pembayaran WHERE id_pembayaran = ?");
$stmt->execute([$id_pembayaran]);
$data = $stmt->fetch();

if (!$data) {
    echo "Data pembayaran tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Status Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Update Status Pembayaran</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pembayaran</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="menunggu" <?= ($data['status_pembayaran'] === 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                            <option value="selesai" <?= ($data['status_pembayaran'] === 'selesai') ? 'selected' : '' ?>>Selesai</option>
                            <option value="gagal" <?= ($data['status_pembayaran'] === 'gagal') ? 'selected' : '' ?>>Gagal</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">Update Status</button>
                    <a href="petugas.php" class="btn btn-secondary btn-block mt-2">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
