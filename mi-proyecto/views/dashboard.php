<?php
 session_start();
require_once '../inc/funciones.php';

if (!verificar_rol('admin')) {
    echo "Acceso denegado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../css/estilosDashboard.css">
    <style>
     
    </style>
</head>
<body>
    
    <header>
        <a href="admin.php">Administración</a>
        <a href="login.php">Cerrar Sesión</a>
    </header>

    <div class="caja">
        <div>
            <p> Bienvenido: <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p>rol: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>

            <div class="imagen">
                <img class="img" src="/uploads/<?php echo htmlspecialchars($_SESSION['user_imagen']); ?>" alt="Imagen de usuario" width="300px" height="300px">
            </div>

        </div>
    </div>
</body>
</html>