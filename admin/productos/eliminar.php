<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
$stmt->execute([$id]);

header('Location: listar.php');
exit();
?>
