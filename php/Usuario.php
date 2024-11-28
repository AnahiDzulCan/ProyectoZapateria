<?php

/**
 * @author Pedro José Bacab Pech
 */


/**
 * Clase Usuario que representa los datos y operaciones relacionadas con los usuarios.
 */
class Usuario {
    private $conn; // Conexión con la base de datos
    private $table_name = "Usuarios"; // Nombre de la tabla asociada
 
    // Atributos de la clase
    public $idUsuario; // Identificador único del usuario
    public $nombre; // Nombre del usuario
    public $apellido; // Apellido del usuario
    public $email; // Correo electrónico del usuario
    public $password; // Contraseña encriptada del usuario
    public $idGenero; // Género del usuario
    public $idRol; // Rol del usuario (referencia a la tabla Rol)
 
    /**
    * Constructor que recibe la conexión a la base de datos.
    */
    public function __construct($db) {
        $this->conn = $db;
    }
 
    /**
     * Método para crear un nuevo usuario.
     * @return bool Devuelve true si el usuario se creó correctamente, false en caso contrario.
     */
    public function crearUsuario($nombre, $apellido, $email, $password, $idGenero, $idRol) {
        $query = "INSERT INTO " . $this->table_name . " (Nombre, Apellido, Email, Password, idGenero, idRol) VALUES (?, ?, ?, ?, ?, ?)";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar los parámetros
            $stmt->bind_param("ssssii", $nombre, $apellido, $email, $password, $idGenero, $idRol);
 
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                // Manejo de errores
                error_log("Error al crear usuario: " . $stmt->error);
            }
        } else {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }
 
    /**
     * Método para actualizar la información de un usuario.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    public function actualizarUsuario($nombre, $apellido, $password, $idGenero) {

        session_start(); 

        if (!isset($_SESSION['idUsuario'])) {
            header("Location: ../InicioSesion.html");
            exit;
        }

        $idUsuario = $_SESSION['idUsuario'];

        $query = "UPDATE " . $this->table_name . " SET Nombre = ?, Apellido = ?, Password = ?, idGenero = ? WHERE idUsuario = ?";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar los parámetros
            $stmt->bind_param("sssii", $nombre, $apellido, $password, $idGenero, $idUsuario);
 
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Error al actualizar usuario: " . $stmt->error);
            }
        } else {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }
 
    /**
      * Método para eliminar un usuario por su ID.
      * @return bool Devuelve true si el usuario fue eliminado, false en caso contrario.
      */
    public function eliminarUsuario($idUsuario) {
        $query = "DELETE FROM " . $this->table_name . " WHERE idUsuario = ?";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar el parámetro
            $stmt->bind_param("i", $idUsuario);
 
            // Ejecutar la consulta y devolver el resultado
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Error al eliminar usuario: " . $stmt->error);
            }
        } else {
             error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }

    /**
    * Método para obtener un usuario por su correo electrónico.
    * @return bool Devuelve true si el usuario fue encontrado, false en caso contrario.
    */
    public function obtenerPorEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Email = ?";
 
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar el parámetro
            $stmt->bind_param("s", $this->email);
 
            // Ejecutar la consulta
            $stmt->execute();
 
            // Obtener el resultado
            $result = $stmt->get_result();
 
            if ($row = $result->fetch_assoc()) {
                // Asignar los valores a los atributos de la clase
                $this->idUsuario = $row['idUsuario'];
                $this->nombre = $row['Nombre'];
                $this->apellido = $row['Apellido'];
                $this->email = $row['Email'];
                $this->password = $row['Password'];
                $this->genero = $row['idGenero'];
                $this->idRol = $row['idRol'];
 
                return true;
            }
        } else {
            error_log("Error en la preparación de la consulta: " . $this->conn->error);
        }
 
        return false;
    }

    /**
     * Método para verificar si ya existe un usuario
     */
    public function verificarCorreo($email) {
        $query = "SELECT 1 FROM " . $this->table_name . " WHERE Email = ? LIMIT 1";
    
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $email); // Vincula el correo
            $stmt->execute();
            $stmt->store_result();
    
            // Devuelve true si encontró un registro, false si no
            return $stmt->num_rows > 0;
        } else {
            error_log("Error al verificar correo: " . $this->conn->error);
            return false; // Error en la consulta
        }
    }

    /**
     * Método para obtener todos los usuarios
     * @return array|null Devuelve un array con los usuarios o null si ocurre un error
     */
    public function obtenerUsuarios(){

        $query = "SELECT idUsuario, Nombre, Apellido, Email FROM " . $this->table_name;

        if($result = $this->conn->query($query)){
            return $result->fetch_all(MYSQLI_ASSOC); //Devuelve todos los resultados como un array asociativo
        }else{
            error_log("Error al obtener usuarios: " . $this->conn->error);
            return null;
        }

    }

    /**
     * Método para iniciar sesión
     */
    public function iniciarSesion($email, $password){

        // Consulta para verificar correo y contraseña
        $query = "SELECT idUsuario, idRol, Password FROM " . $this->table_name . " WHERE Email = ?";

        //Preparar la consulta
        if($stmt = $this->conn->prepare($query)){
            //Vincular el correo con el parámetro
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            //Verificar si hay resultados
            if($row = $result->fetch_assoc()){
                //Comparar la contraseña ingresada con la base de datos
                if(password_verify($password, $row['Password'])){
                    //Iniciar la sesión y guardar el IdUsuario y el rol
                    session_start();
                    $_SESSION['idUsuario'] = $row['idUsuario'];
                    $_SESSION['idRol'] = $row['idRol'];

                    return true; //Credenciales correctas

                }else{
                    //Contrasea incorrecta
                    return false;
                }

            }else{
                //Correo no encontrado
                return false;
            }

        }else{
            //Error en la preparación de la consulta
            error_log("Error al preparar la consulta: " . $this->conn->error);
            return false;
        }

    }

    /**
     * Método para cerrar sesión
     */
    public function cerrarSesion() {

        session_start();

// Verificar si el usuario está autenticado

        if (!isset($_SESSION['idUsuario'])) {
        header("Location: ../InicioSesion.html");
        exit;
        }

        // Iniciar la sesión (por si no está iniciada)
        session_start();
    
        // Eliminar todos los datos de la sesión
        session_unset();
    
        // Destruir la sesión
        session_destroy();
    
        // Redirigir al usuario al inicio de sesión
        header("Location: ../Index.html");
        exit;
    }

}// Final de la clase

 
?>
 