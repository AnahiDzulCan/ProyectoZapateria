<?php
/**
 * @author Pedro José Bacab Pech
 */

class Database{
    
    //Atributos de la clase
    private $host = "localhost"; //Dirección del servidor
    private $db_name = "zapateriadb"; // Nombre de la base de datos
    private $username = "root"; // Usuario de la base de datos
    private $password = ""; //Contraseña de la base de datos

    /**
     * Función para obtener la conexión
     */
    public function getConexionDB(){
        $this->conn = null;

        //Establecer la conexión
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        //Verificar si hay alguna error en la conexión
        if($this->conn->connect_error){
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        //Retornar la conexión
        return $this->conn;

    }

}


?>