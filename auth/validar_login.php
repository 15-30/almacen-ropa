<?php
session_start();

$usuario_valido = 'admin';
$password_valido = 'admin';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    if ($usuario === $usuario_valido && $password === $password_valido) {
        $_SESSION['usuario'] = $usuario;
        header('Location: ../admin/index.php');
        exit();
    } else {
        header('Location: login.php?error=1');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>