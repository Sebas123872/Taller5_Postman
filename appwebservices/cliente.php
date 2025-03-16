<?php
require_once "vendor/econea/nusoap/src/nusoap.php";

$wsdl = "http://localhost/webservices/appwebservices/productoservice.php?wsdl";
$client = new nusoap_client($wsdl, 'wsdl');
$error = $client->getError();

if ($error) {
    die("Error en la conexión SOAP: " . $error);
}

$productos = $client->call("obtenerProductos", ['nombre' => '']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        $params = [
            'nombre' => $_POST['nombre'],
            'precio' => $_POST['precio'],
            'stock' => $_POST['stock']
        ];
        $mensaje = $client->call("crearProducto", $params);
        echo '<script>alert("' . $mensaje . '");</script>';
    } elseif (isset($_POST['buscar'])) {
        $productos = $client->call("obtenerProductos", ['nombre' => $_POST['nombreBuscar']]);
        if (empty($productos)) {
            echo '<div class="alert alert-warning mt-3 text-center">No se encontraron productos.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Gestionar Productos</h2>

        <form method="post" class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre del producto</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del producto" required>
            </div>
            <div class="col-md-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" placeholder="Precio" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" placeholder="Stock" required>
            </div>
            <div class="col-12 text-center">
                <button type="submit" name="crear" class="btn btn-primary">Crear Producto</button>
            </div>
        </form>

        <form method="post" class="mt-3">
            <h2 class="text-center">Buscar Productos</h2>
            <div class="input-group">
                <input type="text" name="nombreBuscar" class="form-control" placeholder="Buscar producto por nombre">
                <button type="submit" name="buscar" class="btn btn-secondary">Buscar</button>
            </div>
        </form>

        <h2 class="text-center mt-5">Lista de Productos</h2>
        <table class="table table-striped mt-3">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>
            <?php if (!empty($productos) && is_array($productos)) {
                foreach($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['id']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                    </tr>
                <?php endforeach; 
            } else { ?>
                <tr><td colspan="4" class="text-center">No hay productos disponibles</td></tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
