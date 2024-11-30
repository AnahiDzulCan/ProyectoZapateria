// para crear el texto en movimiento
var app = document.getElementById('app');

var typewriter = new Typewriter(app, {
    loop: true
});

typewriter.typeString('La mejor zapateria')
    .pauseFor(2500)
    .deleteAll()
    .typeString('María Jose')
    .pauseFor(2500)
    .deleteAll()
    .typeString('A la talla de tu éxito...')
    .pauseFor(2500)
    .start();


  // Función para mostrar la alerta
  function mostrarToast() {
    const toast = document.getElementById('miToast');
    toast.classList.add('show'); // Añade la clase "show" para mostrarla
    setTimeout(() => {
      toast.classList.remove('show'); // La oculta después de 3 segundos
    }, 5000);
  }


// Función para iniciar sesión de iniciarsesion
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