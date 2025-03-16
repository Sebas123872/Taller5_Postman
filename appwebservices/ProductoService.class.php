<?php
class ProductoService {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "webservice");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerProductos() {
        $sql = "SELECT * FROM productos";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function crearProducto($nombre, $precio, $stock) {
        $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sdi", $nombre, $precio, $stock);

        if ($stmt->execute()) {
            return "Producto '$nombre' agregado correctamente.";
        } else {
            return "Error al agregar el producto: " . $stmt->error;
        }
    }

    public function actualizarProducto($id, $nombre, $precio, $stock) {
        $sql = "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sdii", $nombre, $precio, $stock, $id);

        if ($stmt->execute()) {
            return "Producto actualizado correctamente.";
        } else {
            return "Error al actualizar el producto: " . $stmt->error;
        }
    }

    public function eliminarProducto($id) {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return "Producto eliminado correctamente.";
        } else {
            return "Error al eliminar el producto: " . $stmt->error;
        }
    }
}
?>
