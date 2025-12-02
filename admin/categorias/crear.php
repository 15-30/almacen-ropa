<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    header('Location: listar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Crear Categoría</h1>
        <nav>
            <a href="listar.php">Volver</a>
        </nav>
    </div>

    <div class="container">
        <form method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    </div>
</body>
</html>
