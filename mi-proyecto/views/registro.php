<?php
session_start();
require_once '../inc/conexion.php';
require_once '../inc/funciones.php';

$errores = ['nombre' => '', 'email' => '', 'password' => '', 'exito' => ''];

$nombre = '';
$email = '';
$password = '';
$rol = 'invitado'; // Rol predeterminado
$rutaImagen = null; // Inicializar la variable para la imagen

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar_dato($_POST['nombre']);
    $email = limpiar_dato($_POST['email']);
    $password = $_POST['password'];
    $rol = limpiar_dato($_POST['rol']); // Recibe el rol seleccionado

    // Validaciones
    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El email no es válido.';
    }
    if (strlen($password) < 6) {
        $errores['password'] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    // Verificar si el email ya existe en la base de datos
    $sqlVerificacion = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $stmtVerificacion = $conexion->prepare($sqlVerificacion);
    $stmtVerificacion->bindParam(':email', $email);
    $stmtVerificacion->execute();
    $emailExiste = $stmtVerificacion->fetchColumn();

    if ($emailExiste) {
        $errores['email'] = 'El correo electrónico ya está registrado.';
    }

    // Manejo de la carga de archivos
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $directorioDestino = '../uploads/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo)) {
            // Guardar la URL o la ruta en la base de datos
            $rutaImagen = $nombreArchivo; // Guarda solo el nombre del archivo
        } else {
            $errores['exito'] = 'Error al mover el archivo.';
        }
    } else {
        $rutaImagen = null; // O asigna un valor predeterminado si no hay imagen
    }

    // Si no hay errores, proceder con el registro
    if (empty(array_filter($errores))) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password, rol, url_imagen) VALUES (:nombre, :email, :password, :rol, :url_imagen)";
        $stmt = $conexion->prepare($sql);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':url_imagen', $rutaImagen); // Usar rutaImagen aquí
        
        if ($stmt->execute()) {
            $errores['exito'] = 'Usuario registrado exitosamente.';
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body{
            margin: 0;
        }
        .caja{
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */
            background-color: #f0f0f0; /* Color de fondo opcional */
            background-image: url('/php_curso/mi-proyecto/img/2.jpg'); 
            background-size: cover; /* Ajusta la imagen a la altura del body */
            background-position: center; /* Centra la imagen */

        }


        header{
            display: flex; /* Activa el modo flexbox */
            justify-content: flex-end; /* Alinea horizontalmente el contenido a la derecha */
            align-items: center; /* Centra verticalmente el contenido dentro del header */
            height: 70px;
            background: rgba(0, 0, 0, 0.8); /* Aumenta la opacidad al pasar el cursor */
            color: #00f2fe;
        }
        header:hover {
            background: rgba(0, 0, 0, 0.8); /* Aumenta la opacidad al pasar el cursor */
            color: #00f2fe;
        }

        a{
            padding-right: 20px;
            text-decoration: none; /* Elimina el subrayado del enlace */
            color:#00f2fe ;
            font-size: 27px;
        }
        a:hover {
            color: violet; /* Aumenta la opacidad al pasar el cursor */
            text-shadow: 0 0 10px #e0e0ff, 0 0 20px #e0e0ff, 0 0 30px #e0e0ff, 0 0 40px violet, 0 0 70px violet;
            
        }
        .titulo {
            margin-right: 67%;
            padding: 10px 20px;
            border: 2px solid #6a5acd;
            font-size: 2em;
            color: #6a5acd; /* Color base */
            border-radius: 50%;
            text-align: center;
            background: linear-gradient(135deg, #4facfe, #00f2fe, #6a5acd);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            position: relative;
            transition: color 0.3s ease;
            cursor: pointer;
            text-shadow: 0 0 10px #6a5acd, 0 0 20px #6a5acd, 0 0 30px #6a5acd;
        }
        label{
            font-size: 20px;
            
        }

        form{
            width: 100%;
        }

        h2{
            text-align: center;
        }

        .exito{
            text-align: center;
            color: green;
            font-weight: bold;
        }

        input{
            width: -webkit-fill-available;
        }
        #rol{
            font-weight: bold;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }
        .container {
            font-size: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            width: 100%;
            padding-bottom: 10px;
        }
        form{
            width: 100%;
            margin-top: -40px;
            border: 2px solid #007BFF; /* Borde de 2px de grosor y color azul */
            border-radius: 0px 28px 0px 8px; /* Bordes redondeados */
            padding: 20px; /* Espacio interno del formulario */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3); /* Sombra para resaltar */
            background-color: rgba(128, 128, 128, 0.7); /* Fondo gris con 80% de opacidad */
        }
        /*caja*/
        
        /*rol*/
        .label-container {
            border: 2px solid #d6d6d6;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 10px;
            width: 50%; /* Para ajustar el tamaño del label */
                  
        }
        .label-container2 {
            border: 2px solid #d6d6d6;
            text-align: center;
            font-size: 14px;
            height: 23px;
            font-weight: bold;
            padding: 10px;
            width: 50%; /* Para ajustar el tamaño del label */
            align-items: center;
        }
        .select-container {
            height: 23px;
            margin-left: 20px;
            width: 50%;
            border: 2px solid #d6d6d6; /* Borde para el select */
            padding: 10px;
            text-align: center; /* Centra el contenido dentro del contenedor */
            font-weight: bold;
        }

       /* Estilo para inputs, selects y botones */
        
        .btnarchivos {
            border: 1px solid black;
            background-color: transparent; 
            color: black;
            cursor: pointer; /* Cambia el cursor en forma de mano cuando se pasa por encima*/ 
            padding: 0; /* Añade espacio interno al botón */
            width: 100%; 
            height: 23px;
            font-size: 14px; 
            font-weight: bold; /* Texto en negrita para todos */
            text-align: center; /* Centra el texto dentro del botón */
            border-radius: 5px;

            }
            .file-input {
                display: none; /* Oculta completamente el input de archivo */
            }
            .file-input-label {
                display: inline-block; /* Muestra el texto del label */
                padding: 10px; /* Añade espacio alrededor del texto */
                border: 1px solid #ccc; /* Borde para el label */
                border-radius: 5px; /* Redondea los bordes del label */
                cursor: pointer; /* Cambia el cursor en forma de mano cuando se pasa por encima
                width: 100%; /* Ajusta el ancho del label a la pantalla */
                height: 23px; /* Ajusta la altura del label */
                font-size: 14px; /* Ajusta el tamaño del texto */
                font-weight: bold; /* Texto en negrita para todos */
                text-align: center; /* Centra el texto dentro del label */
                }
                    
    </style>
</head>
<body>
    <header>
        <h1 class="titulo">Mi  Proyecto</h1>
        <a href="../index.php">Index</a>
        <a href="login.php">Login</a>
    </header>

    <div class="caja">
        <form method="post" enctype="multipart/form-data"> <!-- Asegúrate de incluir enctype -->
            <h2>Registro de Usuario</h2>
            <?php if (!empty($errores['exito'])): ?>
                <p class="exito"><?php echo $errores['exito']; ?></p>
            <?php endif; ?>
            
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            <?php if (!empty($errores['nombre'])): ?>
                <p class="error"><?php echo $errores['nombre']; ?></p>
            <?php endif; ?>
        
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (!empty($errores['email'])): ?>
                <p class="error"><?php echo $errores['email']; ?></p>
            <?php endif; ?>
        
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password">
            <?php if (!empty($errores['password'])): ?>
                <p class="error"><?php echo $errores['password']; ?></p>
            <?php endif; ?>

            <div class="container">
                <div class="label-container">
                    Rol:
                </div>
                <div class="select-container">
                    <select id="rol" name="rol">
                        <option value="invitado">Invitado</option>
                        <option value="admin">Administrador</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
            </div>
           
            <div class="container">
                <div class="label-container2">
                    Imagen de perfil:
                </div>
                <div class="select-container">
                    <input type="file" id="imagen" name="imagen" class="file-input" accept="image/*">
                    <button type="button" class="btnarchivos" onclick="uploadFile()">Elegir archivo</button>
                </div>
            </div>

            <button type="submit">Registrar</button>
        </form>
    </div>
    <script>
        document.querySelector('.btnarchivos').addEventListener('click', function() {
            document.querySelector('#imagen').click(); // Simula un clic en el input de archivo oculto
        });
    </script>
</body>
</html>