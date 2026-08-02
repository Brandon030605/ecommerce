<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;
unset($_SESSION['alert']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-area img {
            height: 55px;
            margin-bottom: 16px;
        }

        .logo-area h4 {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.4rem;
            margin: 0;
        }

        .logo-area p {
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            margin: 4px 0 0;
        }

        .form-label {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .input-group-text {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-right: none;
            color: rgba(255,255,255,0.5);
        }

        .form-control {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-left: none;
            color: #ffffff;
            padding: 12px 16px;
            border-radius: 0 12px 12px 0;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: rgba(99, 179, 237, 0.6);
            box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.15);
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.25);
        }

        .input-group {
            border-radius: 12px;
            overflow: hidden;
        }

        .input-group .input-group-text {
            border-radius: 12px 0 0 12px;
        }

        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 13px;
            width: 100%;
            margin-top: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0 20px;
            gap: 12px;
        }

        .divider hr {
            flex: 1;
            border-color: rgba(255,255,255,0.1);
            margin: 0;
        }

        .divider span {
            color: rgba(255,255,255,0.35);
            font-size: 0.78rem;
            white-space: nowrap;
        }

        .store-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .store-link:hover {
            color: #3b82f6;
        }

        .store-link i {
            font-size: 1rem;
        }
    </style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Logo -->
        <div class="logo-area">
            <img src="images/logo.png" alt="Logo" onerror="this.style.display='none'">
            <h4>Bienvenido</h4>
            <p>Inicia sesión para continuar</p>
        </div>

        <!-- Form -->
        <form action="codelogin.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        type="email"
                        class="form-control"
                        name="username"
                        placeholder="correo@ejemplo.com"
                        required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="••••••••"
                        required>
                </div>
            </div>

            <button type="submit" class="btn-login" name="login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>

        </form>

        <div class="divider">
            <hr>
            <span>o</span>
            <hr>
        </div>

        <a href="tienda-en-linea.php" class="store-link">
            <i class="bi bi-shop"></i>
            Ver tienda en línea
        </a>

    </div>
</div>

<?php if ($alert): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: <?= json_encode($alert['title']) ?>,
        text: <?= isset($alert['message']) ? json_encode($alert['message']) : '""' ?>,
        icon: <?= json_encode($alert['icon']) ?>
    });
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
