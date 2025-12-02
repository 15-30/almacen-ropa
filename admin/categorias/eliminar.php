<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$id = $_GET['id'];
try {
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
} catch (Exception $e) {
    // Manejo básico de error (ej. si hay productos asociados)
    echo "<script>alert('No se puede eliminar: " . $e->getMessage() . "');</script>";
}
header('Location: listar.php');
exit();

