<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$stmt = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - Almacén de Ropa</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Gestión de Categorías</h1>
        <nav>
            <a href="../index.php">Dashboard</a>
            <a href="../productos/listar.php">Productos</a>
            <a href="../../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </div>

    <div class="container">
        <a href="crear.php" class="btn btn-primary">Nueva Categoría</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo $cat['nombre']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $cat['id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?php echo $cat['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
