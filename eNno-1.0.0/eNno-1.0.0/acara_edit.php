<?php
include 'config.php';

$id = $_POST['id_acara'];
$judul = $_POST['judul'];
$lokasi = $_POST['lokasi'];
$tanggal = $_POST['tanggal_acara'];
$harga = $_POST['harga'];

// Cek apakah ada file foto baru diupload
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "uploads/";

    move_uploaded_file($tmp, $folder . $foto);

    // Update dengan foto
    $query = "UPDATE acara SET judul=?, lokasi=?, tanggal_acara=?, harga=?, foto=? WHERE id_acara=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$judul, $lokasi, $tanggal, $harga, $foto, $id]);
} else {
    // Update tanpa mengubah foto
    $query = "UPDATE acara SET judul=?, lokasi=?, tanggal_acara=?, harga=? WHERE id_acara=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$judul, $lokasi, $tanggal, $harga, $id]);
}

header("Location: index.php?page=acara");
?>