<?php
include 'config.php';

$judul = $_POST['judul'];
$lokasi = $_POST['lokasi'];
$tanggal = $_POST['tanggal_acara'];
$harga = $_POST['harga'];
$dibuat_oleh = 1; // ganti sesuai ID user login (jika ada session login)

$foto = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "uploads/";

    if (!file_exists($folder)) {
        mkdir($folder, 0755, true); // buat folder jika belum ada
    }

    move_uploaded_file($tmp, $folder . $foto);
}

$query = "INSERT INTO acara (judul, lokasi, tanggal_acara, harga, foto, dibuat_oleh) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->execute([$judul, $lokasi, $tanggal, $harga, $foto, $dibuat_oleh]);

header("Location: index.php?page=acara");
exit;
?>