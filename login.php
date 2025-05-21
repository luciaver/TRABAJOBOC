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
    ?>

    <div id="base-login">

        <div id="base-logo">
            <img src="img/login-logo.png" id="logo">
        </div>

    <form action="" method="get" id="formulario">
        
        <label for="Correo">Correo electrónico</label>
        
        <div class="formulario-registros">
            <input type="text" class="input-registro" name="Correo" placeholder="ejemplo@elcampico.com" value="<?php $email ?>">
        </div>
        
        <label for="password">Contraseña</label>
        
        <div class="formulario-registros">
            <input type="password" class="input-registro" name="password" value="<?php $passg ?>">
        </div>  
       
        <button type="submit" name="acceder" id="form-submit">Acceder</button>
    
    </form>
    <a href="contrasena_olvidada.html">¿Olvidó su contraseña?</a>
    </div>

    <?php
    
    $email = $_POST['email'] ? $_POST['email']:null;
    $passg = $_POST['passg'] ? $_POST['passg']:null;

    $stmt = $pdo->prepare("SELECT * FROM usuario");

    try {
        while ($row = $stmt->fetch()){
            if(isset($_POST['email'])) {
                if(isset($_POST['passg'])){
                    
                }
            }
        }
    } catch(PDOException $e){
        echo $e->getMessage();
    }

    
    ?>

</body>
</html>