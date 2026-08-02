<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

header("Content-Type: application/json; charset=UTF-8");

$ids = isset($_POST['ids']) ? $_POST['ids'] : [];

if (empty($ids)) {
    echo json_encode([]);
    exit;
}

// Sanitizar IDs
$ids_sanitized = array_map('intval', $ids);
$ids_str = implode(',', $ids_sanitized);

$query = "SELECT 
    p.id AS productoID,
    p.titulo,
    p.subtitulo,
    p.preciounitario,
    p.descuento,
    p.preciomayoreo,
    p.cantidadmayoreo,
    (SELECT medio FROM mediosventa WHERE idproducto = p.id ORDER BY id LIMIT 1) AS primer_medio
FROM productosventa p
WHERE p.id IN ($ids_str)";

$result = mysqli_query($con, $query);

$productos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = $row;
}

echo json_encode($productos);