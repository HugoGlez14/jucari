<?php
// logout.php
session_start();
session_destroy();

// Redirigir a la página principal pública (Landing Page)
header('Location: index.php');
exit;
?>