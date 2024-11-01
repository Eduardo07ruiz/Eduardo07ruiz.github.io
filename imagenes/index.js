// Seleccionamos los elementos del DOM que necesitamos
const $modal = document.getElementById('modal'), // Modal que se mostrará
      $btnCloseModal = document.getElementById('btn-close'), // Botón para cerrar el modal
      $imageCard = document.getElementById('image-card'); // Elemento que mostrará la imagen

// Función para abrir el modal
const openModal = () => 
      $modal.style.display = 'flex'; // Mostramos el modal estableciendo display en flex

// Función para cerrar el modal
const closeModal = () => 
      $modal.style.display = 'none'; // Ocultamos el modal estableciendo display en none

// Función para abrir la imagen en el modal
const openImage = (src, alt) => {
    $imageCard.src = src; // Establecemos la fuente de la imagen
    $imageCard.alt = alt; // Establecemos el texto alternativo de la imagen
    openModal(); // Abrimos el modal
}

// Agregamos un evento de clic al documento
document.addEventListener('click', (e) => {
    // Verificamos si se ha clickeado el botón de cierre del modal
    if (e.target === $btnCloseModal) closeModal(); 
    // Verificamos si se ha clickeado una imagen con el id "photo"
    if (e.target.matches('#photo')) openImage(e.target.src, e.target.alt); 
})