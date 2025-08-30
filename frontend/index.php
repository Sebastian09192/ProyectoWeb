<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta https-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://cdn.jsdelivr.net https://cdn.jsdelivr.net/npm; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdn.jsdelivr.net/npm; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net https://cdn.jsdelivr.net/npm; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self'; upgrade-insecure-requests" />
  <meta name="referrer" content="no-referrer" />
  <meta name="description" content="Tienda Virtual - Proyecto Final ITI-523" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Inicio · Tienda Virtual</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<!-- Start of HubSpot Embed Code -->
  <script type="text/javascript" id="hs-script-loader" async defer src="//js-na1.hs-scripts.com/50431712.js"></script>
<!-- End of HubSpot Embed Code -->
<body>
<nav class="navbar navbar-expand-lg sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">VSCR</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="products.php">Productos</a></li>
            </ul>
            
            <a class="btn btn-outline-light position-relative me-2" href="cart.php">
                <i class="fa-solid fa-cart-shopping me-1"></i> Carrito
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">0</span>
            </a>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li id="login-link"><a class="dropdown-item" href="login.php">Iniciar Sesión</a></li>
                    <li id="register-link"><a class="dropdown-item" href="registro.php">Registrarse</a></li>
                    
                    <li id="profile-link" style="display: none;"><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
                    <li id="logout-link" style="display: none;"><hr class="dropdown-divider"></li>
                    <li id="logout-link-item" style="display: none;"><a class="dropdown-item" href="#">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<header class="py-5 hero-section">
  <div class="container text-white hero-content">
    <h1 class="display-4 fw-bold">¡Bienvenido a VSCR!</h1>
    <p class="lead mt-3">Explora nuestras categorías y llena tu carrito con productos increíbles.</p>
    <a href="products.php" class="btn btn-light btn-lg mt-4"><i class="fa fa-store me-1"></i> Ver productos</a>
  </div>
</header>

<section class="container my-5">
  <h2 class="h4 mb-4 text-center">Categorías destacadas</h2>
  <div class="row g-4 justify-content-center">
    <div class="col-6 col-md-3"><a class="card shadow-sm cat-card" href="products.php?cat=electronica"><div class="card-body text-center"><i class="fa-solid fa-plug fa-2x mb-2"></i><div>Electrónica</div></div></a></div>
    <div class="col-6 col-md-3"><a class="card shadow-sm cat-card" href="products.php?cat=ropa"><div class="card-body text-center"><i class="fa-solid fa-shirt fa-2x mb-2"></i><div>Ropa</div></div></a></div>
    <div class="col-6 col-md-3"><a class="card shadow-sm cat-card" href="products.php?cat=hogar"><div class="card-body text-center"><i class="fa-solid fa-couch fa-2x mb-2"></i><div>Hogar</div></div></a></div>
    <div class="col-6 col-md-3"><a class="card shadow-sm cat-card" href="products.php?cat=viajes"><div class="card-body text-center"><i class="fa-solid fa-plane fa-2x mb-2"></i><div>Viajes</div></div></a></div>
  </div>
</section>

<section class="bg-light py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-6 order-md-2">
        <h2 class="h4">Compra segura</h2>
        <p class="text-muted">Ofrecemos métodos de pago seguros y protección al comprador para que compres con confianza.</p>
        <h2 class="h4 mt-4">Envío rápido</h2>
        <p class="text-muted">Procesamos y enviamos tus pedidos rápidamente para que los recibas cuanto antes.</p>
        <h2 class="h4 mt-4">Atención al cliente</h2>
        <p class="text-muted">Nuestro equipo de soporte está disponible para ayudarte con cualquier consulta o problema.</p>
      </div>
      <div class="col-md-6 order-md-1">
        <div class="ratio ratio-16x9 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center">
          <div class="text-center text-muted"><i class="fa-solid fa-shield-halved fa-3x mb-3"></i><div class="h5">Tu seguridad es nuestra prioridad</div></div>
        </div>
      </div>
    </div>
  </div>
</section>
<footer class="border-top mt-5 py-4">
  <div class="container small d-flex flex-column flex-md-row justify-content-between">
    <div>© <span id="year"></span> VSCR — Proyecto Final ITI-523</div>
    <div>Hecho con Bootstrap 5.3 · <a href="../README.md" class="link-light">Docs</a></div>
  </div>
</footer>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="assets/js/app.js"></script>
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js-na1.hs-scripts.com/50431712.js"></script>
<!-- End of HubSpot Embed Code -->
</body>
</html>