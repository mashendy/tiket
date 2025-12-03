<?php
// AKTIFKAN ERROR REPORTING UNTUK DEBUGGING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

echo "<!DOCTYPE html><html><head><title>Debug kirim_tiket_proses</title>";
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo "</head><body class='p-3'>";
echo "<h1>Debugging kirim_tiket_proses.php</h1>";

// 1. Validasi id_tiket
if (!isset($_GET['id_tiket']) || empty(trim($_GET['id_tiket'])) || !is_numeric($_GET['id_tiket'])) {
    echo "<p class='alert alert-danger'><strong>Error:</strong> ID Tiket tidak valid atau tidak ditemukan di URL.</p>";
    echo "<a href='petugas.php' class='btn btn-primary mt-2'>Kembali ke Petugas</a>";
    echo "</body></html>";
    exit;
}

$id_tiket = (int)$_GET['id_tiket'];
$status_baru_tiket = 'terkirim';

echo "<p><strong>ID Tiket diterima:</strong> " . htmlspecialchars($id_tiket) . "</p>";
echo "<p><strong>Status baru yang akan diset:</strong> " . htmlspecialchars($status_baru_tiket) . "</p>";

$pdo->beginTransaction();
echo "<p>Memulai transaksi database...</p>";

try {
    // 2. Pengecekan tiket valid dan pembayaran selesai
    echo "<h2>Langkah 1: Pengecekan Tiket dan Pembayaran</h2>";
    $sql_check = "SELECT t.id_tiket, p.status_pembayaran
                  FROM tiket t
                  JOIN pembayaran p ON t.id_tiket = p.id_tiket
                  WHERE t.id_tiket = :id_tiket"; // Hapus AND p.status_pembayaran sementara untuk debug
    $stmt_check = $pdo->prepare($sql_check);

    if (!$stmt_check) {
        $pdo->rollBack();
        $errorInfo = $pdo->errorInfo();
        echo "<p class='alert alert-danger'><strong>Error (prepare sql_check):</strong> Gagal menyiapkan pengecekan tiket. DB Error: " . htmlspecialchars($errorInfo[2] ?? 'Unknown error') . "</p>";
        echo "<a href='petugas.php?kirim_tiket_status=error&message=PrepareCheckFail&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
        echo "</body></html>";
        exit;
    }
    echo "<p>Query pengecekan disiapkan: <code>" . htmlspecialchars($sql_check) . "</code></p>";

    $stmt_check->bindParam(':id_tiket', $id_tiket, PDO::PARAM_INT);
    $execute_check_result = $stmt_check->execute();

    if (!$execute_check_result) {
        $pdo->rollBack();
        $errorInfo = $stmt_check->errorInfo();
        echo "<p class='alert alert-danger'><strong>Error (execute sql_check):</strong> Gagal menjalankan pengecekan tiket. DB Error: " . htmlspecialchars($errorInfo[2] ?? 'Unknown error') . "</p>";
        echo "<a href='petugas.php?kirim_tiket_status=error&message=ExecuteCheckFail&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
        echo "</body></html>";
        exit;
    }
    echo "<p>Query pengecekan dieksekusi.</p>";

    $tiket_info = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$tiket_info) {
        $pdo->rollBack();
        echo "<p class='alert alert-warning'><strong>Peringatan (sql_check):</strong> Tiket dengan ID " . htmlspecialchars($id_tiket) . " tidak ditemukan di database.</p>";
        echo "<a href='petugas.php?kirim_tiket_status=error&message=TiketNotFound&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
        echo "</body></html>";
        exit;
    } else {
        echo "<p>Informasi tiket ditemukan: <pre>" . htmlspecialchars(print_r($tiket_info, true)) . "</pre></p>";
        if ($tiket_info['status_pembayaran'] !== 'selesai') {
            $pdo->rollBack();
            echo "<p class='alert alert-warning'><strong>Peringatan (sql_check):</strong> Pembayaran untuk tiket ID " . htmlspecialchars($id_tiket) . " belum 'selesai'. Status saat ini: '" . htmlspecialchars($tiket_info['status_pembayaran']) . "'. Tidak dapat mengirim tiket.</p>";
            echo "<a href='petugas.php?kirim_tiket_status=error&message=PembayaranBelumSelesai&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
            echo "</body></html>";
            exit;
        }
        echo "<p class='alert alert-success'>Pengecekan tiket dan status pembayaran 'selesai' berhasil.</p>";
    }


    // 3. Update status tiket
    echo "<h2>Langkah 2: Update Status Tiket</h2>";
    $sql_update_tiket = "UPDATE tiket SET status_tiket = :status_baru_tiket WHERE id_tiket = :id_tiket_update";
    $stmt_update_tiket = $pdo->prepare($sql_update_tiket);

    if (!$stmt_update_tiket) {
        $pdo->rollBack();
        $errorInfo = $pdo->errorInfo();
        echo "<p class='alert alert-danger'><strong>Error (prepare sql_update_tiket):</strong> Gagal menyiapkan update status tiket. DB Error: " . htmlspecialchars($errorInfo[2] ?? 'Unknown error') . "</p>";
        echo "<a href='petugas.php?kirim_tiket_status=error&message=PrepareUpdateFail&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
        echo "</body></html>";
        exit;
    }
    echo "<p>Query update disiapkan: <code>" . htmlspecialchars($sql_update_tiket) . "</code></p>";

    // Menggunakan nama placeholder yang berbeda untuk menghindari konflik jika ada scope issue (meskipun kecil kemungkinannya di sini)
    $stmt_update_tiket->bindParam(':status_baru_tiket', $status_baru_tiket, PDO::PARAM_STR);
    $stmt_update_tiket->bindParam(':id_tiket_update', $id_tiket, PDO::PARAM_INT);

    $execute_update_result = $stmt_update_tiket->execute();

    if (!$execute_update_result) {
        $pdo->rollBack();
        $errorInfo = $stmt_update_tiket->errorInfo();
        echo "<p class='alert alert-danger'><strong>Error (execute sql_update_tiket):</strong> Gagal menjalankan update status tiket. DB Error: " . htmlspecialchars($errorInfo[2] ?? 'Unknown error') . "</p>";
        // INI ADALAH TEMPAT ERROR "UNKNOWN COLUMN" AKAN MUNCUL JIKA MASIH ADA
        echo "<a href='petugas.php?kirim_tiket_status=error&message=ExecuteUpdateFail-" . urlencode($errorInfo[1] ?? '0') . "&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
        echo "</body></html>";
        exit;
    }
    echo "<p>Query update dieksekusi.</p>";

    $rowCount = $stmt_update_tiket->rowCount();
    echo "<p><strong>Jumlah baris yang terpengaruh oleh UPDATE:</strong> " . $rowCount . "</p>";

    if ($rowCount > 0) {
        $pdo->commit();
        echo "<p class='alert alert-success'><strong>Berhasil:</strong> Status tiket berhasil diperbarui dan transaksi di-commit! Akan dialihkan...</p>";
        // header("Location: petugas.php?kirim_tiket_status=success&id_tiket=" . urlencode($id_tiket));
        // exit;
        echo '<meta http-equiv="refresh" content="3;url=petugas.php?kirim_tiket_status=success&id_tiket=' . urlencode($id_tiket) . '">'; // Redirect setelah 3 detik untuk melihat pesan
    } else {
        $pdo->rollBack();
        echo "<p class='alert alert-warning'><strong>Peringatan (Update):</strong> Tidak ada baris yang diperbarui. Kemungkinan ID tiket tidak ditemukan saat UPDATE atau status sudah sama. Transaksi di-rollback.</p>";
        echo "<a href='petugas.php?kirim_tiket_status=error&message=NoRowsUpdated&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        echo "<p>Transaksi di-rollback karena PDOException.</p>";
    }
    echo "<p class='alert alert-danger'><strong>PDOException:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    // Tampilkan query jika ada di error message
    if (strpos($e->getMessage(), 'SQL:') !== false) {
        echo "<p>Query yang bermasalah (dari exception): <code>" . htmlspecialchars(substr($e->getMessage(), strpos($e->getMessage(), 'SQL:') + 5)) . "</code></p>";
    }
    echo "<a href='petugas.php?kirim_tiket_status=error&message=PDOException&id_tiket=" . urlencode($id_tiket) . "' class='btn btn-primary mt-2'>Kembali</a>";
}

echo "</body></html>";
?>