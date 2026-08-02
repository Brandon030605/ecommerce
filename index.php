<?php
session_start();

$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;
unset($_SESSION['alert']);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Ecommerce</title>

    <link rel="stylesheet" href="css/styles.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <h2>Iniciar Sesión</h2>

    <form action="validar.php" method="POST">

        <label>Correo</label><br>

        <input
            type="email"
            name="username"
            autocomplete="username"
            required>

        <br><br>

        <label>Contraseña</label><br>

        <input
            type="password"
            name="password"
            autocomplete="current-password"
            required>

        <br><br>

        <button type="submit">
            Entrar
        </button>

    </form>

    <?php if ($alert) : ?>
        <script>
            Swal.fire({
                title: <?= json_encode($alert['title']) ?>,
                text: <?= json_encode($alert['message']) ?>,
                icon: <?= json_encode($alert['icon']) ?>,
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php endif; ?>

</body>

</html>