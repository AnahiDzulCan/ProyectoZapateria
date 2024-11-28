<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
  header("Location: InicioSesion.html"); // Redirige si no está autenticado
  exit();
}

// Incluye la clase Database
include_once 'php/Database.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: InicioSesion.html"); // Redirige si no está autenticado
    exit();
}

// Crear instancia de la clase Database y obtener la conexión
$db = new Database();
$conn = $db->getConexionDB();

// Obtener el idUsuario de la sesión
$idUsuario = $_SESSION['idUsuario'];

// Consulta SQL para obtener los datos del usuario
$query = "
    SELECT u.Nombre, u.Apellido, u.Email, g.Nombre
    FROM usuarios u
    INNER JOIN genero g ON u.idGenero = g.idGenero
    WHERE u.idUsuario = ?";

// Preparar la consulta
if ($stmt = $conn->prepare($query)) {
  // Enlazar el parámetro (idUsuario)
  $stmt->bind_param("i", $idUsuario);
  
  // Ejecutar la consulta
  $stmt->execute();
  
  // Almacenar el resultado
  $stmt->store_result();
  
  // Verificar si el usuario fue encontrado
  if ($stmt->num_rows > 0) {
      // Enlazar las variables con los resultados
      $stmt->bind_result($nombre, $apellido, $email, $genero);
      
      // Obtener el primer resultado (solo debería haber uno)
      $stmt->fetch();
  } else {
      echo "Usuario no encontrado.";
      exit();
  }
} else {
  echo "Error al preparar la consulta.";
  exit();
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

    <title>Perfil</title>
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
          
              <div class="row d-flex justify-content-center m-4">
                <div class="col-lg-4">
                  <div class="card mb-4">
                    <div class="card-body text-center">
                      <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
                        class="rounded-circle img-fluid" style="width: 150px;">
                      <h5 class="my-3"> <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?> </h5>
                      <p class="text-muted mb-1"> <?php echo htmlspecialchars($email); ?> </p>
                      <p class="text-muted mb-4"> <?php echo htmlspecialchars($genero); ?> </p>
                      <div class="d-flex justify-content-center mb-2">
                        <form method="get" action="modificarUsuario.html">
                          <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Modificar datos</button>
                        </form>
                        <form method="POST" action="php/UsuarioController.php">
                          <input type="hidden" name="accion" value="cerrarSesion">
                          <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary ms-1">Cerrar sesión</button>
                        </form>
                      </div>
                    </div>
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