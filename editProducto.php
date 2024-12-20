<?php

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
  header("Location: InicioSesion.html"); // Redirige si no está autenticado
  exit();
}

if (isset($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
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
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <title>RegistroProducto</title>
</head>
<body>
    <!-- menuHome -->
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
            <li><a href="carrito.php" class="nav-link fs-6"><i class="bi bi-cart-fill"></i> Carrito</a></li>
            <li><a href="usuario.php" class="nav-link fs-6"><i class="bi bi-person-heart"></i> Perfil</a></li>
          </ul>
       </div>
          
      </nav>
  </div>
          
</header>

      <main>

        <div class="d-flex justify-content-center align-items-center p-4">
          <div class="card" id="Form-registro">
              <div class="card-body p-4 rounded shadow bg-light">
                  <h5 class="card-header text-center">Editar producto</h5>
                  <form method="POST" action="php/ProductoController.php">

                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="idProducto" value="<?php echo $idProducto;?>">
            
                    <label for="idCategoria">idCategoria:</label>
                    <input type="number" id="idCategoria" name="idCategoria" class="form-control m-3"  min="1" required>

                    <div class="m-0">
                      <label>Género:</label>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="idgenero" id="masculino" value="1" checked>
                        <label class="form-check-label" for="inlineRadio1">Masculino</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="idgenero" id="femenino" value="2" checked>
                        <label class="form-check-label" for="inlineRadio2">Femenino</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="idgenero" id="unisex"  value="3" checked>
                        <label class="form-check-label" for="inlineRadio3">Unisex</label>
                      </div>
                    <div>

                    <label for="Nombre">Nombre:</label>
                    <input type="text" id="Nombre" name="Nombre" class="form-control" required>
            
                    <label for="Descripcion">Descripción:</label>
                    <input type="text" id="Descripcion" name="Descripcion" class="form-control"  required>
            
                    <label for="Talla">Talla:</label>
                    <input type="number" id="Talla" name="Talla" class="form-control"  min="1" max="50" required>

                    <label for="Nombre">Modelo:</label>
                    <input type="text" id="Modelo" name="Modelo" class="form-control"  required>
            
                    <label for="Precio">Precio:</label>
                    <input type="number" id="Precio" name="Precio"  class="form-control"  min="1" required>
            
                    <label for="Stock">Stock:</label>
                    <input type="number" id="Stock" name="Stock" class="form-control"  min="1" required>
       
                    <button id="btns" class="btn m-3 d-flex " type="submit" value="crear">Actualizar</button>

                </form>
            
              </div>
          </div>
      </div>

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

      <script>
        // Función para verificar que las contraseñas coincidan
        function verificarContraseñas() {
            // Obtener los valores de los campos de contraseña y confirmación
            var password = document.getElementById("password").value;
            var repassword = document.getElementById("repassword").value;
    
            // Verificar si las contraseñas coinciden
            if (password !== repassword) {
                alert("Las contraseñas no coinciden. Por favor, intente de nuevo.");
                return false; // Impide el envío del formulario
            }
    
            return true; // Permite el envío del formulario
        }
    
        // Asociar la función al evento de envío del formulario
        document.querySelector("form").onsubmit = verificarContraseñas;
    </script>

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