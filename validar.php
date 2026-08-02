<?php
session_start();
require 'dbcon.php';

// Verificar que el formulario se envió correctamente
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$username = mysqli_real_escape_string($con, trim($_POST['username']));
$password = $_POST['password'];

// Buscar usuario
$query = "SELECT * FROM usuarios WHERE username='$username' LIMIT 1";
$resultado = mysqli_query($con, $query);

if (mysqli_num_rows($resultado) == 1) {

    $usuario = mysqli_fetch_assoc($resultado);

    // Verificar que el usuario esté activo
    if ($usuario['estatus'] != 1) {

        $_SESSION['alert'] = [
            'title' => 'USUARIO INACTIVO',
            'message' => 'Tu cuenta se encuentra deshabilitada.',
            'icon' => 'warning'
        ];

        header("Location: index.php");
        exit();
    }

    // Verificar contraseña
    if (password_verify($password, $usuario['password'])) {

        $_SESSION['username'] = $usuario['username'];
        $_SESSION['rol'] = $usuario['rol'];

        header("Location: usuarios.php");
        exit();

    } else {

        $_SESSION['alert'] = [
            'title' => 'ERROR',
            'message' => 'Contraseña incorrecta.',
            'icon' => 'error'
        ];

        header("Location: index.php");
        exit();

    }

} else {

    $_SESSION['alert'] = [
        'title' => 'ERROR',
        'message' => 'El usuario no existe.',
        'icon' => 'error'
    ];

    header("Location: index.php");
    exit();

}
?>