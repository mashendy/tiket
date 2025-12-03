<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $no_hp    = $_POST['no_hp'];
    $alamat   = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM member WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "Email sudah digunakan. Gunakan email lain.";
            header('Location: register.php');
            exit;
        }

        // Insert data member baru
        $stmt = $pdo->prepare("INSERT INTO member (nama, email, no_hp, alamat, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $no_hp, $alamat, $password]);

        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Pendaftaran gagal: " . $e->getMessage();
        header('Location: register.php');
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}
?>