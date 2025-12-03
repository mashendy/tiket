<?php
include 'config.php';

if (isset($_GET['id_pembayaran'])) {
    $id_pembayaran = $_GET['id_pembayaran'];

    // Ambil id_tiket dari pembayaran
    $stmt = $pdo->prepare("SELECT id_tiket FROM pembayaran WHERE id_pembayaran = ?");
    $stmt->execute([$id_pembayaran]);
    $row = $stmt->fetch();

    if ($row) {
        $id_tiket = $row['id_tiket'];

        // Hapus pembayaran
        $stmt = $pdo->prepare("DELETE FROM pembayaran WHERE id_pembayaran = ?");
        $stmt->execute([$id_pembayaran]);

        // Hapus tiket terkait
        $stmt = $pdo->prepare("DELETE FROM tiket WHERE id_tiket = ?");
        $stmt->execute([$id_tiket]);

        header("Location: petugas.php?delete_status=success");
        exit;
    } else {
        header("Location: petugas.php?delete_status=failed");
        exit;
    }
} else {
    header("Location: petugas.php");
    exit;
}
?>