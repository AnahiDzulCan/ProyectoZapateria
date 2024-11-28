<?php

/**
 * @author Pedro Jose Bacab Pech
 */

class Venta {
    
    private $conn; // Conexión a la base de datos
    private $table_name = "Venta"; // Nombre de la tabla asociada

    // Atributos de la venta
    public $idVenta;    // Identificador único de la venta
    public $fecha;      // Fecha de la venta
    public $idProducto; // Identificador del producto vendido
    public $cantidad;   // Cantidad de productos vendidos
    public $total;      // Total de la venta

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar una nueva venta
    public function crearVenta() {
        $query = "INSERT INTO " . $this->table_name . " (Fecha, IdProducto, Cantidad, Total) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("siid", $this->fecha, $this->idProducto, $this->cantidad, $this->total);
            return $stmt->execute();
        }
        return false;
    }
}


?>