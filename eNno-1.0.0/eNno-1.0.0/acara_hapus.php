<?php
include 'config.php';

$id = $_POST['id_acara'];

$query = "DELETE FROM acara WHERE id_acara = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

header("Location: index.php?page=acara");
exit;
?>