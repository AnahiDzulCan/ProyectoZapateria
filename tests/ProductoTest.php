<?php

use PHPUnit\Framework\TestCase;

require_once './php/Producto.php'; // Ruta de la clase que contiene el método crearProducto.

class ProductoTest extends TestCase
{
    private $mockConn; // Mock de conexión a la base de datos.
    private $producto;  // Instancia de la clase Producto.

    /**
     * Configuración inicial para cada prueba.
     */
    protected function setUp(): void
    {
        // Crear un mock para la conexión a la base de datos (mysqli).
        $this->mockConn = $this->createMock(mysqli::class);

        // Crear una instancia de Producto con la conexión simulada.
        $this->producto = new Producto($this->mockConn);
    }

    /**
     * Prueba para verificar que crearProducto inserta correctamente un Producto.
     */
    public function testCrearProducto()
    {
        // Crear un mock del statement (mysqli_stmt).
        $mockStmt = $this->createMock(mysqli_stmt::class);
    
        // Configurar el mock para el statement:
        // Aseguramos que bind_param y execute se llamen correctamente.
        $mockStmt->expects($this->once())
            ->method('bind_param')
            ->with($this->equalTo("iisssiii"), 1, 2,"Tenis", "2345", "Tenis color negro", 25, 350,10)
            ->willReturn(true);
    
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
    
        // Configurar el mock de la conexión para devolver el statement mock.
        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("INSERT INTO"))
            ->willReturn($mockStmt);
    
        // Llamar a crearProducto y verificar que retorna true.
        $resultado = $this->producto->crearProducto(
            1,                       // idCategoria
            2,                       // idGenero
            "Tenis",                 // String
            "2345",                  // String
            "Tenis color negro",     // String
            25,                      // Integer (Talla)
            350,                     // Integer (Precio)
            10                       // Integer (Stock)
        );
    
        // Verificar que el resultado es verdadero.
        $this->assertTrue($resultado);
    }
    

    
}
