<?php
require 'config.php'; // Pastikan konfigurasi database sudah disertakan

// Cek apakah ID member ada dalam URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pastikan ID member valid (numeric)
    if (is_numeric($id)) {
        // Siapkan query untuk menghapus member berdasarkan ID
        $stmt = $pdo->prepare("DELETE FROM member WHERE id_member = ?");
        $stmt->execute([$id]);

        // Setelah penghapusan, arahkan kembali ke halaman utama dengan pesan sukses
        header("Location: index.php?page=member&pesan=" . urlencode('Data member berhasil dihapus.'));
        exit();
    } else {
        // Jika ID tidak valid
        header("Location: index.php?page=member&pesan=" . urlencode('ID member tidak valid.'));
        exit();
    }
} else {
    // Jika ID tidak ditemukan dalam URL
    header("Location: index.php?page=member&pesan=" . urlencode('ID member tidak ditemukan.'));
    exit();
}
?>
