<?php
// Incluir el archivo de encabezado que ya contiene la sesión y los enlaces
include 'template/header.php';
?>

<div class="text-center mb-4">
    <i class="fa-regular fa-circle-check display-4 text-success"></i>
    <h1 class="h3 mt-2">¡Pedido confirmado!</h1>
    <p class="text-muted">Gracias por tu compra. Te enviamos un correo con los detalles.</p>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div><span class="text-muted">Número de pedido</span><div class="fw-bold" id="orderId">-</div></div>
                    </div>
                    <div class="col-md-6">
                        <div><span class="text-muted">Núm. de seguimiento</span><div class="fw-bold" id="tracking">-</div></div>
                    </div>
                    <div class="col-md-6">
                        <div><span class="text-muted">Fecha</span><div class="fw-bold" id="orderDate">-</div></div>
                    </div>
                    <div class="col-md-6">
                        <div><span class="text-muted">Total</span><div class="fw-bold" id="orderTotal">₡0</div></div>
                    </div>
                </div>
                <hr />
                <div id="itemsList" class="small"></div>
                <div class="d-flex gap-2 mt-3">
                    <a class="btn btn-outline-secondary" href="products.php">Seguir comprando</a>
                    <a class="btn btn-primary" id="invoiceBtn" href="#">Ver factura</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluir el archivo de pie de página
include 'template/footer.php';
echo '<script type="module" src="assets/js/confirmacion.js"></script>';
?>