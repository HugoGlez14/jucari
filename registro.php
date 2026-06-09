<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
require 'conexion.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim(htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8'));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password']; 

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtCheck->execute(['email' => $email]);
        if ($stmtCheck->rowCount() > 0) { $mensaje = '<div class="alert error">⚠️ El correo ya existe.</div>'; } 
        else {
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(['nombre' => $nombre, 'email' => $email, 'password' => $passwordHash])) {
                $mensaje = '<div class="alert success">✨ Registrado. <a href="login.php">Inicia sesión</a></div>';
            } else { $mensaje = '<div class="alert error">❌ Error interno.</div>'; }
        }
    } else { $mensaje = '<div class="alert error">⚠️ Llena todo.</div>'; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - JUCARI</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <button id="theme-toggle" class="theme-toggle-btn" title="Tema">🌙</button>

    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="logo">🧸</div>
            <h2>¡Únete al equipo!</h2>
            <p class="sub">Crea tu cuenta administrativa</p>
            
            <?= $mensaje ?>

            <form method="POST" action="" autocomplete="off">
                <input style="display:none" type="email" name="femail"/>
                <input style="display:none" type="password" name="fpass"/>

                <div class="control-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" placeholder="Ej. Ana López" required autocomplete="off">
                </div>
                <div class="control-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="correo@ejemplo.com" required autocomplete="off">
                </div>
                <div class="control-group">
                    <label>Contraseña Nueva</label>
                    <div class="password-field-container">
                        <input type="password" id="auth-password" name="password" placeholder="••••••••" required autocomplete="new-password">
                        <button type="button" id="auth-toggle-btn" class="toggle-password-btn">👁️</button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Registrarme 🌟</button>
            </form>
            <div class="auth-link">¿Ya tienes una cuenta? <a href="login.php">Entra aquí</a></div>
        </div>
    </div>
    <script src="js/auth.js"></script>
</body>
</html>