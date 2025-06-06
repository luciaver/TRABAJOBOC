<?php
session_start();

require_once 'conexion.php';

$mensaje = "";
$clase = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contraseña'];

    try {
        $stmt = $pdo->prepare("SELECT email, PASSWORD, rol FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $usuario);
        $stmt->execute();

        $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioDB && $contrasena === $usuarioDB['PASSWORD']) {
            $_SESSION['usuario'] = $usuarioDB['email'];
            $_SESSION['rol'] = $usuarioDB['rol'];
            
            if ($usuarioDB['rol'] === 'Admin') {
                header('Location: admin_cocina.php');
                exit();
            } elseif ($usuarioDB['rol'] === 'Cocina') {
                header('Location: listado_cocina.php');
                exit();
            } elseif ($usuarioDB['rol'] === 'Alumno') {
                header('Location: bocadillos.php');
                exit();
            } else {
                header('Location: welcome.php');
                exit();
            }
        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
            $clase = "error";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al intentar iniciar sesión: " . $e->getMessage();
        $clase = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Formulario Login</title>
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <section class="formulario-login">
        <img src="img/candado.jpg" alt="Icono de Candado" class="icono-candado" />
        <h5>Iniciar Sesión</h5>
        <form method="POST" action="login.php">
            <input class="controladores" type="text" name="usuario" placeholder="Ingresa tu usuario" required />
            <input class="controladores" type="password" name="contraseña" placeholder="Ingresa tu contraseña" required />
            <input class="botones" type="submit" value="Entrar" />
        </form>
        <?php
        if ($mensaje != "") {
            echo '<p class="mensaje ' . $clase . '">' . $mensaje . '</p>';
        }
        ?>
        <p><a href="#">¿Olvidaste tu contraseña?</a></p>
    </section>
</body>

</html>