<?php

session_start();

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
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Lista de Usuarios</h2>

    <!-- Mostrar mensaje -->
<?php if (isset($_SESSION['mensaje'])): ?>
    <p style="color: green;"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></p>
    <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
<?php endif; ?>

    <?php if (!empty($usuarios)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Acción</th>
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
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay usuarios registrados.</p>
    <?php endif; ?>

    <button onclick="window.location.href='../registro.html'">Regresar</button>
</body>
</html>
