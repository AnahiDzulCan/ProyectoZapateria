<?php

use PHPUnit\Framework\TestCase;

require_once './php/Usuario.php'; // Ruta de la clase que contiene el método crearUsuario.

class UsuarioTest extends TestCase
{
    private $mockConn; // Mock de conexión a la base de datos.
    private $usuario;  // Instancia de la clase Usuario.

    /**
     * Configuración inicial para cada prueba.
     */
    protected function setUp(): void
    {
        // Crear un mock para la conexión a la base de datos (mysqli).
        $this->mockConn = $this->createMock(mysqli::class);

        // Crear una instancia de Usuario con la conexión simulada.
        $this->usuario = new Usuario($this->mockConn);
    }

    /**
     * Prueba para verificar que crearUsuario inserta correctamente un usuario.
     */
    public function testCrearUsuario()
    {
        // Crear un mock del statement (mysqli_stmt).
        $mockStmt = $this->createMock(mysqli_stmt::class);
    
        // Configurar el mock para el statement:
        // Aseguramos que bind_param y execute se llamen correctamente.
        $mockStmt->expects($this->once())
            ->method('bind_param')
            ->with($this->equalTo("ssssii"), "Juan", "Pérez", "juan.perez@example.com", "12345", 1, 0)
            ->willReturn(true);
    
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
    
        // Configurar el mock de la conexión para devolver el statement mock.
        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("INSERT INTO"))
            ->willReturn($mockStmt);
    
        // Llamar a crearUsuario y verificar que retorna true.
        $resultado = $this->usuario->crearUsuario(
            "Juan",                        // String
            "Pérez",                       // String
            "juan.perez@example.com",      // String
            "12345",                       // String
            1,                             // Integer (idGenero)
            0                              // Integer (idRol)
        );
    
        // Verificar que el resultado es verdadero.
        $this->assertTrue($resultado);
    }
    

    
}
