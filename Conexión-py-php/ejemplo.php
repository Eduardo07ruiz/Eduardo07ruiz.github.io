
<?php
$servername="localhost";
$database="ejemplo";
$username="root";
$password="";

$conexion=
mysqli_connect($servername, $username, $password, $database);

if (!$conexion){
    die("Conexión fallida: " . mysqli_connect_error());
}

echo "<h2>Conexión exitosa</h2>";
mysqli_close($conexion);
?>