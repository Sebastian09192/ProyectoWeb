<?php 
$page_title = 'Productos · Tienda Virtual';
include 'template/header.php'; 
?>

<h1 class="h3">Catálogo de Productos</h1>
<div class="row g-3 mt-2">
    <aside class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3"><label class="form-label">Buscar</label><input type="text" id="search" class="form-control" placeholder="Nombre del producto" /></div>
                <div class="mb-3"><label class="form-label">Categoría</label><select id="category" class="form-select"><option value="">Todas</option><option value="electronica">Electrónica</option><option value="ropa">Ropa</option><option value="hogar">Hogar</option><option value="viajes">Viajes</option></select></div>
                <div class="mb-3"><label class="form-label">Precio máximo</label><input type="number" id="priceMax" class="form-control" min="0" step="1000" placeholder="₡" /></div>
                <button id="clearFilters" class="btn btn-outline-secondary w-100">Limpiar filtros</button>
            </div>
        </div>
    </aside>
    <section class="col-lg-9">
        <div id="resultsInfo" class="small text-muted mb-2"></div>
        <div id="productsGrid" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4"></div>
    </section>
</div>

<?php 
include 'template/footer.php'; 
echo '<script type="module" src="assets/js/filters.js"></script>';
?>