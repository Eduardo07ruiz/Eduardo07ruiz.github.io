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
$cedula = $_POST['cedula'] ?? '';
$old_cedula = $_POST['old_cedula'] ?? '';
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

// Variables para los resultados de consulta y mensajes
$cedula_result = $nombres_result = $apellidos_result = $direccion_result = $telefono_result = $email_result = $contraseña_result = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'consult') {
        // Consultar datos
        $stmt = $conn->prepare("SELECT * FROM empleados WHERE cedula = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cedula_result = $row['cedula'];
            $nombres_result = $row['nombres'];
            $apellidos_result = $row['apellidos'];
            $direccion_result = $row['direccion'];
            $telefono_result = $row['telefono'];
            $email_result = $row['email'];
            $contraseña_result = $row['contraseña'];
        } else {
            $mensaje = "No se encontraron datos para ese número de cédula.";
        }
    } elseif ($action === 'insert') {
        // Insertar datos
        $stmt = $conn->prepare("INSERT INTO empleados (cedula, nombres, apellidos, direccion, telefono, email, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $cedula, $nombres, $apellidos, $direccion, $telefono, $email, $contraseña);
        if ($stmt->execute()) {
            $mensaje = "Datos insertados correctamente.";
        } else {
            $mensaje = "Error al insertar datos: " . $stmt->error;
        }
    } elseif ($action === 'edit') {
        // Editar datos
        if ($cedula !== $old_cedula) {
            $stmt = $conn->prepare("UPDATE empleados SET cedula=?, nombres=?, apellidos=?, direccion=?, telefono=?, email=?, contraseña=? WHERE cedula=?");
            $stmt->bind_param("ssssssss", $cedula, $nombres, $apellidos, $direccion, $telefono, $email, $contraseña, $old_cedula);
        } else {
            $stmt = $conn->prepare("UPDATE empleados SET nombres=?, apellidos=?, direccion=?, telefono=?, email=?, contraseña=? WHERE cedula=?");
            $stmt->bind_param("sssssss", $nombres, $apellidos, $direccion, $telefono, $email, $contraseña, $cedula);
        }
        if ($stmt->execute()) {
            $mensaje = "Datos actualizados correctamente.";
        } else {
            $mensaje = "Error al actualizar datos: " . $stmt->error;
        }
    } elseif ($action === 'delete') {
        // Eliminar datos
        $stmt = $conn->prepare("DELETE FROM empleados WHERE cedula=?");
        $stmt->bind_param("s", $cedula);
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
    <title>Datos-usuarios</title>
    <link rel="stylesheet" href="usuarios.css">
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
        <h1 class="title">Datos De Empleados</h1>
        <div class="input">
            <form action="empleados.php" method="post">
                <!--Formulario para pedir datos-->
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="old_cedula" value="<?php echo htmlspecialchars($cedula_result, ENT_QUOTES, 'UTF-8'); ?>">
                <label for="cedula">Cédula:</label>
                <input type="number" name="cedula" id="cedula" class="ced" min="0" placeholder="12345678" value="<?php echo htmlspecialchars($cedula_result, ENT_QUOTES, 'UTF-8'); ?>" required autofocus><br><br>
                <label for="nombres">Nombres:</label>
                <input type="text" name="nombres" id="nombres" class="nom" placeholder="Eduardo Luis" value="<?php echo htmlspecialchars($nombres_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" class="ape" placeholder="Ruiz Borrero" value="<?php echo htmlspecialchars($apellidos_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="dir" placeholder="Carrera 15 # 32-10, Barrio Chapinero" value="<?php echo htmlspecialchars($direccion_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="telefono">Teléfono:</label>
                <input type="number" name="telefono" id="telefono" class="tel" min="0" placeholder="3001234567" value="<?php echo htmlspecialchars($telefono_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" class="ema" placeholder="ejemplo@dominio.com" value="<?php echo htmlspecialchars($email_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" class="con" placeholder="Contraseña" value="<?php echo htmlspecialchars($contraseña_result, ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
                <input type="checkbox" id="Vpass" class="checkbox"><br>

                <!--Botones de CRUD-->
                <button type="button" class="ins" onclick="setAction('insert')">Insertar</button>
                <button type="button" class="Con" onclick="setAction('consult')">Consultar</button>
                <button type="button" class="edi" onclick="setAction('edit')">Editar</button>
                <button type="button" class="eli" onclick="setAction('delete')">Eliminar</button>
                <button type="button" class="sal" onclick="location.href='index.html'">Salir</button>

                <!-- Ver o ocultar la contraseña con JavaScript -->
                <script>
                    const pw = document.getElementById("contraseña");
                    const Vpass = document.getElementById("Vpass");

                    Vpass.onchange = function () {
                        pw.type = Vpass.checked ? "text" : "password";
                    };
                </script>
            </form>
        </div>
    </div>
</body>
</html>