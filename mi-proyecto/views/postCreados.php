<?php
session_start();
require_once '../inc/funciones.php';
require_once '../inc/conexion.php';

if (!verificar_rol('admin')) {
    echo "Acceso denegado.";
    exit;
}

// Suponiendo que el ID del usuario logueado está almacenado en $_SESSION['usuario_id']
$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    echo "No se ha encontrado el ID del usuario logueado.";
    exit;
}

$errores = ['titulo' => '', 'descripcion' => '', 'imagen' => '', 'exito' => ''];
$titulo = '';
$descripcion = '';
$rutaImagen = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiar_dato($_POST['titulo']);
    $descripcion = limpiar_dato($_POST['descripcion']);

    // Validaciones
    if (empty($titulo)) {
        $errores['titulo'] = 'Ingrese un título.';
    }
    if (strlen($descripcion) < 10) {
        $errores['descripcion'] = 'La descripción debe tener al menos 10 caracteres.';
    }

    // Verificar si la descripción ya existe en la base de datos
    $sqlVerificacion = "SELECT COUNT(*) FROM post WHERE descripcion = :descripcion";
    $stmtVerificacion = $conexion->prepare($sqlVerificacion);
    $stmtVerificacion->bindParam(':descripcion', $descripcion);
    $stmtVerificacion->execute();
    if ($stmtVerificacion->fetchColumn()) {
        $errores['descripcion'] = 'La descripción ya está registrada.';
    }

    // Manejo de la carga de archivos
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $directorioDestino = '../uploads/';

        if (move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo)) {
            $rutaImagen = $nombreArchivo;
        } else {
            $errores['imagen'] = 'Error al mover el archivo.';
        }
    } else if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errores['imagen'] = 'Error en la subida de archivo.';
    }

    // Si no hay errores, proceder con el post
    if (empty(array_filter($errores))) {
        $sql = "INSERT INTO post (titulo, url_imagen, descripcion, usuario_id) VALUES (:titulo, :url_imagen, :descripcion, :usuario_id)";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':url_imagen', $rutaImagen);
        $stmt->bindParam(':usuario_id', $usuario_id);

        if ($stmt->execute()) {
            // Redireccionar a postCreado.php con el usuario_id en la URL
            header("Location: postCreado.php?usuario_id=" . $usuario_id);
            exit;
        } else {
            $errores['exito'] = 'Error al registrar el post.';
        }
    }
}
?>

<!-- Aquí sigue tu código HTML para el formulario -->

// Verifica si el usuario está logueado y obtiene el ID desde la URL
if (!isset($_GET['usuario_id'])) {
    echo "Acceso denegado.";
    exit;
}

$usuario_id = $_GET['usuario_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Post Privado</title>
</head>
<body>
    <h2>Post creado exitosamente</h2>
    <p>El post ha sido creado por el usuario con ID: <?php echo $usuario_id; ?></p>
    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
