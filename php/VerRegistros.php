<?php

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../InicioSesion.html"); // Redirige si no está autenticado
    exit();
}

include_once 'UsuarioController.php';

// Crear instancia del controlador
$usuarioController = new UsuarioController();

// Obtener lista de usuarios
$usuarios = $usuarioController->listarUsuarios();
$mensaje = "";

//Procesador la solicitud de la eliminacion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $idUsuario = $_POST['idUsuario'];
    $mensaje = $usuarioController->eliminarUsuario($idUsuario);

    // Guardar el mensaje en la sesión
    $_SESSION['mensaje'] = $mensaje;

    // Actualizar la lista de usuarios después de eliminar
    $usuarios = $usuarioController->listarUsuarios();
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
     <link rel="stylesheet" href="../assets/styles.css">
         <!-- CDN Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Lista de usuarios</title>

</head>
<body>
<header class="main-header">
  <div class="px-3 py-2 border-bottom" id="Contenedor1">
    <nav class="navbar header-nav navbar-expand-lg" id="navbar-header">
             <!-- Logo  -->
      <div class="containerlogo d-flex justify-content-between align-items-center p-3">
        <a href="../home.html" class="navbar-brand">
          <img class="logo" src="../assets/logo.png" alt="logo"> <h5 class=" m-0 text-white ">Zapatería "María Jose"</h5>
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
            <li><a href="../home.html" class="nav-link fs-6"><i class="bi bi-house-fill"></i> Home</a></li>
            <li><a href="../productosAdmin.php" class="nav-link fs-6"><i class="bi bi-bag-heart-fill"></i> Productos</a></li>
            <li><a href="../carrito.php" class="nav-link fs-6"><i class="bi bi-cart-fill"></i> Carrito</a></li>
            <li><a href="../usuario.php" class="nav-link fs-6"><i class="bi bi-person-heart"></i> Perfil</a></li>
          </ul>
       </div>
          
      </nav>
  </div>
          
</header>

<main class="p-4 m-3">

<!-- Mostrar mensaje -->
<?php if (isset($_SESSION['mensaje'])): ?>
<p style="color: green;"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></p>
<?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
<?php endif; ?>

<?php if (!empty($usuarios)): ?>
<table class="table">
  <thead>
  <h2>Lista de Usuarios</h2>
  <tr class="table-active">
    <td>id</td>
      <td>Nombre</td>
      <td>Apellido</td>
      <td>Email</td>
      <td>Eliminar</td> 
    </tr>
  </thead>
  <tbody>
  <?php foreach ($usuarios as $usuario): ?>
    <tr>
    <td><?php echo htmlspecialchars($usuario['idUsuario']); ?></td>
      <td><?php echo htmlspecialchars($usuario['Nombre']); ?></td>
      <td><?php echo htmlspecialchars($usuario['Apellido']); ?></td>
      <td><?php echo htmlspecialchars($usuario['Email']); ?></td>
      <td>
      <form method="POST" action="">
         <input type="hidden" name="accion" value="eliminar">
         <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars($usuario['idUsuario']); ?>">
         <button class="text-decoration-none" type="submit"><i class="bi bi-trash3"></i></button>
      </form>
    </td> 
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
    <p>No hay usuarios registrados.</p>
<?php endif; ?>

<button  onclick="window.location.href='../productosAdmin.php'" type="button" class="btn btn-light text-white"  id="btns" >Regresar</button>

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
            <button class="btn btn-primary" type="button">Enviar</button>
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
