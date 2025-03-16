<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "vendor/econea/nusoap/src/nusoap.php";
require_once "ProductoService.class.php";

$server = new soap_server();
$server->configureWSDL("ProductoService", "urn:productoservice");

// Instancia del servicio
$productoService = new ProductoService();

// Registrar métodos correctamente para que SOAP envíe los parámetros
$server->register(
    "agregarProducto",
    ['nombre' => 'xsd:string', 'precio' => 'xsd:float', 'stock' => 'xsd:int'],
    ['return' => 'xsd:string'],
    "urn:productoservice",
    "urn:productoservice#agregarProducto",
    "rpc",
    "encoded",
    "Añadir un nuevo producto"
);

$server->register(
    "actualizarProducto",
    ['id' => 'xsd:int', 'nombre' => 'xsd:string', 'precio' => 'xsd:float', 'stock' => 'xsd:int'],
    ['return' => 'xsd:string'],
    "urn:productoservice",
    "urn:productoservice#actualizarProducto",
    "rpc",
    "encoded",
    "Actualizar un producto existente"
);

$server->register(
    "eliminarProducto",
    ['id' => 'xsd:int'],
    ['return' => 'xsd:string'],
    "urn:productoservice",
    "urn:productoservice#eliminarProducto",
    "rpc",
    "encoded",
    "Eliminar un producto por ID"
);

$server->register(
    "obtenerProductos",
    [],
    ['return' => 'xsd:Array'],
    "urn:productoservice",
    "urn:productoservice#obtenerProductos",
    "rpc",
    "encoded",
    "Obtener todos los productos"
);

// Funciones que recibirán los parámetros correctamente
function agregarProducto($nombre, $precio, $stock) {
    global $productoService;
    return $productoService->crearProducto($nombre, $precio, $stock);
}

function actualizarProducto($id, $nombre, $precio, $stock) {
    global $productoService;
    return $productoService->actualizarProducto($id, $nombre, $precio, $stock);
}

function eliminarProducto($id) {
    global $productoService;
    return $productoService->eliminarProducto($id);
}

function obtenerProductos() {
    global $productoService;
    return $productoService->obtenerProductos();
}

// Capturar la solicitud SOAP
$HTTP_RAW_POST_DATA = file_get_contents("php://input");
$server->service($HTTP_RAW_POST_DATA);
?>
