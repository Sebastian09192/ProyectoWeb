<?php 
$page_title = 'Carrito · VSCR';
include 'template/header.php'; 
?>

<h1 class="h3">Tu Carrito</h1>
<div class="table-responsive mt-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="cartTableBody"></tbody>
    </table>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-6">
        <a href="products.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i> Seguir comprando</a>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="subtotal">₡0</strong></div>
                <div class="d-flex justify-content-between"><span>Impuesto (IVA)</span><strong id="tax">₡0</strong></div>
                <div class="d-flex justify-content-between"><span>Envío</span><strong id="shipping">₡0</strong></div>
                <hr />
                <div class="d-flex justify-content-between fs-5"><span>Total</span><strong id="total">₡0</strong></div>
                <a href="checkout.php" class="btn btn-primary w-100 mt-3" id="checkoutBtn"><i class="fa fa-lock me-1"></i> Proceder al pago</a>
            </div>
        </div>
    </div>
</div>

<?php 
include 'template/footer.php';
echo '<script type="module" src="assets/js/cartPage.js"></script>';
?>