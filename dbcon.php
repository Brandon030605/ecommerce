<?php

#$servidor = "localhost";
#"$usuario = "root";
#$contrasena = "";
#$basedatos = "ecommerce";

$servidor = "datallizer.com";
$usuario = "datallizer_novenoa";
$contrasena = "proyectonoveno2026";
$basedatos = "datallizer_ecommercea";

$con = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// echo "Conexión exitosa";

?>