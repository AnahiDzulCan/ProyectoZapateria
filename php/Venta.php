<?php

/**
 * @author Pedro Jose Bacab Pech
 */


 /**
  * Clase de la venta
  */
class Venta {
    
    private $conn; // Conexión a la base de datos
    private $table_Venta = "Venta"; // Nombre de la tabla Venta
    private $table_detalleVenta = "detalleVenta"; // Nombre de la tabla detalleVenta

    // Atributos de la venta
    public $idVenta;    // Identificador único de la venta
    public $fecha;      // Fecha de la venta

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Método para relizar una venta con los datos obtenidos de la compra
     * @return bool Devuelve true si la compra fue exitosa, false en caso contrario.
     */
    public function crearVenta($idUsuario, $carrito, $total) {

        // Iniciar la transacción para asegurar la integridad de los datos
        $this->conn->begin_transaction();

        try{

            // Crear la fecha de la venta
            $fecha = date('Y-m-d H:i:s');

            $query = "INSERT INTO " . $this->table_Venta . " (idUsuario, Fecha, Total) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isi", $idUsuario, $fecha, $total);
            $stmt->execute();

            // Obtener el idVenta generado
            $idVenta = $stmt->insert_id; // Obtenemos el ID de la venta recién insertada

            // 2. Insertar los detalles de la venta en la tabla `detalleVenta`
            foreach ($carrito as $producto) {
                $subtotal = $producto['precio'] * $producto['cantidad'];
                
                $queryDetalle = "INSERT INTO " . $this->table_detalleVenta . " (idVenta, idProducto, Cantidad, Subtotal) VALUES (?, ?, ?, ?)";
                $stmtDetalle = $this->conn->prepare($queryDetalle);
                $stmtDetalle->bind_param("iiii", $idVenta, $producto['id'], $producto['cantidad'], $subtotal);
                $stmtDetalle->execute();
            }

            // Si todo sale bien, commit la transacción
            $this->conn->commit();

            return true; // Compra exitosa

        }catch(Exception $e){
            // Si ocurre un error, hacer rollback
            $this->conn->rollback();
            return false; // Error en la compra
        }
    }

    /**
     * Método para obtener ventas mensuales
     * @param int $anio Año para el que se desea calcular las ventas.
     * @param int $mes Mes para el que se desea calcular las ventas.
     * @return int Devuelve el total de las ventas del mes especificado, si no hay ventas retorna 0. 
     */
    public function obtenerVentasMensuales($anio, $mes) {
        $query = "SELECT SUM(Total) AS totalVentas FROM " . $this->table_Venta . " 
                  WHERE YEAR(Fecha) = ? AND MONTH(Fecha) = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $anio, $mes);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data['totalVentas'] ? $data['totalVentas'] : 0;
    }

    /**
     * Método para obtener ventas anuales
     * @param int $anio Año para el que se desea calcular las ventas.
     * @return int Devuelve el total de las ventas del mes especificado, si no hay ventas retorna 0. 
     */
    public function obtenerVentasAnuales($anio) {
        $query = "SELECT SUM(Total) AS totalVentas FROM " . $this->table_Venta . " 
                  WHERE YEAR(Fecha) = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $anio);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data['totalVentas'] ? $data['totalVentas'] : 0;
    }

}


?>