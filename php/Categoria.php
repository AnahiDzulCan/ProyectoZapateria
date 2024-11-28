<?php

/**
 * @author Pedro José Bacab Pech
 */

class Categoria {
   
    private $conn; // Conexión a la base de datos
    private $table_name = "Categoria";// Nombre de la tabla asociada

    // Atributos de la categoría
    public $idCategoria; // Identificador único de la categoría
    public $nombre;      // Nombre de la categoría

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para crear una nueva categoría
    public function crearCategoria() {
        $query = "INSERT INTO " . $this->table_name . " (Nombre) VALUES (?)";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $this->nombre);
            return $stmt->execute();
        }
        return false;
    }

    // Método para obtener todas las categorías disponibles
    public function obtenerCategorias() {
        $query = "SELECT * FROM " . $this->table_name;
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();

            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
            return $categories;
        }
        return false;
    }
}


?>