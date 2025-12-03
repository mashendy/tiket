<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // Cek di tabel pengguna (admin, petugas)
        $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE email = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['jabatan'] = $user['jabatan'];

            if ($_SESSION['jabatan'] == 'admin') {
                $_SESSION['success'] = "Login berhasil sebagai Admin.";
                header('Location: index.php?page=member');
                exit;
            } elseif ($_SESSION['jabatan'] == 'petugas') {
                $_SESSION['success'] = "Login berhasil sebagai Petugas.";
                header('Location: index.php?page=petugas');
                exit;
            } else {
                $_SESSION['error'] = "Jabatan tidak dikenali.";
                header('Location: login.php');
                exit;
            }
        } else {
            // Cek di tabel member
            $stmt = $pdo->prepare("SELECT * FROM member WHERE email = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $member = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($member && password_verify($password, $member['password'])) {
                session_regenerate_id(true);

                $_SESSION['id_member'] = $member['id_member'];
                $_SESSION['email'] = $member['email'];
                $_SESSION['nama'] = $member['nama'];

                $_SESSION['success'] = "Login berhasil sebagai Member.";
                header('Location: index.php?page=beli');
                exit;
            }
        }

        // Jika gagal login
        $_SESSION['error'] = "Email atau password salah.";
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Terjadi kesalahan: " . htmlspecialchars($e->getMessage());
        header('Location: login.php');
        exit;
    }
} else {
    // Jika bukan POST
    header('Location: login.php');
    exit;
}
?>