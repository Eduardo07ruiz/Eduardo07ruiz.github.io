<?php


// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';
$old_id = $_POST['old_id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$descripción = $_POST['descripción'] ?? '';
$precio = $_POST['precio'] ?? '';

// Variables para los resultados de consulta y mensajes
$id_result = $nombre_result = $descripción_result = $precio_result = $mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'consult') {
        // Consultar datos
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_result = $row['id'];
            $nombre_result = $row['nombre'];
            $descripción_result = $row['descripción'];
            $precio_result = $row['precio'];
        } else {
            $mensaje = "No se encontraron datos para ese ID de producto.";
        }
    } elseif ($action === 'insert') {
        // Insertar datos
        $stmt = $conn->prepare("INSERT INTO productos (id, nombre, descripción, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $nombre, $descripción, $precio);
        if ($stmt->execute()) {
            $mensaje = "Datos insertados correctamente.";
        } else {
            $mensaje = "Error al insertar datos: " . $stmt->error;
        }
    } elseif ($action === 'edit') {
        // Editar datos
        if ($id !== $old_id) {
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripción=?, precio=? WHERE id=?");
            $stmt->bind_param("ssss", $nombre, $descripción, $precio, $id);
        } else {
            $stmt = $conn->prepare("UPDATE productos SET id=?, nombre=?, descripción=?, precio=? WHERE id=?");
            $stmt->bind_param("sssss", $id, $nombre, $descripción, $precio, $old_id);
        }
        if ($stmt->execute()) {
            $mensaje = "Datos actualizados correctamente.";
        } else {
            $mensaje = "Error al actualizar datos: " . $stmt->error;
        }
    } elseif ($action === 'delete') {
        // Eliminar datos
        $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $mensaje = "Registro eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar datos: " . $stmt->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Productos</title>
    <link rel="stylesheet" href="productos.css">
    <script>
        function showAlert(message) {
            alert(message);
        }

        function setAction(action) {
            document.getElementById('action').value = action;
            document.querySelector('form').submit();
        }

        window.onload = function() {
            // Mostrar mensaje de error si existe
            <?php if ($mensaje): ?>
                showAlert("<?php echo addslashes($mensaje); ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <h1 class="title">Datos De Productos</h1>
        <div class="input">
            <form action="productos.php" method="post">
                <!--Formulario para pedir datos-->
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="old_id" value="<?php echo htmlspecialchars($id_result, ENT_QUOTES, 'UTF-8'); ?>">
                <label for="id">ID Producto:</label>
                <input type="number" name="id" id="id" class="ced" min="0" placeholder="123" value="<?php echo htmlspecialchars($id_result, ENT_QUOTES, 'UTF-8'); ?>" required autofocus><br><br>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="nom" placeholder="Nombre del Producto" value="<?php echo htmlspecialchars($nombre_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="descripción">Descripción:</label>
                <input type="text" name="descripción" id="descripción" class="ape" placeholder="Descripción del Producto" value="<?php echo htmlspecialchars($descripción_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="precio">Precio:</label>
                <input type="number" name="precio" id="precio" class="dir" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars($precio_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

                <!--Botones de CRUD-->
                <button type="button" class="ins" onclick="setAction('insert')">Insertar</button>
                <button type="button" class="Con" onclick="setAction('consult')">Consultar</button>
                <button type="button" class="edi" onclick="setAction('edit')">Editar</button>
                <button type="button" class="eli" onclick="setAction('delete')">Eliminar</button>
                <button type="button" class="sal" onclick="location.href='index.html'">Salir</button>
            </form>
        </div>
    </div>
</body>
</html>
