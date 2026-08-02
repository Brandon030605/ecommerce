<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

if (!isset($_POST['save'])) {
    header("Location: pedido.php");
    exit(0);
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellidop = trim($_POST['apellidop'] ?? '');
$apellidom = trim($_POST['apellidom'] ?? '');
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$telefono  = trim($_POST['telefono'] ?? '');
$calle     = trim($_POST['calle'] ?? '');
$exterior  = trim($_POST['exterior'] ?? '');
$interior  = trim($_POST['interior'] ?? '');
$colonia   = trim($_POST['colonia'] ?? '');
$ciudad    = trim($_POST['ciudad'] ?? '');
$estado    = trim($_POST['estado'] ?? '');
$postal    = trim($_POST['postal'] ?? '');
$pais      = trim($_POST['pais'] ?? '');
$cupon     = trim($_POST['cuponLS'] ?? '');
$productos = $_POST['cartLS'] ?? '';
$estatus   = 1;

// Validar campos obligatorios
if (!$nombre || !$apellidop || !$apellidom || !$email || !$telefono || !$calle || !$exterior || !$colonia || !$ciudad || !$estado || !$postal || !$pais) {
    $_SESSION['alert'] = [
        'title'   => 'Error',
        'message' => 'Por favor completa todos los campos obligatorios.',
        'icon'    => 'error'
    ];
    header("Location: pedido.php");
    exit(0);
}

// Calcular totales desde el carrito
$cart = json_decode($productos, true) ?? [];
$ids = array_map('intval', array_column($cart, 'id'));

$subtotal    = 0;
$descuentoT  = 0;
$cuponMonto  = 0;

if (!empty($ids)) {
    $ids_str = implode(',', $ids);

    // Obtener comisión
    $resComision = $con->query("SELECT valoruno FROM configuraciones WHERE id=4 LIMIT 1");
    $rowComision = $resComision->fetch_assoc();
    $comisionFactor = (float)str_replace('%', '', $rowComision['valoruno'] ?? 0) / 100;

    // Obtener config envío
    $resEnvio = $con->query("SELECT valoruno, valordos FROM configuraciones WHERE nombre='Envio' LIMIT 1");
    $rowEnvio = $resEnvio->fetch_assoc();
    $envioMinimo = (float)($rowEnvio['valoruno'] ?? 0);
    $envioCosto  = (float)($rowEnvio['valordos'] ?? 0);

    // Obtener productos
    $query = "SELECT id AS productoID, preciounitario, descuento, preciomayoreo, cantidadmayoreo, sku, titulo
              FROM productosventa WHERE id IN ($ids_str)";
    $result = $con->query($query);

    $productosMap = [];
    while ($row = $result->fetch_assoc()) {
        $productosMap[$row['productoID']] = $row;
    }

    foreach ($cart as $item) {
        $id  = (int)$item['id'];
        $qty = (int)$item['cantidad'];
        if (!isset($productosMap[$id])) continue;

        $prod = $productosMap[$id];
        $pUnitario  = (float)$prod['preciounitario'];
        $pMayoreo   = (float)$prod['preciomayoreo'];
        $minMayoreo = (int)$prod['cantidadmayoreo'];
        $descuento  = (float)$prod['descuento'];

        $aplicaMayoreo = ($minMayoreo > 0 && $pMayoreo > 0 && $pMayoreo < $pUnitario && $qty >= $minMayoreo);
        $precioBase    = $aplicaMayoreo ? $pMayoreo : $pUnitario;
        $descuentoReal = $aplicaMayoreo ? 0 : $descuento;

        $precioConComision = $precioBase * (1 + $comisionFactor);
        $subtotal   += $precioConComision * $qty;
        $descuentoT += $descuentoReal * $qty;
    }

    // Validar cupón
    if (!empty($cupon)) {
        $stmtCupon = $con->prepare("SELECT descuento, tipo FROM cupones WHERE codigo = ? AND estatus = 1 LIMIT 1");
        $stmtCupon->bind_param('s', $cupon);
        $stmtCupon->execute();
        $stmtCupon->bind_result($cupDescuento, $cupTipo);
        if ($stmtCupon->fetch()) {
            $cuponMonto = (float)$cupDescuento;
        }
        $stmtCupon->close();
    }

    // Calcular envío
    $baseParaEnvio = $subtotal - $descuentoT - $cuponMonto;
    $envioMonto    = ($baseParaEnvio < $envioMinimo) ? $envioCosto : 0;

    $total = max(0, $subtotal - $descuentoT - $cuponMonto + $envioMonto);
} else {
    $envioMonto = 0;
    $total      = 0;
}

// Insertar pedido
$sql = "INSERT INTO pedidos 
        (nombre, apellidop, apellidom, email, telefono, calle, exterior, interior, colonia, ciudad, estado, postal, pais, cupon, productos, subtotal, cuponMonto, envioMonto, total, estatus)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param(
    "sssssssssssssssddddi",
    $nombre, $apellidop, $apellidom, $email, $telefono,
    $calle, $exterior, $interior, $colonia, $ciudad,
    $estado, $postal, $pais, $cupon, $productos,
    $subtotal, $cuponMonto, $envioMonto, $total, $estatus
);

if ($stmt->execute()) {
    $last_id = $con->insert_id;
    $stmt->close();

    // Generar identificador
    $folio_num     = str_pad($last_id, 7, "0", STR_PAD_LEFT);
    $iniciales     = strtoupper(substr($nombre, 0, 1) . substr($apellidop, 0, 1) . substr($apellidom, 0, 1));
    $identificador = "MIEMPRESA-$folio_num-$iniciales";

    $up_stmt = $con->prepare("UPDATE pedidos SET identificador=? WHERE id=?");
    $up_stmt->bind_param("si", $identificador, $last_id);
    $up_stmt->execute();
    $up_stmt->close();

    // Insertar ventas (detalle de productos)
    if (!empty($ids)) {
        foreach ($cart as $item) {
            $id  = (int)$item['id'];
            $qty = (int)$item['cantidad'];
            if (!isset($productosMap[$id])) continue;

            $prod = $productosMap[$id];
            $pUnitario  = (float)$prod['preciounitario'];
            $pMayoreo   = (float)$prod['preciomayoreo'];
            $minMayoreo = (int)$prod['cantidadmayoreo'];
            $descuento  = (float)$prod['descuento'];

            $aplicaMayoreo = ($minMayoreo > 0 && $pMayoreo > 0 && $pMayoreo < $pUnitario && $qty >= $minMayoreo);
            $precioBase    = $aplicaMayoreo ? $pMayoreo : $pUnitario;
            $descuentoReal = $aplicaMayoreo ? 0 : $descuento;
            $precioConComision = $precioBase * (1 + $comisionFactor);

            $stmtV = $con->prepare("INSERT INTO ventas (identificador, titulo, sku, cantidad, precio, descuento) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtV->bind_param("sssidi", $identificador, $prod['titulo'], $prod['sku'], $qty, $precioConComision, $descuentoReal);
            $stmtV->execute();
            $stmtV->close();
        }
    }

    header("Location: pago.php?id=$identificador");
    exit(0);
} else {
    $_SESSION['alert'] = [
        'title'   => 'Error',
        'message' => 'No se pudo guardar el pedido. Intenta de nuevo.',
        'icon'    => 'error'
    ];
    header("Location: pedido.php");
    exit(0);
}