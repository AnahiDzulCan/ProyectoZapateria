<?php

include_once 'Venta.php';
include_once 'Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controlCarrito = new Carrito();
$controlCarrito->manejadorAccionesCarrito();

class Carrito {

    /**
     * @var array|mixed
     */
    private $carrito;
    private $database;
    private $venta;

    public function __construct() {
        $this->database = new Database();
        $conexion = $this->database->getConexionDB();
        $this->venta = new Venta($conexion);

        // Inicializar el carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $this-> carrito = &$_SESSION['carrito']; // Referencia al carrito en la sesión
    }

    /**
     * Función para obtener el carrito
     *
     * @return array|mixed
     */
    public function obtenerCarrito()
    {
        return $this->carrito;
    }


// Manejo de acciones
function manejadorAccionesCarrito() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $accion = $_POST['accion'];
       

        switch ($accion) {
            case 'agregar':

                $producto = [
                    'id' => htmlspecialchars($_POST['idProducto']),
                    'nombre' => htmlspecialchars($_POST['nombre']),
                    'precio' => htmlspecialchars($_POST['precio']),
                    'modelo' => htmlspecialchars($_POST['modelo']),
                ];

                $idProducto = $producto['id'];
                $indice = array_search($idProducto, array_column($this->carrito, 'id'));

                if ($indice !== false) {
                    // Incrementa la cantidad si el producto ya está en el carrito
                    $this->carrito[$indice]['cantidad']++;
                } else {
                    // Agrega un nuevo producto al carrito
                    $producto['cantidad'] = 1; // Añade cantidad al producto
                    $this->carrito[] = $producto;
                }
                echo "
                <script>
                alert('¡Producto agregado!'); 
                window.location.href = '../productos.php'               
                </script>";
                break;

            case 'eliminar':

                $producto = [
                    'id' => htmlspecialchars($_POST['idProducto']),
                    'nombre' => htmlspecialchars($_POST['nombre']),
                    'precio' => htmlspecialchars($_POST['precio']),
                    'modelo' => htmlspecialchars($_POST['modelo']),
                ];

                $idProducto = (int) htmlspecialchars($_POST['idProducto']);
                // Elimina el producto por id
                $this->carrito = array_filter($this->carrito, function($producto) use ($idProducto) {
                    return (int) $producto['id'] !== $idProducto;
                });

                // Reindexar el array
                $this->carrito = array_values($this->carrito);
                break;

            case 'comprar':
                 // Procesa la compra
                 $idUsuario = $_SESSION['idUsuario']; // ID del usuario  en la sesión
                 $carritoJson = $_POST['carrito']; // Obtenemos el carrito 
                 $total = intval($_POST['total']); // Obtenemos el total

                 // Decodificar el JSON recibido en un array o objeto
                 $carrito = json_decode($carritoJson, true); // true convierte a un array, false lo mantiene como objeto

                 // Llamar a la función para realizar la compra
                 $exito = $this->venta->crearVenta($idUsuario, $carrito, $total);
                 
                 if ($exito) {
                    echo "<script>alert('¡Compra realizada con éxito!');</script>";
                    $_SESSION['carrito'] = []; // Vaciar el carrito después de la compra
                    } else {
                        echo "<script>alert('Hubo un error al realizar la compra. Intenta de nuevo.');</script>";
                    }
                    break;
        }

        // Guarda el carrito actualizado en la sesión
        $_SESSION['carrito'] = $this->carrito;

        // Muestra el carrito actualizado en la consola
        $carritoJson = json_encode($this->carrito);
        echo "
            <script>
                var carrito = $carritoJson;
                console.log('Contenido del carrito:', carrito);
            </script>";
    }
}

// Función para mostrar productos
function mostrarCarrito($carrito) {
    if (empty($carrito)) {
        echo "<p>Tu carrito está vacío.</p>";
        return;
    }

    foreach ($carrito as $producto) {
        $id = htmlspecialchars($producto['id']);
        $nombre = htmlspecialchars($producto['nombre']);
        $precio = htmlspecialchars($producto['precio']);
        $cantidad = htmlspecialchars($producto['cantidad']);
        $modelo = htmlspecialchars($producto['modelo']);
        
        echo "
        <div class='card mb-3'>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <div class='d-flex flex-row align-items-center'>
                        <div>
                            <img
                            src='assets/modelos/{$modelo}.png'
                            class='img-fluid rounded-3' alt='{$nombre}' style='width: 65px;'>
                        </div>
                            <div class='ms-3'>
                                        <h5>{$nombre}</h5>
                                        <p class='small mb-0'>Modelo: {$modelo} ID:{$id}</p>
                            </div>
                    </div>
                        <div class='d-flex flex-row align-items-center'>
                            <div style='width: 50px;''>
                                <h5 class='fw-normal mb-0'>Cantidad: {$cantidad}</h5>
                            </div>
                            <div style='width: 80px;'>
                                <h5 class='mb-0'>\$ {$precio}</h5>
                            </div>
                        <a href='#!'><i class='bi bi-trash3 p-3'></i></a>
                           <form method='POST' class='d-inline'>
                            <input type='hidden' name='idProducto' value='{$id}'>
                            <input type='hidden' name='nombre' value='{$nombre}'>
                            <input type='hidden' name='precio' value='{$precio}'>
                            <input type='hidden' name='modelo' value='{$modelo}'>
                           <button type='submit' class='btn btn-danger btn-sm' name='accion' value='eliminar'>Eliminar</button>
                           </form>
                    </div>
                </div>
            </div>
        </div>
        ";

    }
}

}

?>
