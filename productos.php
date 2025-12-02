<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query("SELECT p.*, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id ORDER BY p.id DESC");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar productos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - Almacén de Ropa</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div class="admin-header">
        <h1>Catálogo de Productos</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="auth/login.php">Iniciar Sesión</a>
        </nav>
    </div>

    <div class="container">
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php else: ?>
            <?php if (count($productos) > 0): ?>
                <div class="product-grid">
                    <?php foreach ($productos as $prod): ?>
                        <div class="product-card">
                            <?php if ($prod['imagen']): ?>
                                <img src="assets/img/productos/<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="background: #eee; display: flex; align-items: center; justify-content: center;">
                                    <span>Sin imagen</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <div>
                                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                                    <div class="product-category"><?php echo htmlspecialchars($prod['categoria_nombre']); ?></div>
                                    <p>Talla: <?php echo htmlspecialchars($prod['talla']); ?> | Color: <?php echo htmlspecialchars($prod['color']); ?></p>
                                </div>
                                <div class="product-price">$<?php echo number_format($prod['precio'], 0, ',', '.'); ?> COP</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p>No hay productos disponibles en este momento.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
