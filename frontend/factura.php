<?php 
$page_title = 'Factura · Tienda Virtual';
include 'template/header.php'; 
?>

<div class="invoice border rounded-3 p-4 bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-start">
        <div><h1 class="h4 mb-0">Factura</h1><div class="text-muted">TiendaCR</div></div>
        <div class="text-end">
            <div><span class="text-muted">Fecha:</span> <strong id="invDate">-</strong></div>
            <div><span class="text-muted">Factura #</span> <strong id="invId">-</strong></div>
            <div><span class="text-muted">Pedido #</span> <strong id="invOrderId">-</strong></div>
            <div><span class="text-muted">Seguimiento</span> <strong id="invTracking">-</strong></div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <h2 class="h6">Facturar a</h2>
            <div id="billTo">Cargando...</div>
        </div>
        <div class="col-md-6 text-md-end">
            <h2 class="h6">Vendedor</h2>
            <div>TiendaCR S.A.</div>
            <div>San Carlos, Alajuela</div>
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-sm align-middle">
            <thead><tr><th>Producto</th><th class="text-center">Cantidad</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr></thead>
            <tbody id="invItems"></tbody>
        </table>
    </div>
    <div class="d-flex flex-column align-items-end">
        <div><span class="text-muted">Subtotal:</span> <strong id="invSubtotal">₡0</strong></div>
        <div><span class="text-muted">IVA (13%):</span> <strong id="invTax">₡0</strong></div>
        <div><span class="text-muted">Envío:</span> <strong id="invShipping">₡0</strong></div>
        <div class="fs-5"><span class="text-muted">Total:</span> <strong id="invTotal">₡0</strong></div>
    </div>
    <div class="mt-3 d-print-none">
        <button class="btn btn-outline-secondary" id="print-btn"><i class="fa fa-print me-1"></i> Imprimir</button>
        <button class="btn btn-secondary" id="download-pdf-btn"><i class="fa fa-file-pdf me-1"></i> Guardar PDF</button>
        <a class="btn btn-primary" href="index.php">Volver al inicio</a>
    </div>
</div>

<?php 
include 'template/footer.php';
echo '<script type="module" src="assets/js/factura.js"></script>';
?>