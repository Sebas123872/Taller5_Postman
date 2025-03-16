<?php
require_once "database.php";

class Producto {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerProductos($nombre = null, $precio_mayor_que = null) {
        $sql = "SELECT * FROM productos WHERE 1=1";

        $params = array();
        $types = "";

        if ($nombre) {
            $sql .= " AND nombre LIKE ?";
            $params[] = $nombre . "%";
            $types .= "s";
        }

        if ($precio_mayor_que !== null) {
            $sql .= " AND precio > ?";
            $params[] = $precio_mayor_que;
            $types .= "d";
        }

        if (!empty($params)) {
            $stmt = $this->conn->prepare($sql);
            if ($types) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $resultado = $stmt->get_result();
        } else {
            $resultado = $this->conn->query($sql);
        }

        return $resultado;
    }

    public function crearProducto($nombre, $precio, $stock) {
        $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdi", $nombre, $precio, $stock);
        $stmt->execute();
    }

    public function actualizarProducto($id, $nombre, $precio, $stock) {
        $sql = "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdii", $nombre, $precio, $stock, $id); 
        $stmt->execute();
    }

    public function eliminarProducto($id) {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}


?>
