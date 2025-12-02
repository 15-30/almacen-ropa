<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener categorías
$stmt_cat = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];
    
    $imagen = $producto['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = time() . '_' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../../assets/img/productos/' . $imagen);
    }

    $stmt = $pdo->prepare("UPDATE productos SET nombre=?, talla=?, color=?, precio=?, stock=?, categoria_id=?, imagen=? WHERE id=?");
    $stmt->execute([$nombre, $talla, $color, $precio, $stock, $categoria_id, $imagen, $id]);
    
    header('Location: listar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Editar Producto</h1>
        <nav>
            <a href="listar.php">Volver</a>
        </nav>
    </div>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
            
            <label>Categoría:</label>
            <select name="categoria_id" required style="width: 100%; padding: 10px; margin: 10px 0;">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $producto['categoria_id']) ? 'selected' : ''; ?>>
                        <?php echo $cat['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label>Talla:</label>
            <input type="text" name="talla" value="<?php echo $producto['talla']; ?>" required>
            
            <label>Color:</label>
            <input type="text" name="color" value="<?php echo $producto['color']; ?>" required>
            
            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required>
            
            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required>
            
            <label>Imagen Actual:</label>
            <?php if ($producto['imagen']): ?>
                <img src="../../assets/img/productos/<?php echo $producto['imagen']; ?>" width="100"><br>
            <?php endif; ?>
            <label>Cambiar Imagen:</label>
            <input type="file" name="imagen">
            
            <button type="submit" class="btn btn-success">Actualizar</button>
        </form>
    </div>
</body>
</html>
