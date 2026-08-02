<?php
require 'dbcon.php';

// Datos del administrador
$nombre = "Administrador";
$apellidopaterno = "Principal";
$apellidomaterno = "Sistema";
$username = "admin@gmail.com";
$password = password_hash("123456", PASSWORD_DEFAULT);

$rol = 1;
$estatus = 1;

// Verificar si ya existe
$sql = "SELECT * FROM usuarios WHERE username='$username'";
$result = mysqli_query($con, $sql);

if(mysqli_num_rows($result)>0){

    echo "El administrador ya existe.";

}else{

    $query = "INSERT INTO usuarios
    (nombre, apellidopaterno, apellidomaterno, username, password, rol, estatus)
    VALUES
    ('$nombre','$apellidopaterno','$apellidomaterno','$username','$password','$rol','$estatus')";

    if(mysqli_query($con,$query)){
        echo "Administrador creado correctamente.";
    }else{
        echo mysqli_error($con);
    }

}