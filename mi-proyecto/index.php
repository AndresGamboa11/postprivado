<?php
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
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

        .titulo:hover {
            color: #e0e0ff; /* Cambia a un color más claro al hacer hover */
            text-shadow: 0 0 10px #e0e0ff, 0 0 20px #e0e0ff, 0 0 30px #e0e0ff, 0 0 40px violet, 0 0 70px violet;
            background: linear-gradient(135deg, violet, #ff00ff);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            
        }
        h1.Bv {
            font-size: 4em; /* Aumenta el tamaño de la fuente del título */
            animation: fadeIn 2s ease-in-out infinite alternate; /* Animación de aparición */
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .caja {
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */
            background: linear-gradient(135deg, #4facfe, #00f2fe, #6a5acd); /* Degradado azul, violeta y morado */
            color: white; /* Color del texto */
            text-align: center; /* Centra el texto */
            background-image: url('/php_curso/mi-proyecto/img/4.png');
            background-position: center; /* Centra la imagen */
            background-size: cover;
        }

    </style>
</head>
<body>

    <header>
        <h1 class="titulo">Mi  Proyecto</h1>
        <a href="./views/login.php">Login</a>
        <a href="./views/Registro.php">Registrar</a>
    </header>

    <div class="caja">
        <h1 class="Bv">¡Bienvenidos! </h1> <!-- Mensaje de bienvenida -->
    </div>
        
</body>
</html>
