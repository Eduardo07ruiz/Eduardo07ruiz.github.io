
var nombre = 'Eduardo';
var primer_apellido = 'Ruiz';
var segundo_apellido = 'Borrero'


var datos = document.getElementById('nombre');
var datos = document.getElementById('primer_apellido');
var datos = document.getElementById('segundo_apellido');


datos.innerHTML=`
<h1> hola mi nombre es: ${nombre}</h1>
<h1> mi primer apellido es: ${primer_apellido}</h1>
<h1> mi segundo apellido es: ${segundo_apellido}</h1>
`;