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
$id_cate = $_POST['id_cate'] ?? '';
$old_id_cate = $_POST['old_id_cate'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Variables para los resultados de consulta y mensajes
$response = [
    'data' => null,
    'mensaje' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'consult') {
        // Consultar datos
        $stmt = $conn->prepare("SELECT * FROM categorias WHERE id_cate = ?");
        $stmt->bind_param("s", $id_cate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $response['data'] = $result->fetch_assoc();
        } else {
            $response['mensaje'] = "No se encontraron datos para ese ID de categoría.";
        }
    } elseif ($action === 'report') {
        // Consultar todos los registros
        $result = $conn->query("SELECT * FROM categorias");
        if ($result->num_rows > 0) {
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $response['mensaje'] = "No se encontraron datos en la tabla de categorías.";
        }
    } elseif ($action === 'insert') {
        // Insertar datos
        $stmt = $conn->prepare("INSERT INTO categorias (id_cate, nombre, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id_cate, $nombre, $descripcion);
        if ($stmt->execute()) {
            $response['mensaje'] = "Datos insertados correctamente.";
        } else {
            $response['mensaje'] = "Error al insertar datos: " . $stmt->error;
        }
    } elseif ($action === 'edit') {
        // Editar datos
        if ($id_cate === $old_id_cate) {
            $stmt = $conn->prepare("UPDATE categorias SET nombre=?, descripcion=? WHERE id_cate=?");
            $stmt->bind_param("sss", $nombre, $descripcion, $id_cate);
        } else {
            $stmt = $conn->prepare("UPDATE categorias SET id_cate=?, nombre=?, descripcion=? WHERE id_cate=?");
            $stmt->bind_param("ssss", $id_cate, $nombre, $descripcion, $old_id_cate);
        }
        if ($stmt->execute()) {
            $response['mensaje'] = "Datos actualizados correctamente.";
        } else {
            $response['mensaje'] = "Error al actualizar datos: " . $stmt->error;
        }
    } elseif ($action === 'delete') {
        // Eliminar datos
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id_cate=?");
        $stmt->bind_param("s", $id_cate);
        if ($stmt->execute()) {
            $response['mensaje'] = "Registro eliminado exitosamente.";
        } else {
            $response['mensaje'] = "Error al eliminar datos: " . $stmt->error;
        }
    }
    $conn->close();
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Categorías</title>
    <link rel="stylesheet" href="categorias.css">
    <script>
        async function handleFormSubmit(event) {
            event.preventDefault();
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const response = await fetch('categorias.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (document.getElementById('action').value !== 'consult' && document.getElementById('action').value !== 'report') {
                alert(result.mensaje);
            }
            
            if (result.data) {
                if (document.getElementById('action').value === 'consult') {
                    // Actualizar los campos del formulario con los datos consultados
                    document.getElementById('id_cate').value = result.data.id_cate;
                    document.getElementById('nombre').value = result.data.nombre;
                    document.getElementById('descripcion').value = result.data.descripcion;
                    document.getElementById('old_id_cate').value = result.data.id_cate; // Actualizar old_id_cate
                }
            } 
        }

        window.onload = function() {
            document.querySelector('form').addEventListener('submit', handleFormSubmit);
        };
    </script>
</head>
<body>
    <div class="container">
        <h1 class="title">Datos De Categorías</h1>
        <div class="input">
            <form action="categorias.php" method="post">
                <!--Formulario para pedir datos-->
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="old_id_cate" id="old_id_cate" value="">
                <label for="id_cate">ID Categoría:</label>
                <input type="number" name="id_cate" id="id_cate" class="ced" min="0" placeholder="123" required autofocus><br><br>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="nom" placeholder="Nombre de Categoría" required><br><br>
                <label for="descripcion">Descripción:</label><br><br>
                <input type="text" name="descripcion" id="descripcion" class="ape" placeholder="Descripción de Categoría" required><br><br>

                <!--Botones de CRUD-->
                <button type="button" class="ins" onclick="document.getElementById('action').value = 'insert'; handleFormSubmit(event)">Insertar</button>
                <button type="button" class="Con" onclick="document.getElementById('action').value = 'consult'; handleFormSubmit(event)">Consultar</button>
                <button type="button" class="edi" onclick="document.getElementById('action').value = 'edit'; handleFormSubmit(event)">Editar</button>
                <button type="button" class="eli" onclick="document.getElementById('action').value = 'delete'; handleFormSubmit(event)">Eliminar</button>
                <button type="button" class="sal" onclick="location.href='index.html'">Salir</button>
            </form>
        </div>
    </div>
</body>
</html>
