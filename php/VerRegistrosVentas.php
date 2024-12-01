<?php

include_once 'Venta.php';
include_once 'Database.php';

// Crear instancia del controlador
$VerRegistrosVentas = new VerRegistrosVentas();

// Manejar la solicitud
$VerRegistrosVentas->manejarSolicitud();

class VerRegistrosVentas{

    private $database;
    private $venta;

     //Crear instancia de la base de datos para pasarle al usuario la conexión
     public function __construct(){
        $this->database = new Database();
        $conexion = $this->database->getConexionDB();
        $this->venta = new Venta($conexion);
    }

    /**
     * Método para consultar ventas mensuales
     * @param int $anio Año del cual se quiere saber el total.
     * @param int $mes Mes del cual se quiere saber el total.
     * @return int $totalVentas Total de las ventas mensual.
     */
    public function consultarMensual($anio,$mes){
        
        $totalVentas = $this->venta->obtenerVentasMensuales($anio, $mes);
        return $totalVentas;

    }

    /**
     * Método para consultar ventas anuales
     * @param int $anio Año del cual se quiere saber el total.
     * @return int $totalVentas Total de las ventas mensual.
     */
    public function consultarAnual($anio){
        
        $totalVentas = $this->venta->obtenerVentasAnuales($anio);
        return $totalVentas;

    }

    /**
     * Método que se encarga de manejar la solicitud, es decir, registro, editar, eliminar
     */
    public function manejarSolicitud(){


        // Verificar si se enviaron los datos del formulario
      if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $solicitud = [
            'accion' => $_POST['accion']
        ];

        //Verificar la acción
        if($solicitud['accion'] == 'mensual'){

            $anio = htmlspecialchars($_POST['anio']);
            $mes = htmlspecialchars($_POST['mes']);

            $totalVentas = $this->consultarMensual($anio, $mes);

            if ($totalVentas !== null) {
                // Mostrar el total de ventas en el alert
                echo "
                <script>
                alert('Ventas de $mes/$anio: $' + $totalVentas);
                window.location.href = '../ventasMensuales.html';
                </script>";
            } else {
                echo "
                <script>
                alert('No se encontraron ventas para el mes $mes/$anio');
                window.location.href = '../ventasMensuales.html';
                </script>";
            }

        }else if($solicitud['accion'] == 'anual'){

            $anio = htmlspecialchars($_POST['anio']);

            $totalVentas = $this->consultarAnual($anio);
                
            if ($totalVentas !== null) {
                // Mostrar el total de ventas en el alert
                echo "
                <script>
                alert('Ventas del año $anio: $' + $totalVentas);
                window.location.href = '../ventasAnuales.html';
                </script>";
            } else {
                echo "
                <script>
                alert('No se encontraron ventas para el año $anio');
                window.location.href = '../ventasAnuales.html';
                </script>";
            }

        }
    
      }
    }

}

?>