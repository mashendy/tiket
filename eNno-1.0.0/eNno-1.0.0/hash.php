<?php
// File: hash.php
require_once 'config.php'; // Untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $nama = trim($_POST['nama']);

    // Hash password dengan password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Cek apakah email sudah ada di database
        $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            echo "Email sudah terdaftar!";
        } else {
            // Masukkan data pengguna baru ke dalam database
            $stmt = $pdo->prepare("INSERT INTO pengguna (email, password, nama) VALUES (:email, :password, :nama)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':nama', $nama);
            $stmt->execute();

            echo "Pendaftaran berhasil!";
        }
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>

<!-- Form Registrasi -->
<form action="hash.php" method="POST">
    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="nama" required><br><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <button type="submit">Daftar</button>
</form>
