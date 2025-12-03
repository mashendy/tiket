<?php
$host = 'localhost'; // Ganti sesuai dengan host database Anda
$dbname = 'tiketku'; // Nama database
$username = 'root'; // Ganti sesuai dengan username database Anda
$password = ''; // Ganti sesuai dengan password database Anda

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set mode error PDO ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Menangkap error jika koneksi gagal
    die("Koneksi gagal: " . $e->getMessage());
}
?>
