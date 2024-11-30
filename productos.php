<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
  header("Location: InicioSesion.html"); // Redirige si no está autenticado
  exit();
}

$idRol = $_SESSION['idRol'];

if($idRol==1){
    header("Location: productosAdmin.php");
    exit;
}

include_once 'php/Database.php'; // Incluye la clase para la base de datos
include_once 'php/Producto.php';  // Incluye la clase Producto

// Crear la conexión a la base de datos
$database = new Database();
$db = $database->getConexionDB();

// Crear el objeto de la clase Producto
$producto = new Producto($db);

// Obtener los filtros desde la URL
$filtroGenero = isset($_GET['filtroGenero']) ? (int) $_GET['filtroGenero'] : null;
$filtroTalla = isset($_GET['filtroTalla']) ? (int) $_GET['filtroTalla'] : null;
$filtroCategoria = isset($_GET['filtroCategoria']) ? (int) $_GET['filtroCategoria'] : null;

// Verificar si hay filtros en la URL
if ($filtroGenero || $filtroTalla || $filtroCategoria) {
  // Si hay filtros, obtener los productos filtrados
  $productos = $producto->obtenerProductosFiltrados($filtroGenero, $filtroTalla, $filtroCategoria);
} else {
  // Si no hay filtros, obtener todos los productos
  $productos = $producto->obtenerProductos();
}


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
<!-- inicio Header -->
<header class="main-header">
     <div class="px-3 py-2 border-bottom" id="Contenedor1">
       <nav class="navbar header-nav navbar-expand-lg" id="navbar-header">
                   <!-- Logo  -->
         <div class="containerlogo d-flex justify-content-between align-items-center p-3">
            <a href="home.html" class="navbar-brand">
               <img class="logo" src="assets/logo.png" alt="logo"> <h5 class=" m-0 text-white ">Zapatería "María Jose"</h5>
             </a>
         </div>
        
            <!-- botón Menú(para móviles) -->
        <button class="navbar-toggler collapsed p-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
             <!-- menú -->
        <div class="collapse navbar-collapse justify-content-end " id="navbar-collapse-toggle">
            <ul class="navbar-nav ">
                  <li><a href="home.html" class="nav-link fs-6"><i class="bi bi-house-fill"></i> Home</a></li>
                  <li><a href="productos.php" class="nav-link fs-6"><i class="bi bi-bag-heart-fill"></i> Productos</a></li>
                  <li><a href="carrito.html" class="nav-link fs-6"><i class="bi bi-cart-fill"></i> Carrito</a></li>
                  <li><a href="usuario.php" class="nav-link fs-6"><i class="bi bi-person-heart"></i> Perfil</a></li>
            </ul>
        </div>      
       </nav>
    </div>            
 </header>
<!-- Fin Headaer -->


    <main>

        <div class="px-3 py-2 border-bottom mb-3">
            <div class="container d-flex flex-wrap justify-content-lg-end">
                <div class="text-end">
                    <!-- <div class="dropdown" data-bs-theme="light">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonLight" data-bs-toggle="dropdown" aria-expanded="false">
                          Filtrar
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonLight">
                          <li><a class="dropdown-item active" href="#">Dama</a></li>
                          <li><a class="dropdown-item" href="#">Caballero</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <form class="p-2 mb-2 bg-body-tertiary border-bottom">
                            <input type="search" class="form-control" autocomplete="false" placeholder="Talla">
                            </form>
                        </li>
                        </ul>
                      </div> -->

                      <div class="dropdown" data-bs-theme="light">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButtonLight" data-bs-toggle="dropdown" aria-expanded="false">
                Filtrar
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonLight">
                    <li><a class="dropdown-item" href="?filtroGenero=2">Dama</a></li>
                    <li><a class="dropdown-item" href="?filtroGenero=1">Caballero</a></li>
                    <li><a class="dropdown-item" href="?filtroCategoria=3">Sandalia</a></li>
                    <li><a class="dropdown-item" href="?filtroCategoria=2">Tacon</a></li>
                    <li><a class="dropdown-item" href="?filtroCategoria=1">Tenis</a></li>
                    <li><a class="dropdown-item" href="?filtroCategoria=4">Chancla</a></li>
                    <li><hr class="dropdown-divider"></li>
        <li>
            <form class="p-2 mb-2 bg-body-tertiary border-bottom" method="GET">
                <input type="number" name="filtroTalla" class="form-control" placeholder="Talla" min="1" max="50">
                <button type="submit" class="btn btn-light text-white"  id="btns">Filtrar por talla</button>
            </form>
        </li>
    </ul>
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
                                    <button type="button" id="btns" class="btn btn-light text-white">Añadir al carrito</button>
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
  <footer class="py-5" >
      <div class="row d-flex justify-content-center">

        <div class="col-6 col-md-2 mb-3 m-4 ">
          <h5 class="d-flex justify-content" >Para ti</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="#descuentos" class="nav-link p-0 text-body-secondary">Descuentos</a></li>
            <li class="nav-item mb-2"><a href="#Ver-rapido" class="nav-link p-0 text-body-secondary">Ver rapido</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">De temporada</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Cupones</a></li>
          </ul>
        </div>
  
        <div class="col-md-5 offset-md-1 mb-3">
          <form>
            <h5>Tu opinión nos importa, ¡déjanos un comentario!</h5>
            <p>Queremos conocer las opiniones de los demás antes de decidirnos, porque esto nos da confianza en nuestra elección..</p>
            <div class="d-flex flex-column flex-sm-row w-100 gap-2">
              <label for="newsletter1" class="visually-hidden">Comenta</label>
              <input id="newsletter1" type="text" class="form-control">
              <button class="btn btn-light text-white"  id="btns" type="button">Enviar</button>
            </div>
          </form>
        </div>
      </div>
  
      <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top p-5">
        <p>©Zapatería "María Jose"</p>
        <ul class="list-unstyled d-flex">
          <li class="ms-3"><a class="link-body-emphasis" href="#"><i class="bi bi-whatsapp fs-3"></i></a></li>
          <li class="ms-3"><a class="link-body-emphasis" href="#"><i class="bi bi-instagram fs-3"></i></a></li>
          <li class="ms-3"><a class="link-body-emphasis" href="#"><i class="bi bi-facebook fs-3"></i></a></li>
          <li class="ms-3"><a class="link-body-emphasis" href="#"><i class="bi bi-twitter fs-3"></i></a></li>
        </ul>
      </div>
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