<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

if (!isset($_SESSION['usuario_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUCARI Workspace - Hub</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎁</text></svg>">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        /* Ajuste específico de elevación para las tarjetas del Hub */
        .hub-card {
            border: 1px solid var(--border);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative; overflow: hidden;
        }
        .hub-card:hover:not(.disabled) {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            background: rgba(26, 115, 232, 0.01);
        }
        html.dark-mode .hub-card:hover:not(.disabled) {
            background: rgba(255,255,255,0.01);
        }
    </style>
</head>
<body>
<div class="container">
    <header class="app-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="logo-icon" style="font-size:35px;">🎁</div>
            <div>
                <h1>JUCARI Workspace</h1>
                <p>Hola, <strong><?= htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>. Selecciona un módulo:</p>
            </div>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button id="theme-toggle" class="theme-toggle-btn" title="Modo Noche">🌙</button>
            <a href="logout.php" class="btn btn-danger" style="color:white;">Cerrar Sesión</a>
        </div>
    </header>

    <div class="hub-grid">
        <a href="panel.php" class="hub-card">
            <div class="icon" style="color:var(--primary);">
                <svg width="45" height="45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h5m-6 5h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3>Etiquetas y PDF</h3>
            <p>Genera los catálogos en PDF listos para impresión forzada por departamento.</p>
        </a>

        <a href="panel.php" class="hub-card">
            <div class="icon" style="color:var(--success);">
                <svg width="45" height="45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <h3>Gestor de Productos</h3>
            <p>Agrega, edita, busca y elimina productos. Edición masiva habilitada.</p>
        </a>

        <div class="hub-card disabled">
            <div class="icon" style="color:var(--accent);">
                <svg width="45" height="45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3>Generación de Órdenes</h3>
            <p>Módulo para levantar pedidos rápidos a clientes mayoristas de sucursales.</p>
        </div>

        <div class="hub-card disabled">
            <div class="icon" style="color:var(--danger);">
                <svg width="45" height="45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
            </div>
            <h3>Control de Stock</h3>
            <p>Próximo sistema integrado de entradas, salidas y alertas de stock bajo.</p>
        </div>
    </div>
</div>

<script>
    // Pequeño script local para el tema del hub
    document.addEventListener("DOMContentLoaded", () => {
        const themeToggle = document.getElementById('theme-toggle');
        if(themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark-mode');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light');
                themeToggle.innerText = document.documentElement.classList.contains('dark-mode') ? '☀️' : '🌙';
            });
            themeToggle.innerText = document.documentElement.classList.contains('dark-mode') ? '☀️' : '🌙';
        }
    });
</script>
</body>
</html>