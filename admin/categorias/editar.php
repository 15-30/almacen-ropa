<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
    $stmt->execute([$nombre, $id]);
    header('Location: listar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Editar Categoría</h1>
        <nav>
            <a href="listar.php">Volver</a>
        </nav>
    </div>

    <div class="container">
        <form method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $categoria['nombre']; ?>" required>
            <button type="submit" class="btn btn-success">Actualizar</button>
        </form>
    </div>
</body>
</html>
