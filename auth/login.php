<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Almacén de Ropa</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="validar_login.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
