<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="usuarios.php">
        <img src="images/logo.png" alt="Logo" style="height:40px;">
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="bi bi-list" style="font-size:1.4rem;color:#fff;"></i>
    </button>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle" style="font-size:1.3rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="tienda-en-linea.php"><i class="bi bi-shop me-2"></i>Ver tienda</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Principal</div>

                    <a class="nav-link" href="usuarios.php">
                        <div class="sb-nav-link-icon"><i class="bi bi-people-fill"></i></div>
                        Usuarios
                    </a>

                    <a class="nav-link" href="carga-tienda-en-linea.php">
                        <div class="sb-nav-link-icon"><i class="bi bi-bag-fill"></i></div>
                        Tienda en línea
                    </a>

                    <a class="nav-link" href="pedido.php">
                        <div class="sb-nav-link-icon"><i class="bi bi-receipt"></i></div>
                        Pedidos
                    </a>

                    <div class="sb-sidenav-menu-heading">Público</div>

                    <a class="nav-link" href="tienda-en-linea.php" target="_blank">
                        <div class="sb-nav-link-icon"><i class="bi bi-shop"></i></div>
                        Ver tienda
                    </a>

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Sesión iniciada como:</div>
                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitado'; ?>
            </div>
        </nav>
    </div>
