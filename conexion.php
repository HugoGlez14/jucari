<?php
// conexion.php
$host = 'localhost';
$dbname = 'jucari';
$user = 'root'; // Pon tu usuario de MySQL (por defecto en XAMPP es root)
$pass = '';     // Pon tu contraseña de MySQL (por defecto en XAMPP está vacía)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>