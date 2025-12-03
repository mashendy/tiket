<?php
session_start();
session_destroy();
session_start(); // Mulai session baru untuk menyimpan pesan
$_SESSION['success'] = "Anda telah berhasil logout.";
header('Location: login.php');
exit;
?>
