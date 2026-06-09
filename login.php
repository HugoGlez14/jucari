<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

require 'conexion.php';

if (isset($_SESSION['usuario_id'])) { header('Location: hub.php'); exit; }
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); $password = trim($_POST['password']);
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]); $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true); 
            $_SESSION['usuario_id'] = $usuario['id']; $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header('Location: hub.php'); exit;
        } else { $mensaje = '<div class="alert error">❌ Correo o contraseña incorrectos.</div>'; }
    } else { $mensaje = '<div class="alert error">⚠️ Llena todos los campos.</div>'; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder - JUCARI</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <link rel="stylesheet" href="css/auth.css"> </head>
<body>
    <button id="theme-toggle" class="theme-toggle-btn" title="Tema">🌙</button>

    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="logo">🎉</div>
            <h2>¡Hola de nuevo!</h2>
            <p class="sub">Ingresa para administrar el Bazar</p>
            
            <?= $mensaje ?>

            <form method="POST" action="" autocomplete="off">
                <input style="display:none" type="email" name="femail"/>
                <input style="display:none" type="password" name="fpass"/>

                <div class="control-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="tucorreo@ejemplo.com" required autocomplete="off">
                </div>
                
                <div class="control-group">
                    <label>Contraseña</label>
                    <div class="password-field-container">
                        <input type="password" id="auth-password" name="password" placeholder="••••••••" required autocomplete="new-password">
                        <button type="button" id="auth-toggle-btn" class="toggle-password-btn">👁️</button>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Entrar al Sistema 🚀</button>
            </form>
            <div class="auth-link">¿No tienes cuenta? <a href="registro.php">Regístrate</a></div>
            <div style="border-bottom:1px solid #cbd5e1; margin:20px 0 15px 0;"></div>
            <div class="auth-link" style="margin:0;"><a href="index.php" style="color:#64748b; font-weight:500;">➔ Volver a la página de clientes</a></div>
        </div>
    </div>
    <script src="js/auth.js"></script>
</body>
</html>