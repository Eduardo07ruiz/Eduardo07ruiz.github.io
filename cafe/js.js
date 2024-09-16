document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const messageElement = document.createElement('p'); // Crear un elemento para mostrar el mensaje de error
    messageElement.style.display = 'none';
    messageElement.style.color = 'red'; // Estilo para el mensaje de error
    form.appendChild(messageElement); // Agregar el mensaje al formulario

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        const emailInput = document.getElementById('email');
        const emailValue = emailInput.value;

        // Regex para validar el formato del correo electrónico
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailPattern.test(emailValue)) {
            // Redirige a 'mensaje.html' si el correo es válido
            window.location.href = "mensaje.html"; // Ajusta la ruta según la estructura de tu proyecto
        } else {
            // Muestra un mensaje de error si el correo no es válido
            messageElement.textContent = 'Ingrese un correo electrónico válido.';
            messageElement.style.display = 'block';
        }
    });
});