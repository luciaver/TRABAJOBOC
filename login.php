<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>login</title>
</head>
<body>

    <?php
    require_once "conexion.php";
    $email = isset($_POST['correo']) ? $_POST['correo']:null;
    $passg = isset($_POST['password']) ? $_POST['password']:null;

    $rol = null;
    ?>

    <div id="base-login">

        <div id="base-logo">
            <img src="img/login-logo.png" id="logo">
        </div>

    <form action="" method="post" id="formulario">
        
        <label for="correo">Correo electrónico</label>
        
        <div class="formulario-registros">
            <input type="text" class="input-registro" name="correo" placeholder="ejemplo@elcampico.com" value="<?php echo $email ?>" required>
        </div>
        
        <label for="password">Contraseña</label>
        
        <div class="formulario-registros">
            <input type="password" class="input-registro" name="password" value="<?php echo $passg ?>" require>
        </div>  
       
        <button type="submit" name="acceder" id="form-submit">Acceder</button>
    
    </form>
    <a href="contrasena_olvidada.html">¿Olvidó su contraseña?</a>
    </div>

    <?php

    $stmt = $pdo->prepare("SELECT * FROM usuario where email = '$email' and PASSWORD = '$passg'");

    if ($stmt != null && $email != null && $passg != null){
        if(isset($_POST['rol'])) {
            $rol = $_POST['rol'];
        } else {
            $rol = null;
        }
        if ($rol == "Alumno"){
            header("Location: inicio_user.html");
        }else if($rol == "Cocina"){
            header("Location: cocina.html");
        }else if($rol == "Admin"){
            header("Location: admin_usuario.html");
        }else{ 
            echo "<p>Usuario o contraseña mal escrita</p>";
        }
    }else{
        echo "<p>Usuario o contraseña mal escrita</p>";
    }

    ?>

</body>
</html>