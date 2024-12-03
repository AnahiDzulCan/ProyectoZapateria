<?php

/**
 * @author Pedro José Bacab Pech
 */


/**
 * Clase Producto que representa los datos y operaciones relacionadas con los productos.
 */
class Producto {
    private $conn;// Conexión a la base de datos
    private $table_name = "producto"; // Nombre de la tabla asociada

    // Atributos del producto
    public $idProducto;  // Identificador único del producto
    public $idCategoria; // Clave foránea que hace referencia a la tabla categoria
    public $idGenero;    // Clave foránea que hace referencia a la tabla genero
    public $nombre;      // Nombre del producto
    public $modelo;      // Modelo del producto
    public $descripcion; // Descripción del producto
    public $talla;       // Talla del producto
    public $precio;      // Precio del producto
    public $stock;       // Cantidad disponible en inventario

    /**
     * Constructor que recibe la conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Método para crear un nuevo producto
     * @param int $idCategoria Categoria del producto recibido del formulario.
     * @param int $idGenero Género del producto recibido del formulario.
     * @param String $nombre Nombre del producto recibido del formulario.
     * @param String $modelo Modelo del producto recibido del formulario.
     * @param String $descripcion Descripción del producto recibido del formulario.
     * @param int $talla Talla del producto recibido del formulario.
     * @param int $precio Precio del producto recibido del formulario.
     * @param int $stock Stock del producto recibido del formulario.
     * @return bool Devuelve true si la creación fue exitosa, false en caso contrario.
     */
    public function crearProducto($idCategoria, $idGenero, $nombre, $modelo, $descripcion, $talla, $precio, $stock) {
        $query = "INSERT INTO " . $this->table_name . " (idCategoria, idGenero, Nombre, Modelo, Descripcion, Talla, Precio, Stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conn->prepare($query)){
            //Enlazar los parametros
            $stmt->bind_param("iisssiii", $idCategoria, $idGenero, $nombre, $modelo, $descripcion, $talla, $precio, $stock);
            
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                // Manejo de errores
                error_log("Error al crear producto: " . $stmt->error);
            }
        }else{
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }

        return false;
    }

    /**
     * Método para actualizar la información de un Producto.
     * @param int $idCategoria Categoria del producto recibido del formulario.
     * @param int $idGenero Género del producto recibido del formulario.
     * @param String $nombre Nombre del producto recibido del formulario.
     * @param String $modelo Modelo del producto recibido del formulario.
     * @param String $descripcion Descripción del producto recibido del formulario.
     * @param int $talla Talla del producto recibido del formulario.
     * @param int $precio Precio del producto recibido del formulario.
     * @param int $stock Stock del producto recibido del formulario.
     * @param int $idProducto idProducto del producto.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    public function actualizarProducto($idCategoria, $idGenero, $nombre, $modelo, $descripcion, $talla, $precio, $stock, $idProducto) {
        $query = "UPDATE " . $this->table_name . " SET idCategoria = ?, idGenero = ?, Nombre = ?, Modelo = ?, Descripcion = ?, Talla=?, Precio = ?, Stock= ? WHERE idProducto = ?";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar los parámetros
            $stmt->bind_param("iisssiiii", $idCategoria, $idGenero, $nombre, $modelo, $descripcion, $talla, $precio, $stock, $idProducto);
 
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Error al actualizar producto: " . $stmt->error);
            }
        } else {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }

    /**
      * Método para eliminar un producto por su ID.
      * @param int $idProducto idProducto del producto.
      * @return bool Devuelve true si el usuario fue eliminado, false en caso contrario.
      */
      public function eliminarProducto($idProducto) {
        $query = "DELETE FROM " . $this->table_name . " WHERE idProducto = ?";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar el parámetro
            $stmt->bind_param("i", $idProducto);
 
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Error al eliminar producto: " . $stmt->error);
            }
        } else {
             error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }

    /**
     * Método para obtener todos los productos
     * @return bool Devuelve tel resultado si la obtención fue exitosa, false en caso contrario.
     */
    public function obtenerProductos(){

        $query = "SELECT p.idProducto, p.Nombre, p.Modelo, p.Descripcion, p.Talla, p.Precio, p.Stock, g.Nombre AS Genero
              FROM " . $this->table_name . " p
              JOIN genero g ON p.idGenero = g.idGenero"; 

        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->execute();
            return $stmt->get_result(); // Obtener el resultado de la consulta
        } else {
            error_log("Error al ejecutar la consulta: " . $this->conn->error);
            return false; // Si hay un error, retornamos false
        }

    }

    /**
     * Método para filtrar productos.
     *
     * @param int|null $idGenero     El ID del género.
     * @param int|null $talla        La talla del producto.
     * @param int|null $idCategoria  El ID de la categoría.
     *
     * @return mysqli_result|false Resultado de la consulta o false en caso de error.
     */
    public function obtenerProductosFiltrados($idGenero = null, $talla = null, $idCategoria = null){
        $query = "SELECT p.idProducto, p.Nombre, p.Modelo, p.Descripcion, p.Talla, p.Precio, p.Stock, g.Nombre AS Genero
              FROM " . $this->table_name . " p
              JOIN genero g ON p.idGenero = g.idGenero WHERE 1=1";
        $params = [];
        $types = "";

    // Agregar filtros a la consulta
    if (!empty($idGenero)) {
        $query .= " AND p.idGenero = ?";
        $params[] = $idGenero;
        $types .= "i";
    }

    // Agregar filtros a la consulta
    if (!empty($talla)) {
        $query .= " AND p.Talla = ?";
        $params[] = $talla;
        $types .= "i";
    }

    // Agregar filtros a la consulta
    if (!empty($idCategoria)) {
        $query .= " AND p.idCategoria = ?";
        $params[] = $idCategoria;
        $types .= "i";
    }

    //Preparar la consulta
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $this->conn->error);
        return false;
    }

    //Verificar si hay parámetros
    if(!empty($params)){
        $stmt->bind_param($types, ...$params);
    }

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        error_log("Error al ejecutar la consulta: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();

    return $result;
    }

    /**
     * Método que actualiza el stock del producto
     * @param int $idProducto identificador del producto
     * @param int $cantidad cantidad a comprar
     * @return false|execute retorna la ejecución de la consulta si hay stock, false en caso contrario
     */
    public function actualizarStock($idProducto, $cantidad){
        $query = "SELECT * FROM " . $this->table_name . " WHERE idProducto = ?";
        
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar el parámetro
            $stmt->bind_param("i", $idProducto);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $producto = $resultado->fetch_assoc();

            if (!$producto || $producto['Stock'] < $cantidad) {
                // No hay suficiente stock
                return false;
            }

            //Actualizar stock
            $nuevoStock = $producto['Stock'] - $cantidad;
            $query = "UPDATE " . $this->table_name . " SET Stock = ? WHERE idProducto = ?";

            if($stmt = $this->conn->prepare($query)){
                $stmt->bind_param("ii", $nuevoStock, $idProducto);

                return $stmt->execute();
            }else{
                error_log("Error en la preparación de la consulta: " . $this->conn->error);
            }
            
        } else {
             error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }

        return false;

    }

}


?>