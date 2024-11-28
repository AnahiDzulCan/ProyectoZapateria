<?php
include_once 'php/Database.php'; // Incluye la clase para la base de datos
include_once 'php/Producto.php';  // Incluye la clase Producto

// Crear la conexión a la base de datos
$database = new Database();
$db = $database->getConexionDB();

// Crear el objeto de la clase Producto
$producto = new Producto($db);

// Obtener los productos
$productos = $producto->obtenerProductos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- enlazado con el style -->
    <link rel="stylesheet" href="assets/styles.css">
    <!-- CDN Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <title>Catalogo</title>
</head>

<body>

    <!-- menuHome -->
    <header>
        <div class="px-3 py-2 border-bottom" id="header-menu">
          <div class="container1">
            <div class="containerlogo d-flex justify-content-between align-items-center p-3">
              <!-- Logo -->
              <a href="home.html" class="d-flex align-items-center text-white text-decoration-none">
                <img class="logo" src="assets/logo.png" alt="logo">
                <div class="ms-3">
                  <h5 class="m-3">Zapatería "María Jose"</h5>
                </div>
              </a>
              <!-- Menú -->
              <ul class="nav">
                <li><a href="home.html" class="nav-link text-white"><i class="bi bi-house-fill"></i> Home</a></li>
                <li><a href="productos.php" class="nav-link text-white"><i class="bi bi-bag-heart-fill"></i> Productos</a></li>
                <li><a href="carrito.html" class="nav-link text-white"><i class="bi bi-cart-fill"></i> Carrito</a></li>
                <li><a href="usuario.php" class="nav-link text-white"><i class="bi bi-person-heart"></i> Perfil</a></li>
              </ul>
            </div>
          </div>
     <!-- termina el menu home -->

      </header>


    <main>

        <div class="px-3 py-2 border-bottom mb-3">
            <div class="container d-flex flex-wrap justify-content-lg-end">
            <button type="button" class="btn btn-light text-dark me-2"  id="btns"><a href="registroProducto.html">Crear Producto</a></button>
                <div class="text-end">
                    <div class="dropdown" data-bs-theme="light">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonLight" data-bs-toggle="dropdown" aria-expanded="false">
                          Filtrar
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonLight">
                          <li><a class="dropdown-item active" href="#">Dama</a></li>
                          <li><a class="dropdown-item" href="#">Caballero</a></li>
                          <li><a class="dropdown-item" href="#">Niña</a></li>
                          <li><a class="dropdown-item" href="#">Niño</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <form class="p-2 mb-2 bg-body-tertiary border-bottom">
                            <input type="search" class="form-control" autocomplete="false" placeholder="Talla">
                            </form>
                        </li>
                        </ul>
                      </div>
                </div>
                  <!-- para inicio o registro --> 
    </div>
  </div> 




            </div>
        </div>

        <!-- cards para los productos -->
        <div class="container p-4">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

            <?php
            
            if ($productos) {
                while ($producto_row = $productos->fetch_assoc()) {
                    ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="assets/modelos/<?php echo htmlspecialchars($producto_row['Modelo']); ?>.png">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($producto_row['Nombre']); ?></h3>
                                <ul class="list-group list-group-flush p-2">
                                    <li class="list-group-item">Talla: <?php echo htmlspecialchars($producto_row['Talla']); ?></li>
                                    <li class="list-group-item">Modelo: <?php echo htmlspecialchars($producto_row['Modelo']); ?></li>
                                    <li class="list-group-item">Género: <?php echo htmlspecialchars($producto_row['Genero']); ?></li>
                                    <li class="list-group-item">Precio: <?php echo htmlspecialchars($producto_row['Precio']); ?></li>
                                    <li class="list-group-item">Stock: <?php echo htmlspecialchars($producto_row['Stock']); ?></li>
                                </ul>
                                <p class="card-text"><?php echo htmlspecialchars($producto_row['Descripcion']); ?></p>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                                  <form method="POST" action="editProducto.php">
                                  <input type="hidden" name="idProducto" value="<?php echo $producto_row['idProducto']; ?>">
                                  <input type="hidden" name="accion" value="editar">
                                    <button type="submit" id="btns" class="btn btn-outline">Editar</button>
                                  </form>

                                  <form method="POST" action="php/ProductoController.php">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="idProducto" value="<?php echo $producto_row['idProducto']; ?>">
                                    <button type="submit" id="btns" class="btn btn-outline">Eliminar</button>
                                  </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No hay productos disponibles.";
            }
            
            ?>
            
            </div>
        </div>
        <!-- Terminan cards para los productos -->


    </main>




  <!-- Footer -->
<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top" id="footer">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
        <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
      </a>
      <span class="mb-3 mb-md-0 text-body-secondary">© Zapatería "María José"</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-whatsapp fs-3"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-instagram fs-3"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-facebook fs-3"></i></a></li>
      <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-twitter fs-3"></i></a></li>
    </ul>
</footer>

    <!-- script = funcionalidad para los elementos -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- CDN JS, TEXTO MOVIMIENTO -->
    <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
    <!-- main js -->
    <script src="assets/Js.js"></script>
</body>

</html>