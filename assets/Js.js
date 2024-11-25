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
    .typeString('<strong>A la talla de tu éxito...</strong>')
    .pauseFor(2500)
    .start();