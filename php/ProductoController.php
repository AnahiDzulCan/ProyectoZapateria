<?php

include_once 'Database.php';
include_once 'Producto.php';

// ProductoController.php

// Crear instancia del controlador
$productoController = new ProductoController();

// Manejar la solicitud
$productoController->manejarSolicitud();

class ProductoController{

    private $database;
    private $producto;
    

    //Crear instancia de la base de datos para pasarle al producto la conexión 
    public function __construct(){
        $this->database = new Database();
        $conexion = $this->database->getConexionDB();
        $this->producto = new Producto($conexion);
    }

    /**
     * Método que hace el llamado para crear un producto
     * @param array $datos que contiene los datos del producto.
     * @return bool true si la actualización fue exitosa, false en caso contrario.
     */
    public function crearProducto($datos){
        if($this->producto->crearProducto($datos['idCategoria'], $datos['idGenero'], $datos['Nombre'], $datos['Modelo'], $datos['Descripcion'], $datos['Talla'], $datos['Precio'], $datos['Stock'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param array $datos que contiene los datos del producto.
     * @param int $idProducto idProcuto del producto.
     * @return bool true si la actualización fue exitosa, false en caso contrario.
     * Método que hace el llamado para editar un Producto
     */
    public function editarProducto($idProducto,$datos){
        if($this->producto->actualizarProducto($datos['idCategoria'], $datos['idGenero'], $datos['Nombre'], $datos['Modelo'], $datos['Descripcion'], $datos['Talla'], $datos['Precio'], $datos['Stock'], $idProducto)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Método que hace el llamado para eliminar un Producto
     * @param int $idProducto idProcuto del producto.
     * @return bool true si la eliminación fue exitosa, false en caso contrario.
     */
    public function eliminarProducto($idProducto){
        if($this->producto->eliminarProducto($idProducto)){
            return true;
        }else{
            false;
        }
    }

    /**
     * Método para obtener los productos
     * @param array retorna la lista de productos.
     */
    public function listarProductos(){
        return $this->producto->obtenerProductos();
    }

    /**
     * Método que se encarga de manejar la solicitud, es decir, crear o editar
     */
    public function manejarSolicitud(){


        // Verificar si se enviaron los datos del formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $solicitud = [
            'accion' => $_POST['accion']
        ];

       


        //Verificar la acción (crear o editar)
        if($solicitud['accion'] == 'crear'){


        $datos = [
            'idCategoria' => htmlspecialchars($_POST['idCategoria']),
            'idGenero' => htmlspecialchars($_POST['idgenero']),
            'Nombre' => htmlspecialchars($_POST['Nombre']),
            'Modelo' => htmlspecialchars($_POST['Modelo']),
            'Descripcion' => htmlspecialchars($_POST['Descripcion']),
            'Talla' => htmlspecialchars($_POST['Talla']),
            'Precio' => htmlspecialchars($_POST['Precio']),
            'Stock' => htmlspecialchars($_POST['Stock']),
        ];

            if($this->crearProducto($datos)){
              //Avisar
            echo " 
            <script>
            alert('Producto Creado.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";  
            }else{
            echo "
            <script>
            alert('Algo salió mal.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";
            }

        }else if($solicitud['accion'] == 'editar'){

            $idProducto = htmlspecialchars($_POST['idProducto']);

            $datos = [
                'idCategoria' => htmlspecialchars($_POST['idCategoria']),
                'idGenero' => htmlspecialchars($_POST['idgenero']),
                'Nombre' => htmlspecialchars($_POST['Nombre']),
                'Modelo' => htmlspecialchars($_POST['Modelo']),
                'Descripcion' => htmlspecialchars($_POST['Descripcion']),
                'Talla' => htmlspecialchars($_POST['Talla']),
                'Precio' => htmlspecialchars($_POST['Precio']),
                'Stock' => htmlspecialchars($_POST['Stock']),
            ];

        if($this->editarProducto($idProducto, $datos)){
            //Avisar
            echo " 
            <script>
            alert('Producto Modificado.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";
        }else{
            echo "
            <script>
            alert('Algo salió mal.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";
        }

        }else if($solicitud['accion'] == 'eliminar'){

            $idProducto = $_POST['idProducto'];
            if($this->eliminarProducto($idProducto)){
                //Avisar
            echo " 
            <script>
            alert('Producto Eliminado.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";
            }else{
            echo "
            <script>
            alert('Algo salió mal.'); 
            window.location.href = '../productosAdmin.php'               
            </script>";
            }

        }
        

        }
    }

}

?>