<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['Nombre_apellidos_usuario']);
    $curso = trim($_POST['curso']);
    $email = trim($_POST['email']); 
    $contrasena = $_POST['contrasena'];
    $repetir = $_POST['repetir_contrasena'];

    if ($contrasena !== $repetir) {
        die("Error: Las contraseñas no coinciden.");
    }

    // Encriptar la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        // Insertar en tabla usuario
        $sqlUsuario = "INSERT INTO usuario (email, PASSWORD, rol) VALUES (?, ?, 'Alumno')";
        $stmtUsuario = $pdo->prepare($sqlUsuario);
        $stmtUsuario->execute([$email, $contrasena_hash]);

        // Insertar en tabla alumno
        $sqlAlumno = "INSERT INTO alumno (email, nombre, curso, alta) VALUES (?, ?, ?, 'true')";
        $stmtAlumno = $pdo->prepare($sqlAlumno);
        $stmtAlumno->execute([$email, $nombre, $curso]);

        echo "Alumno creado correctamente.";
    } catch (PDOException $e) {
        echo "Error de base de datos: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Alumno</title>
    <link rel="stylesheet" href="user_admin.css">
</head>
<body>
    <header>
        <div id="div_user">
            <h2 id="nombre_user">Administrador</h2>
        </div>

        <nav>
            <ul>
                <li><a href="admin_usuarios.html">Usuarios</a></li>
                <li><a href="admin_cocina.html">Bocatas</a></li>
            </ul>
        </nav>

        <div id="div_logo">
            <img src="img/login-logo.png" id="img_logo">
        </div>
    </header>

    <section id="section_crearModificarUser ">
        <article class="article_crearModificarUser ">
            <div id="div_titulo">
                <h2>AÑADIR USUARIO</h2>
            </div>

            <div class="div_formulario">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="formulario_div">
                        <label class="label_crear_modificar">Nombre y apellidos del usuario:</label>
                        <div class="div_input_crear">
                            <input type="text" name="Nombre_apellidos_usuario" placeholder="Daniel Pamies Teruel" class="input_crear" required>
                        </div>
                    </div>

                    <div class="formulario_div" id="curso_crear">
                        <label class="label_crear_modificar">Curso:</label>
                        <select name="curso" class="seleccion" required>
                            <option value="1ºESO">1ºESO</option>
                            <option value="2ºESO">2ºESO</option>
                            <option value="3ºESO">3ºESO</option>
                            <option value="4ºESO">4ºESO</option>
                            <option value="Grado Medio 1º año">Grado Medio 1º año</option>
                            <option value="Grado Medio 2º año">Grado Medio 2º año</option>
                        </select>
                    </div>

                    <div class="formulario_div">
                        <label class="label_crear_modificar">Email:</label> <!-- Cambiado de 'emai' a 'email' -->
                        <div class="div_input_crear">
                            <input type="email" name="email" placeholder="daniel@elcampico.com" class="input_crear" required>
                        </div>
                    </div>

                    <div class="formulario_div">
                        <label class="label_crear_modificar">Contraseña:</label>
                        <div class="div_input_crear">
                            <input type="password" name="contrasena" placeholder="pepito_123" class="input_crear" required>
                        </div>
                    </div>

                    <div class="formulario_div">
                        <label class="label_crear_modificar">Repetir la contraseña:</label>
                        <div class="div_input_crear">
                            <input type="password" name="repetir_contrasena" placeholder="pepito_123" class="input_crear" required>
                        </div>
                    </div>

                    <div id="div_boton_crear">
                        <button type="submit" name="crear" class="boton_crear_modificar">Crear usuario</button>
                    </div>
                </form>
            </div>
        </article>
    </section>
</body>
</html>
