<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Mi Perfil";
include_once __DIR__ . '/template/header.php'; // ruta absoluta basada en el directorio actual
?>

<main class="container my-4" id="main-content">
    </main>

<script>
// Función para cargar perfil y pedidos
function cargarPerfil() {
    const main = document.getElementById("main-content");
    main.innerHTML = `
        <h1>Mi Perfil</h1>
        <form id="formPerfil">
            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-control">
            </div>
            <div class="mb-3">
                <label>Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
        </form>

        <h2 class="mt-4">Historial de Pedidos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tracking</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody id="historialPedidos">
                <tr><td colspan="5">Cargando...</td></tr>
            </tbody>
        </table>
    `;

    // --- Previsualizar datos del usuario ---
    fetch("../backend/api/usuarios/obtener.php")
        .then(res => res.json())
        .then(data => {
            if (data.mensaje) {
                alert(data.mensaje);
                return;
            }
            document.getElementById("nombre").value = data.nombre;
            document.getElementById("email").value = data.email;
            document.getElementById("direccion").value = data.direccion;
            document.getElementById("telefono").value = data.telefono;
        })
        .catch(() => alert("No se pudo cargar los datos del usuario."));

    // --- Actualizar perfil ---
    document.getElementById("formPerfil").addEventListener("submit", (e) => {
        e.preventDefault();
        const payload = Object.fromEntries(new FormData(e.target).entries());

        fetch('../backend/api/usuarios/actualizar.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            alert(data.mensaje);
        })
        .catch(() => alert("Error al actualizar el perfil."));
    });

    // --- Cargar historial de pedidos ---
    fetch("../backend/api/pedidos/obtenerPorUsuario.php")
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("historialPedidos");
            tbody.innerHTML = "";
            if (data.length === 0){
                tbody.innerHTML = "<tr><td colspan='5'>No hay pedidos</td></tr>";
            } else {
                data.forEach(pedido => {
                    let row = `<tr>
                        <td>${pedido.tracking}</td>
                        <td>${pedido.date}</td>
                        <td>$${pedido.total.toFixed(2)}</td>
                        <td>${pedido.estado}</td>
                        <td>${pedido.items.map(i => `${i.qty}x ${i.name}`).join("<br>")}</td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            }
        })
        .catch(() => {
            document.getElementById("historialPedidos").innerHTML = "<tr><td colspan='5'>No se pudieron cargar los pedidos</td></tr>";
        });
}

// Ejecutar al cargar la página
cargarPerfil();
</script>
<?php
include_once __DIR__ . '/template/footer.php';
?>