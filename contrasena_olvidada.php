<?php
require_once 'conexion.php'; 

$mensaje_para_usuario = '';
$tipo_mensaje = 'error';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_ingresado = $_POST['email_del_formulario'];

    // Validar el formato del correo electrónico
    if (filter_var($email_ingresado, FILTER_VALIDATE_EMAIL)) {
        // Aquí puedes agregar la lógica para enviar un correo electrónico
        $asunto = "Recuperación de Contraseña";
        $cuerpo = "Hemos recibido una solicitud para recuperar tu contraseña. Si no fuiste tú, ignora este correo.";
        $cabeceras = "From: no-reply@tusitio.com"; // Cambia esto por tu dirección de correo

        // Enviar el correo
        if (mail($email_ingresado, $asunto, $cuerpo, $cabeceras)) {
            $mensaje_para_usuario = "¡Genial! Se ha enviado un correo a " . $email_ingresado . ". Revisa tu bandeja de entrada.";
            $tipo_mensaje = 'exito';

            // Guardar el correo en la base de datos
            $consultaSQL = "INSERT INTO correos_enviados (email) VALUES ('$email_ingresado')";
            try {
                $pdo->query($consultaSQL);
            } catch (PDOException $e) {
                $mensaje_para_usuario = "Error al guardar el correo en la base de datos: " . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        } else {
            $mensaje_para_usuario = "Error: No se pudo enviar el correo. Intenta nuevamente.";
            $tipo_mensaje = 'error';
        }
    } else {
        // Mensaje de error
        $mensaje_para_usuario = "Error: El correo electrónico que ingresaste no es válido. Por favor, verifica el formato.";
        $tipo_mensaje = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contrasena_olvidada.css">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <div class="caja">
        <img class="candado" src="img/candado.jpg" alt="candado">
        
        <h3>¿Olvidaste tu contraseña?</h3>
        <h5>Ingresa tu correo electrónico para que podamos ayudarte a recuperarla.</h5>
        
        <form action="" method="POST">
            <input class="rellenar" type="text" name="email_del_formulario" value="" placeholder="Tu dirección de correo" required>
            <input class="button" type="submit" name="boton_enviar" value="Enviar">
        </form>

        <?php
        if (!empty($mensaje_para_usuario)) {
            $clase_mensaje = ($tipo_mensaje == 'exito') ? 'mensaje-exito' : 'mensaje-error';
            echo "<p class='" . $clase_mensaje . "'>" . $mensaje_para_usuario . "</p>";
        }
        ?>
    </div>
</body>
</html>
