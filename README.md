# Almacén de Ropa

Sistema de gestión de inventario para una tienda de ropa desarrollado en PHP y MySQL.

## Créditos
**Desarrollado por:** Emily Alvear

## Instalación

1.  **Base de Datos**:
    -   Crea una base de datos llamada `almacen_ropa_db`.
    -   Importa el archivo `database_setup.sql` o ejecuta su contenido en tu gestor de MySQL.

2.  **Configuración**:
    -   Edita `config/db.php` si tus credenciales de MySQL no son las predeterminadas (root/vacío).

3.  **Ejecución**:
    -   Coloca la carpeta `almacen_ropa` en tu servidor web (ej. XAMPP `htdocs`).
    -   Accede a `http://localhost/almacen_ropa/` para ver la página pública.
    -   Para administrar, inicia sesión en `http://localhost/almacen_ropa/auth/login.php`.

## Uso

-   **Login**: Usuario: `admin`, Contraseña: `admin`.
-   **Dashboard**: Vista general de estadísticas.
-   **Categorías**: Crear, editar y eliminar categorías de ropa.
-   **Productos**: Gestión completa de productos con imagen, stock, precio, etc.

## Estructura del Proyecto

-   `admin/`: Panel de administración y gestión de contenido.
-   `assets/`: Recursos estáticos.
    -   `css/`: Hojas de estilo.
    -   `img/`: Imágenes del sistema y productos.
-   `auth/`: Scripts de autenticación (Login/Logout).
-   `config/`: Configuración de base de datos.
-   `includes/`: Componentes reutilizables (Header, Footer).
-   `index.php`: Página de inicio pública.
-   `productos.php`: Catálogo público de productos.
