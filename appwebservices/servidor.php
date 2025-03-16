<?php
require_once "vendor/econea/nusoap/src/nusoap.php";
require_once "database.php";

$db = new Database();
$conn = $db->getConnection();

$namespace = "urn:servicioproveedores";
$server = new nusoap_server();
$server->configureWSDL("ServicioProductos", $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Registro de métodos
$server->register(
    "agregarProducto",
    ["nombre" => "xsd:string", "precio" => "xsd:float", "stock" => "xsd:int", "token" => "xsd:string"],
    ["return" => "xsd:string"],
    $namespace,
    false,
    "rpc",
    "encoded",
    "Agrega un nuevo producto"
);

$server->register(
    "actualizarProducto",
    ["id" => "xsd:int", "nombre" => "xsd:string", "precio" => "xsd:float", "stock" => "xsd:int", "token" => "xsd:string"],
    ["return" => "xsd:string"],
    $namespace,
    false,
    "rpc",
    "encoded",
    "Actualiza un producto existente"
);

$server->register(
    "eliminarProducto",
    ["id" => "xsd:int", "token" => "xsd:string"],
    ["return" => "xsd:string"],
    $namespace,
    false,
    "rpc",
    "encoded",
    "Elimina un producto"
);

$server->register(
    "obtenerProductos",
    ["nombre" => "xsd:string", "token" => "xsd:string"],
    ["return" => "tns:ArrayOfProducto"],
    $namespace,
    false,
    "rpc",
    "encoded",
    "Obtiene productos"
);

// Función para validar token
function validarToken($token) {
    $token_valido = "token_secreto"; 
    return ($token === $token_valido);
}

// Implementación de obtenerProductos (CORREGIDA)
function obtenerProductos($nombre = "", $token) {
    if (!validarToken($token)) {
        return [];
    }

    global $conn;

    $sql = "SELECT id, nombre, CAST(precio AS DECIMAL(10,2)) AS precio, stock FROM productos";
    $params = [];

    if (!empty($nombre)) {
        $sql .= " WHERE nombre LIKE ?";
        $params[] = "%$nombre%";
    }

    try {
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param("s", $params[0]);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // ✅ SOLUCIÓN
    } catch (Exception $e) {
        error_log("Error en obtenerProductos: " . $e->getMessage());
        return [];
    }
}


// Ejecutar servicio (SE ELIMINÓ EL CÓDIGO EXTRA)
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
?>


