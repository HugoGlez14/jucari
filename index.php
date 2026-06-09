<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUCARI - Novedades y Regalos de Mayoreo</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎁</text></svg>">
    <link rel="stylesheet" href="css/landing.css">
</head>
<body>

    <nav class="landing-nav">
        <a href="#" class="logo">🎁 JUCARI</a>
        <ul>
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#catalogos">Catálogos</a></li>
            <li><a href="#ubicaciones">Sucursales</a></li>
            <li><a href="#cotizacion" class="btn-login" style="border-radius: 8px;">Cotizar Pedido ➔</a></li>
        </ul>
    </nav>

    <section id="inicio" class="hero">
        <h1>El surtido más grande en <span>Regalos y Novedades</span></h1>
        <p>Somos distribuidores mayoristas directos. Desde peluches gigantes hasta envolturas y artículos de temporada. Abastecemos tu negocio con los mejores precios del centro del país.</p>
        <a href="#catalogos" class="btn-cta">Explorar Productos</a>
    </section>

    <section id="catalogos" class="section">
        <h2 class="section-title">Nuestras Categorías</h2>
        <p class="section-subtitle">Lo más vendido para potenciar tus ganancias</p>
        
        <div class="catalog-grid">
            <div class="catalog-card">
                <div class="catalog-icon">🧸</div>
                <h3>Peluches</h3>
                <p>Osos gigantes, personajes de moda, licencias y peluche de temporada.</p>
                <a href="#cotizacion" class="catalog-btn">Cotizar ahora</a>
            </div>
            <div class="catalog-card">
                <div class="catalog-icon">🛍️</div>
                <h3>Envolturas</h3>
                <p>Cajas musicales, bolsas de regalo holográficas, moños y papel.</p>
                <a href="#cotizacion" class="catalog-btn">Cotizar ahora</a>
            </div>
            <div class="catalog-card">
                <div class="catalog-icon">🎈</div>
                <h3>Fiesta y Decoración</h3>
                <p>Globos metálicos, velas chisperas, cortinas y adornos.</p>
                <a href="#cotizacion" class="catalog-btn">Cotizar ahora</a>
            </div>
            <div class="catalog-card">
                <div class="catalog-icon">⌚</div>
                <h3>Accesorios</h3>
                <p>Relojes, carteras, joyería de fantasía y detalles para toda ocasión.</p>
                <a href="#cotizacion" class="catalog-btn">Cotizar ahora</a>
            </div>
        </div>
    </section>

    <section id="ubicaciones" class="section" style="background: white; border-radius: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.03);">
        <h2 class="section-title">Nuestras Sucursales (CDMX)</h2>
        <p class="section-subtitle">Visita nuestro almacén central o nuestros puntos de venta en el corazón comercial.</p>

        <div class="map-main">
            <div class="map-info">
                <h3>👑 Almacén Matriz (Plaza Sonora)</h3>
                <p><strong>Ubicación:</strong> Fray Servando Teresa de Mier, Merced Balbuena, CDMX.</p>
                <p>Nuestro punto de distribución principal. Aquí podrás recoger pedidos de alto volumen, cargar camiones y acceder a precios exclusivos de distribuidor máster.</p>
            </div>
            <div class="map-iframe">
                <iframe src="https://maps.google.com/maps?q=Mercado+Sonora+CDMX&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
            </div>
        </div>

        <div class="map-grid">
            <div class="map-item">
                <h4>📍 Sucursal Centro Histórico</h4>
                <p>Calle República de Uruguay. Especialidad en joyería y accesorios.</p>
                <div class="map-mini">
                    <iframe src="https://maps.google.com/maps?q=Republica+de+Uruguay+Centro+Historico+CDMX&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
                </div>
            </div>
            <div class="map-item">
                <h4>📍 Sucursal La Merced</h4>
                <p>Nave Mayor. Especialidad en fiesta, globos y envolturas.</p>
                <div class="map-mini">
                    <iframe src="https://maps.google.com/maps?q=Mercado+de+la+Merced+CDMX&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
                </div>
            </div>
            <div class="map-item">
                <h4>📍 Sucursal Tepito</h4>
                <p>Eje 1 Norte. Especialidad en Novedades y Electrónica.</p>
                <div class="map-mini">
                    <iframe src="https://maps.google.com/maps?q=Tepito+Eje+1+Norte+CDMX&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
                </div>
            </div>
            <div class="map-item">
                <h4>📍 Sucursal Zaragoza</h4>
                <p>Calz. Ignacio Zaragoza. Entregas rápidas foráneas.</p>
                <div class="map-mini">
                    <iframe src="https://maps.google.com/maps?q=Calzada+Ignacio+Zaragoza+CDMX&t=&z=14&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section id="cotizacion" class="section">
        <h2 class="section-title">Cotización y Contacto</h2>
        <div class="contact-wrapper">
            <form class="contact-form" action="#" method="POST" onsubmit="alert('¡Mensaje enviado con éxito!'); return false;">
                <input type="text" class="input-field" placeholder="Tu Nombre o Empresa" required>
                <div style="display:flex; gap:20px; flex-wrap: wrap;">
                    <input type="email" class="input-field" placeholder="Correo Electrónico" style="flex:1;" required>
                    <input type="text" class="input-field" placeholder="Teléfono / WhatsApp" style="flex:1;" required>
                </div>
                <textarea class="input-field" placeholder="Escribe los detalles de tu pedido..." required></textarea>
                <button type="submit" class="btn-submit">Enviar Cotización 🚀</button>
            </form>
        </div>
    </section>

    <footer>
        <h2>🎁 JUCARI</h2>
        <p>Expertos en Novedades y Regalos de Mayoreo desde 2010.</p>
        <p>© 2026 Todos los derechos reservados.</p>
        <a href="login.php" style="color: #64748b; text-decoration: none; font-size: 12px; margin-top: 15px; display: inline-block;">Acceso Administrativo</a>
    </footer>
<script src="js/landing.js"></script>
</body>
</html>