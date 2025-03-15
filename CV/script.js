window.onload = function() {
  // Cambiar la URL para incluir el hash de #inicio
  window.location.hash = "inicio";
}

const menuItems = document.querySelectorAll(".menu-items .item");

menuItems[0].classList.add("selected");

menuItems.forEach((item) => {
  item.addEventListener("click", () => {
    // Eliminar la clase 'selected' de todas las opciones
    menuItems.forEach((item) => {
      item.classList.remove("selected");
    });
    item.classList.add("selected");
  });
});

const sidebar = document.querySelector(".sidebar");
const sidebarClose = document.querySelector("#sidebar-close");
sidebarClose.addEventListener("click", () => sidebar.classList.toggle("close"));

menuItems.forEach((item, index) => {
  item.addEventListener("click", () => {
    item.classList.add("show-submenu");
    menuItems.forEach((item2, index2) => {
      if (index !== index2) {
        item2.classList.remove("show-submenu");
      }
    });
  });
});