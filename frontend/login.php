<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Iniciar Sesión · Tienda Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<body>
    <?php // Incluimos la barra de navegación que acabamos de crear.
          // Para esto, es buena idea mover el código del <nav> a un archivo separado (ej: 'template/nav.php')
          // y hacer include 'template/nav.php'; en todas las páginas. Por ahora lo dejamos así. ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top border-bottom">...</nav> <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h3 card-title text-center mb-4">Iniciar Sesión</h1>
                        
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="registro.php">¿No tienes una cuenta? Regístrate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="assets/js/app.js"></script>
</body>
</html>