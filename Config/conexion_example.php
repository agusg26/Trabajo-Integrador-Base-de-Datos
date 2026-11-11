<?php
//Archivo de ejemplo para no subir credenciales, crear conexion.php que es el archivo correcto
$host = "localhost";
$dbname = "northwind";
$user; //= "usuario de la base de datoss";
$password; //= "Contraseña de la base de datos";

try {
    $pdo = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
