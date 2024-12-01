<?php

include_once 'Database.php';
include_once 'Usuario.php';

// UsuarioController.php

// Crear instancia del controlador
$usuarioController = new UsuarioController();

// Manejar la solicitud
$usuarioController->manejarSolicitud();

class UsuarioController{

    private $database;
    private $usuario;
    

    //Crear instancia de la base de datos para pasarle al usuario la conexión
    public function __construct(){
        $this->database = new Database();
        $conexion = $this->database->getConexionDB();
        $this->usuario = new Usuario($conexion);
    }

    /**
     * Método que hace el llamado para registrar un usuario
     * @param array $datos que contiene los datos del usuario.
     * @return bool true si el registro fue exitoso, false en caso contrario.
     */
    public function registrarUsuario($datos){

        if(!($this->usuario->verificarCorreo($datos['email']))){

            $idRol=0;
            if($this->usuario->crearUsuario($datos['nombre'], $datos['apellido'], $datos['email'], $datos['password'], $datos['idgenero'], $idRol)){
                return true;
            }else{
                return false;
            }

        }else{
            false;
        }
    }

    /**
     * Método que hace el llamado para editar un usuario
     * @param array $datos que contiene los datos del usuario.
     * @return bool true si la actualización fue exitosa, false en caso contrario.
     */
    public function editarUsuario($datos){
        if($this->usuario->actualizarUsuario($datos['nombre'], $datos['apellido'], $datos['password'], $datos['idgenero'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Método que hace el llamado para eliminar un usuario
     * @param int $idUsuario idUsuario del usuario.
     * @return String "Usuario eliminado correctamente." si la eliminación fue exitosa, "Error al eliminar el usuario." en caso contrario.
     */
    public function eliminarUsuario($idUsuario){
        if($this->usuario->eliminarUsuario($idUsuario)){
            return "Usuario eliminado correctamente.";
        }else{
            return "Error al eliminar el usuario.";
        }
    }

    /**
     * Método para listar los usuarios
     * @return array lista de usuarios.
     */
    public function listarUsuarios(){
        return $this->usuario->obtenerUsuarios();
    }
    
     /**
     * Método que hace el llamado para iniciar sesión
     * @return bool Verdadero si es exitoso, falso si la contraseña o correo son incorrectos
     */
    public function iniciarSesion($email, $password){

        if($this->usuario->iniciarSesion($email, $password)){
            return true;
        }else{
            return false;
        }    
    
    }

    /**
     * Método para cerrar sesión
     */
    public function cerrarSesion(){
        $this->usuario->cerrarSesion();
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

        //Verificar la acción (registrar o editar)
        if($solicitud['accion'] == 'registrar'){


        $datos = [
            'nombre' => htmlspecialchars($_POST['nombre']),
            'apellido' => htmlspecialchars($_POST['apellido']),
            'email' => htmlspecialchars($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT), // Encriptar la contraseña
            'idgenero' => htmlspecialchars($_POST['idgenero']),
        ];

            if($this->registrarUsuario($datos)){
                //Avisar
                echo "
                <script>
                alert('Usuario Registrado'); 
                window.location.href = '../index.html'               
                </script>";
                
            }else{
                //Mostrar mensaje de error
                echo "<script>
                alert('Algo salió mal, inténtelo de nuevo.');
                window.location.href = '../index.html';
                </script>";
            }

        }else if($solicitud['accion'] == 'editar'){

            $datos = [
                'nombre' => htmlspecialchars($_POST['nombre']),
                'apellido' => htmlspecialchars($_POST['apellido']),
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT), // Encriptar la contraseña
                'idgenero' => htmlspecialchars($_POST['idgenero']),
            ];

           if( $this->editarUsuario($datos)){
            //Mandar al perfil
            header("Location: ../usuario.php");
            exit;
           }else{
            //Mostrar mensaje de error
            echo "<script>
            alert('Ocurrió un error al modificar');
            window.location.href = '../InicioSesion.html';
            </script>";
           }

        }else if($solicitud['accion'] == 'eliminar'){

            $idUsuario = $_POST['idUsuario'];
            $this->eliminarUsuario($idUsuario);

        }else if($solicitud['accion'] == 'ingresar'){

            $email = $_POST['email'];
            $password = $_POST['password'];
            
            //Llamar al método
            if($this->iniciarSesion($email,$password)){
                //Mandar al home
                header("Location: ../home.html");
                exit;
            }else{
                //Mostrar mensaje de error
                echo "<script>
                alert('Correo o contraseña incorrectos');
                window.location.href = '../InicioSesion.html';
                </script>";
            }

        }else if($solicitud['accion'] == 'cerrarSesion'){
            
            $this->cerrarSesion();

        }
        

        }
    }

}

?>
