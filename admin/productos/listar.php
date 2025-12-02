<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

$mensaje = '';
$producto_editar = null;

// 1. Handle Update (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];

    // Get current image to keep it if not changed
    $stmt_curr = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt_curr->execute([$id]);
    $current_img = $stmt_curr->fetchColumn();
    $imagen = $current_img;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = time() . '_' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../../assets/img/productos/' . $imagen);
    }

    $stmt = $pdo->prepare("UPDATE productos SET nombre=?, talla=?, color=?, precio=?, stock=?, categoria_id=?, imagen=? WHERE id=?");
    if ($stmt->execute([$nombre, $talla, $color, $precio, $stock, $categoria_id, $imagen, $id])) {
        $mensaje = "Producto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
}

// 2. Handle Select for Edit (GET)
if (isset($_GET['id'])) {
    $stmt_edit = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt_edit->execute([$_GET['id']]);
    $producto_editar = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

// 3. Fetch Data for Display
$stmt = $pdo->query("SELECT p.*, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id ORDER BY p.id DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt_cat = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos - Almacén de Ropa</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <style>
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
            display: block;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="admin-header">
        <h1>Gestión de Productos</h1>
        <nav>
            <a href="../index.php">Dashboard</a>
            <a href="../categorias/listar.php">Categorías</a>
            <a href="../../auth/logout.php">Cerrar Sesión</a>
        </nav>
    </div>

    <div class="container">
        <div class="card-container">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <h2 style="margin: 0; color: #333;">Lista de Productos</h2>
                <a href="crear.php" class="btn btn-primary">Nuevo Producto</a>
            </div>

            <?php if ($mensaje): ?>
                <div style="color: green; font-weight: bold; margin-bottom: 15px; text-align: center;">
                    <?php echo $mensaje; ?></div>
            <?php endif; ?>

            <div class="scrollable-table" style="border: none; box-shadow: none;">
                <!-- Remove extra border/shadow since it's in a card -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $prod): ?>
                            <tr
                                style="<?php echo ($producto_editar && $producto_editar['id'] == $prod['id']) ? 'background-color: #e8f0fe;' : ''; ?>">
                                <td><?php echo $prod['id']; ?></td>
                                <td>
                                    <?php if ($prod['imagen']): ?>
                                        <img src="../../assets/img/productos/<?php echo htmlspecialchars($prod['imagen']); ?>"
                                            width="40" style="border-radius: 4px;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($prod['categoria_nombre']); ?></td>
                                <td>$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</td>
                                <td><?php echo htmlspecialchars($prod['stock']); ?></td>
                                <td>
                                    <a href="listar.php?id=<?php echo $prod['id']; ?>#edit-form"
                                        class="btn btn-warning">Editar</a>
                                    <a href="eliminar.php?id=<?php echo $prod['id']; ?>" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Form Section -->
        <div id="edit-form" class="form-container">
            <?php if ($producto_editar): ?>
                <h2 style="margin-top: 0; text-align: center; margin-bottom: 20px;">Editar Producto:
                    <?php echo $producto_editar['nombre']; ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" name="id" value="<?php echo $producto_editar['id']; ?>">

                    <div class="form-grid">
                        <!-- Column 1 -->
                        <div>
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" value="<?php echo $producto_editar['nombre']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Categoría:</label>
                                <select name="categoria_id" required>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $producto_editar['categoria_id']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Talla:</label>
                                <input type="text" name="talla" value="<?php echo $producto_editar['talla']; ?>" required>
                            </div>
                        </div>

                        <!-- Column 2 -->
                        <div>
                            <div class="form-group">
                                <label>Color:</label>
                                <input type="text" name="color" value="<?php echo $producto_editar['color']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Precio:</label>
                                <input type="number" step="0.01" name="precio"
                                    value="<?php echo $producto_editar['precio']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Stock:</label>
                                <input type="number" name="stock" value="<?php echo $producto_editar['stock']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Imagen:</label>
                        <?php if ($producto_editar['imagen']): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../../assets/img/productos/<?php echo $producto_editar['imagen']; ?>" width="60"
                                    style="border: 1px solid #ddd; padding: 2px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="imagen">
                    </div>

                    <div class="form-actions">
                        <a href="listar.php" class="btn btn-danger" style="margin-right: 10px;">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p style="font-size: 1.2em;">Selecciona un producto de la lista para editarlo aquí.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../../includes/footer.php'; ?>
</body>

</html>