document.getElementById('loginForm').addEventListener('click', function(event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    var emailInput = document.getElementById('email');
    var emailValue = emailInput.value;
    var messageElement = document.getElementById('message');

    // Regex para validar el formato del correo electrónico
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (emailPattern.test(emailValue)) {
        // Redirige a 'email.html' si el correo es válido
        window.location.href = "email.html"; // Ajusta la ruta según la estructura de tu proyecto
    } else {
        // Muestra un mensaje de error si el correo no es válido
        messageElement.textContent = 'Ingrese un correo electrónico válido.';
        messageElement.style.display = 'block';
    }
});
