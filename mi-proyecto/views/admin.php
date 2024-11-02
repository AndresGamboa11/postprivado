<?php
session_start();
require_once '../inc/funciones.php';
require_once '../inc/conexion.php';

if (!verificar_rol('admin')) {
    echo "Acceso denegado.";
    exit;
}


$errores = ['titulo' => '', 'descripcion' => '', 'exito' => ''];

$titulo = '';
$descripcion = '';
$rutaImagen = 'url_imagen';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiar_dato($_POST['titulo']);
    $descripcion = limpiar_dato($_POST['descripcion']);

    // Validaciones
    if (empty($titulo)) {
        $errores['titulo'] = 'Ingrese un titulo.';
    }
    if (strlen($descripcion) < 10) {  // Ejemplo de validación de longitud
        $errores['descripcion'] = 'Ingrese una descripción.';
    }
    
    // Verificar si la descripción ya existe en la base de datos
    $sqlVerificacion = "SELECT COUNT(*) FROM post WHERE descripcion = :descripcion";
    $stmtVerificacion = $conexion->prepare($sqlVerificacion);
    $stmtVerificacion->bindParam(':descripcion', $descripcion);
    $stmtVerificacion->execute();
    $descripcionExiste = $stmtVerificacion->fetchColumn();

    if ($descripcionExiste) {
        $errores['descripcion'] = 'Ingrese una descripción.';
    }
    if ($descripcionExiste) {
        $errores['imagen'] = 'Error en la subida de la imagen.';
    }
    
    // Manejo de la carga de archivos y si no  se sube una imagen
   // Manejo de la carga de archivos
   if (isset($_FILES['url_imagen']) && $_FILES['url_imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['url_imagen']['name'];
    $rutaTemporal = $_FILES['url_imagen']['tmp_name'];
    $directorioDestino = '../uploads/';

    if (move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo)) {
        $rutaImagen = $nombreArchivo;
    } else {
        $errores['imagen'] = 'Error al mover el archivo.';
    }
    } else if (isset($_FILES['url_imagen']) && $_FILES['url_imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errores['imagen'] = 'Error en la subida de archivo.';
}

    // Si no hay errores, proceder con el post
    if (empty(array_filter($errores))) {
        $sql = "INSERT INTO post (titulo, url_imagen, descripcion) VALUES (:titulo, :url_imagen, :descripcion)";

        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':url_imagen', $rutaImagen);

        if ($stmt->execute()) {
            $errores['exito'] = 'Usuario registrado exitosamente.';
        } else {
            $errores['exito'] = 'Error al registrar el usuario.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body{
            margin: 0; /* Elimina márgenes por defecto */

        }
        .caja{
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */
            background-color: #f0f0f0; /* Color de fondo opcional */
        }

        header{
            display: flex; /* Activa el modo flexbox */
            justify-content: flex-end; /* Alinea horizontalmente el contenido a la derecha */
            align-items: center; /* Centra verticalmente el contenido dentro del header */
            height: 50px;
            background-color: white;
        }
        p{
            font-weight: 500;
        }
        a{
            padding-right: 20px;
            text-decoration: none; /* Elimina el subrayado del enlace */
            color: black;
            font-size: 23px;
        }
        /* Estilo para inputs, selects y botones */
        
        .btnarchivos {
            border: 1px solid black;
            background-color: transparent; 
            color: black;
            cursor: pointer; /* Cambia el cursor en forma de mano cuando se pasa por encima*/ 
            padding: 0; /* Añade espacio interno al botón */
            width: 35%; 
            height: 23px;
            font-size: 14px; 
            text-align: center; /* Centra el texto dentro del botón */
            border-radius: 5px;
            font-weight:550;

            }
            .file-input {
                display: none; /* Oculta completamente el input de archivo */
            }
        .btnA{
            display: flex;
            justify-content: center;
        }
        
        .pbtn{
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: 560;
        }
        .desc{
            font-size: 16px;
        }
        .ttlo{
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .success{
            text-align: center;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php">Volver al Dashboard</a>
        <a href="postCreados.php">Post Creados</a>
    </header>

    <div class="caja">
        <div>
            <h2>Área de Administración</h2>
            <p class="desc">Formulario de administración de un post <br>
            asociado al ID  <?php $usuario_id?>, con conexion activa.
            </p>

            <!-- Mostrar el mensaje de éxito si existe -->
            <?php if (!empty($errores['exito'])): ?>
                <p class="success"><?php echo $errores['exito']; ?></p>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <p class="ttlo">Título</p>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>">
                <?php if (!empty($errores['titulo'])): ?>
                    <p class="error"><?php echo $errores['titulo']; ?></p>
                <?php endif; ?>

                <p class="ttlo">Descripción:</p>
                <input id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>">
                <?php if (!empty($errores['descripcion'])): ?>
                    <p class="error"><?php echo $errores['descripcion']; ?></p>
                <?php endif; ?>

                <p class="ttlo">Imagen:</p>
                <div class="btnA">
                    <input type="file" id="imagen" name="imagen" class="file-input" accept="image/*">
                    <button type="button" class="btnarchivos" onclick="document.getElementById('imagen').click()">Elegir archivo</button>
                    <p class="pbtn">No se ha sele...ningún archivo</p>
                </div>
                <?php if (!empty($errores['imagen'])): ?>
                    <p class="error"><?php echo $errores['imagen']; ?></p>
                <?php endif; ?>

                <button type="submit">Crear</button>
            </form>
        </div>
    </div>
   
</body>
</html>