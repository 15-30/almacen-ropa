<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../auth/login.php');
    exit();
}
require_once '../config/db.php';

// Obtener estadísticas
$stmt_prod = $pdo->query("SELECT COUNT(*) FROM productos");
$total_productos = $stmt_prod->fetchColumn();

$stmt_cat = $pdo->query("SELECT COUNT(*) FROM categorias");
$total_categorias = $stmt_cat->fetchColumn();

$stmt_stock = $pdo->query("SELECT COUNT(*) FROM productos WHERE stock < 5");
$productos_bajo_stock = $stmt_stock->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Almacén de Ropa</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Panel de Administración</h1>
        <nav>
            <a href="categorias/listar.php">Categorías</a>
            <a href="productos/listar.php">Productos</a>
            <a href="../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </div>

    <div class="container">
        <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?></h2>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Productos</h3>
                <p><?php echo $total_productos; ?></p>
            </div>
            <div class="card">
                <h3>Total Categorías</h3>
                <p><?php echo $total_categorias; ?></p>
            </div>
            <div class="card">
                <h3>Productos Bajo Stock</h3>
                <p><?php echo $productos_bajo_stock; ?></p>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center; display: flex; justify-content: center; gap: 20px;">
            <a href="productos/listar.php" class="btn btn-primary" style="padding: 15px 30px; font-size: 1.2em;">Gestionar y Editar Productos</a>
            <a href="../productos.php" class="btn btn-success" style="padding: 15px 30px; font-size: 1.2em;" target="_blank">Ver Catálogo Público</a>
        </div>
    </div>
    <?php include_once '../includes/footer.php'; ?>
</body>
</html>
