<?php
$page_title = 'Administración · Tienda Virtual';
include 'template/header.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Administración de productos</h1>
        <button class="btn btn-primary" id="btnNew"><i class="fa fa-plus me-1"></i> Nuevo producto</button>
    </div>

    <div class="alert alert-info small" id="modeNote">
        Modo: <strong id="modeLabel">Demo local</strong>. Puedes cambiar a API real en <code>assets/js/config.js</code> → <code>apiEnabled:true</code>.
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th class="text-end">Precio</th>
                    <th>Imagen</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody id="prodTableBody"></tbody>
        </table>
    </div>
</main>

<div class="modal fade" id="prodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="prodForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">ID</label>
                            <input class="form-control" id="f_id" required />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" id="f_name" required maxlength="120" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="f_desc" rows="2" maxlength="400"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio (₡)</label>
                            <input type="number" class="form-control" id="f_price" min="0" step="100" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" id="f_cat" required>
                                <option value="electronica">Electrónica</option>
                                <option value="ropa">Ropa</option>
                                <option value="hogar">Hogar</option>
                                <option value="viajes">Viajes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">URL de imagen</label>
                            <input class="form-control" id="f_img" placeholder="assets/img/mi-foto.jpg" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit" id="btnSave">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'template/footer.php';
echo '<script src="assets/js/adminProducts.bundle.js?v=1"></script>';
?>