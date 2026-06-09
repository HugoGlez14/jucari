<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

if (!isset($_SESSION['usuario_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>JUCARI Workspace - Panel</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎁</text></svg>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<!-- MODALES -->
<div id="alert-modal" class="modal-overlay">
    <div class="modal-box" style="text-align: center; max-width: 350px;">
        <div style="color: var(--accent); margin-bottom: 10px; font-size:40px;">⚠️</div>
        <h3 class="modal-title" style="justify-content: center; margin-bottom:10px;">Atención</h3>
        <p id="alert-msg" style="color: var(--text-muted); margin-bottom: 20px; font-size:14px;"></p>
        <button class="btn btn-primary" onclick="closeModal('alert-modal')" style="width: 100%;">Entendido</button>
    </div>
</div>

<div id="edit-modal" class="modal-overlay">
    <div class="modal-box">
        <h3 class="modal-title">✏️ Editar Producto</h3>
        <input type="hidden" id="edit-id">
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Nombre del Producto</label>
            <input type="text" id="edit-nombre">
        </div>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Precio ($)</label>
            <input type="number" id="edit-precio" step="0.01">
        </div>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Departamento</label>
            <input type="text" id="edit-depto">
        </div>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Código de Barras (Opcional)</label>
            <input type="text" id="edit-ean">
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('edit-modal')">Cancelar</button>
            <button class="btn btn-primary" onclick="guardarEdicion()">Guardar</button>
        </div>
    </div>
</div>

<div id="bulk-edit-modal" class="modal-overlay">
    <div class="modal-box">
        <h3 class="modal-title">✨ Edición Masiva</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 0; margin-bottom: 20px;">
            Aplicando a <strong id="bulk-count" style="color:var(--primary);">0</strong> productos.
        </p>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Prefijo / Sufijo en Nombre</label>
            <div style="display:flex; gap:10px;">
                <input type="text" id="bulk-prefijo" placeholder="Al INICIO">
                <input type="text" id="bulk-sufijo" placeholder="Al FINAL">
            </div>
        </div>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Fijar Nuevo Precio ($)</label>
            <input type="number" id="bulk-precio" step="0.01" placeholder="Vacío = No cambiar">
        </div>
        <div class="control-group" style="margin-bottom: 15px;">
            <label>Mover de Departamento</label>
            <select id="bulk-depto-select" onchange="manejarNuevoDeptoBulk()">
                <option value="">-- No cambiar --</option>
                <option value="NEW">Nuevo Departamento...</option>
            </select>
            <input type="text" id="bulk-depto-new" placeholder="Escribe el nombre..." style="display:none; margin-top: 5px;">
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('bulk-edit-modal')">Cancelar</button>
            <button class="btn btn-primary" onclick="aplicarEdicionMasiva()">Aplicar</button>
        </div>
    </div>
</div>

<div id="seleccion-modal" class="modal-overlay">
    <div class="modal-box large">
        <h3 class="modal-title">🛍️ Selección Personalizada</h3>
        <div class="modal-body-scroll" id="lista-seleccion-custom" style="max-height: 50vh;"></div>
        <div class="modal-footer" style="justify-content: space-between;">
            <button class="btn btn-danger" onclick="limpiarSeleccion()">Limpiar Todo</button>
            <button class="btn btn-primary" onclick="closeModal('seleccion-modal')">Cerrar</button>
        </div>
    </div>
</div>

<div id="dept-modal" class="modal-overlay">
    <div class="modal-box large">
        <h3 class="modal-title">🎛️ Filtro de Departamentos</h3>
        <div class="modal-body-scroll" id="lista-deptos-modal" style="max-height: 50vh;"></div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeModal('dept-modal')">Listo</button>
        </div>
    </div>
</div>

<!-- LOADER CON EL REGALO Y BOTÓN CANCELAR -->
<div id="loader-overlay">
    <div class="gift-loader">🎁</div>
    <div class="loader-text" id="loader-title">Procesando...</div>
    <div class="loader-subtext" id="loader-subtitle">Espera un momento.</div>
    <div class="progress-bg" id="loader-progress"><div class="progress-bar" id="pdf-progress"></div></div>
    <button id="btn-cancel-pdf" class="btn btn-danger" style="margin-top: 20px; display: none;">Detener y Cancelar</button>
</div>

<div class="container">
    <header class="app-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="logo-icon">🎁</div>
            <div>
                <h1>JUCARI Workspace</h1>
                <p>Empleado: <strong><?= htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            </div>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="hub.php" class="btn btn-secondary">🏠 Hub</a>
            <button id="theme-toggle" class="theme-toggle-btn" title="Modo Noche">🌙</button>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </header>

    <!-- PANEL 1: CONFIGURACIÓN -->
    <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed var(--border); padding-bottom: 10px; margin-bottom: 20px;">
            <h3 style="border:none; margin:0; padding:0;">⚙️ Configuración de Impresión</h3>
            <button id="btn-modal-seleccion" class="btn btn-secondary" onclick="abrirModalSeleccion()">🛍️ Selección (<span id="count-selection" style="color:var(--primary); font-weight:bold;">0</span>)</button>
        </div>

        <div class="controls">
            <div class="control-group">
                <label>1. Base de Datos</label>
                <div class="file-wrapper">
                    <!-- ARREGLADO: Solo el input transparente encima del botón diseñado -->
                    <button class="btn btn-upload">📁 Subir Excel/CSV</button>
                    <input type="file" id="archivo-excel" accept=".xlsx, .xls, .csv">
                </div>
            </div>
            <div class="control-group">
                <label>2. Imprimir</label>
                <select id="filtro-dept" disabled><option value="">-- Esperando archivo --</option></select>
            </div>
            <div class="control-group">
                <label>3. Ordenación</label>
                <select id="filtro-orden" onchange="cambiarOrdenGlobal()" disabled>
                    <option value="nombre_asc">🔤 Alfabético (A - Z)</option>
                    <option value="precio_asc">💵 Precio (Menor a Mayor)</option>
                    <option value="precio_desc">💰 Precio (Mayor a Menor)</option>
                </select>
            </div>
            <div class="control-group" style="flex:0; min-width:auto;">
                <label>&nbsp;</label>
                <button id="btn-toggle-filtros" class="btn btn-secondary" disabled onclick="abrirModalDeptos()">🎛️ Deptos</button>
            </div>
            <div class="control-group" id="contenedor-lote" style="display:none;">
                <label>Lote</label>
                <select id="filtro-lote"></select>
            </div>
            <div class="control-group" style="width: 70px; flex:none;">
                <label>Pág.</label>
                <input type="number" id="start-page" value="1" min="1" style="text-align: center;">
            </div>
        </div>

        <div style="margin-top: 25px; display: flex; gap: 10px; flex-wrap: wrap; border-top: 1px solid var(--border); padding-top: 20px;">
            <button id="btn-imprimir-nativo" class="btn btn-print" disabled>🖨️ Imprimir Etiquetas (Rápido)</button>
            <button id="btn-descargar-pdf" class="btn btn-primary" disabled>📄 Generar PDF (Exportar)</button>
        </div>
    </div>

    <!-- PANEL 2: GESTOR -->
    <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed var(--border); padding-bottom: 10px; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <h3 style="border:none; margin:0; padding:0;">📦 Gestor de Inventario</h3>
            <div style="display: flex; gap: 10px;">
                <button id="btn-exportar-base" class="btn btn-secondary" disabled>📥 Excel Básico</button>
                <button id="btn-exportar-excel" class="btn btn-success" disabled>📥 Eleventa</button>
                <button id="btn-exportar-clip" class="btn btn-purple" disabled>📥 Clip</button>
            </div>
        </div>
        
        <div class="form-add">
            <div class="control-group" style="flex: 2;">
                <label>Producto Nuevo</label>
                <input type="text" id="add-nombre" placeholder="Ej. Peluche Oso">
            </div>
            <div class="control-group" style="flex: 0.5;">
                <label>Precio ($)</label>
                <input type="number" id="add-precio" placeholder="0.00" step="0.01">
            </div>
            <div class="control-group" style="flex: 1.5;">
                <label>Departamento</label>
                <select id="add-depto-select" onchange="manejarNuevoDepto()">
                    <option value="">Seleccionar...</option>
                    <option value="NEW">➕ Nuevo Depto...</option>
                </select>
                <input type="text" id="add-depto-new" placeholder="Escribe el nombre..." style="display:none; margin-top: 5px;">
            </div>
            <div class="control-group" style="flex: 1;">
                <label>Cód. Barras</label>
                <input type="text" id="add-ean" placeholder="Opcional">
            </div>
            <button id="btn-agregar" class="btn btn-success">➕ Añadir</button>
        </div>

        <div class="table-controls">
            <input type="text" id="search-input" class="search-box" placeholder="🔍 Buscar registros..." onkeyup="triggerSearch()" disabled>
            <select id="table-filter-dept" onchange="triggerSearch()" disabled style="max-width: 250px;">
                <option value="">📁 Todos los departamentos</option>
            </select>
            
            <div style="margin-left: auto; display: flex; align-items: center; gap: 15px;">
                <span id="search-count" style="font-size: 13px; font-weight: bold; color: var(--secondary);"></span>
                <button id="btn-bulk-edit" class="btn btn-purple" onclick="abrirModalEdicionMasiva()" disabled>✏️ Edición Masiva</button>
            </div>
        </div>

        <div class="table-wrap" id="tabla-registros">
            <p style="text-align:center; color: var(--text-muted); padding: 40px 0;">Sube un archivo para comenzar.</p>
        </div>
        <div class="pagination" id="pagination-container"></div>
    </div>

    <div id="pdf-wrapper">
        <div id="pdf-zone"></div>
    </div>
</div>

<script src="js/app.js"></script>
</body>
</html>