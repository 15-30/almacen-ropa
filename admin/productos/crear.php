<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../auth/login.php');
    exit();
}
require_once '../../config/db.php';

// Obtener categorías para el select
$stmt_cat = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];
    
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = time() . '_' . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../../assets/img/productos/' . $imagen);
    }

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, talla, color, precio, stock, categoria_id, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $talla, $color, $precio, $stock, $categoria_id, $imagen]);
    
    header('Location: listar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Crear Producto</h1>
        <nav>
            <a href="listar.php">Volver</a>
        </nav>
    </div>

    <div class="container">
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <!-- Column 1 -->
                    <div>
                        <div class="form-group">
                            <label>Nombre del Producto:</label>
                            <input type="text" name="nombre" required placeholder="Ej: Camiseta Negra">
                        </div>
                        
                        <div class="form-group">
                            <label>Categoría:</label>
                            <select name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Talla:</label>
                            <input type="text" name="talla" required placeholder="Ej: M, L, 42">
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div>
                        <div class="form-group">
                            <label>Color:</label>
                            <input type="text" name="color" required placeholder="Ej: Negro, Azul">
                        </div>
                        
                        <div class="form-group">
                            <label>Precio:</label>
                            <input type="number" step="0.01" name="precio" required placeholder="0.00">
                        </div>
                        
                        <div class="form-group">
                            <label>Stock:</label>
                            <input type="number" name="stock" required placeholder="Cantidad disponible">
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Imagen del Producto:</label>
                    <input type="file" name="imagen" accept="image/*">
                </div>
                
                <div class="form-actions">
                    <a href="listar.php" class="btn btn-danger" style="margin-right: 10px;">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../../includes/footer.php'; ?>
</body>
</html>
