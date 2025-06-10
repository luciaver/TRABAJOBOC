<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CREAR bocadillo

    if (isset($_POST['crear'])) {
        $nombreBocadillo = $_POST['nombre'];
        $alergenosBocadillo = $_POST['alergenos'];
        $ingredientesBocadillo = $_POST['ingredientes'];
        $estadoBocadillo = $_POST['estado'];
        $costeBocadillo = $_POST['coste'];
      
     $consultaSQL = "INSERT INTO bocadillos (nombre, alergenos, ingredientes, estado, coste)  VALUES (?, ?, ?, ?, ?)";
     $stmt = $pdo->prepare($consultaSQL);
     $resultado = $stmt->execute([$nombreBocadillo, $alergenosBocadillo, $ingredientesBocadillo, $estadoBocadillo, $costeBocadillo]);  

        if ($resultado) {
            echo "Bocadillo creado con éxito.";
        } else {
            echo "Error al crear bocadillo.";
        }
    }
    // MODIFICAR bocadillo

    if (isset($_POST['modificar'])) {
        $nombreBocadillo = $_POST['nombre'];
        $alergenosBocadillo = $_POST['alergenos'];
        $ingredientesBocadillo = $_POST['ingredientes'];
        $estadoBocadillo = $_POST['estado'];
        $costeBocadillo = $_POST['coste'];
          $consultaSQL = "UPDATE bocadillos SET alergenos = ?, ingredientes = ?, estado = ?, coste = ? WHERE nombre = ?";
        $stmt = $pdo->prepare($consultaSQL);
        $resultado = $stmt->execute([$alergenosBocadillo, $ingredientesBocadillo, $estadoBocadillo, $costeBocadillo, $nombreBocadillo]);
        echo $resultado ? "Bocadillo modificado con éxito." : "Error al modificar bocadillo.";
    }

    // ELIMINAR bocadilloo
    if (isset($_POST['eliminar'])) {
        $nombreBocadillo = $_POST['nombre'];
        $consultaSQL = "DELETE FROM bocadillos WHERE nombre = ?";
        $stmt = $pdo->prepare($consultaSQL);
        $resultado = $stmt->execute([$nombreBocadillo]);
        echo $resultado ? "✅ Bocadillo eliminado con éxito." : "❌ Error al eliminar bocadillo.";
    }
    

    // SOLICITAR bocadillo

 if (isset($_POST['solicitar'])) {
    $nombreAlumno = $_POST['nombre_alumno'];
    $curso = $_POST['curso'];
    $nombreBocadilloSolicitado = $_POST['bocadillo'];

    // Buscar email del alumno
    $stmt = $pdo->prepare("SELECT email FROM alumno WHERE nombre = ? AND curso = ?");
    $stmt->execute([$nombreAlumno, $curso]);
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alumno) {
        $emailAlumno = $alumno['email'];

        // Buscar precio del bocadillo
        $stmt = $pdo->prepare("SELECT coste FROM bocadillos WHERE nombre = ?");
        $stmt->execute([$nombreBocadilloSolicitado]);
        $bocadillo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bocadillo) {
            $precio = $bocadillo['coste'];

            // Insertar pedido
            $stmtInsert = $pdo->prepare("INSERT INTO pedidos (nombre_bocadillo, precio, estado, email_alumno) VALUES (?, ?, 'NO RETIRADO', ?)");
            $resultado = $stmtInsert->execute([$nombreBocadilloSolicitado, $precio, $emailAlumno]);

            echo $resultado ? "✅ Pedido realizado con éxito." : "❌ Error al registrar el pedido.";
        } else {
            echo "❌ Bocadillo no encontrado.";
        }
    } else {
        echo "❌ Alumno no encontrado con ese nombre y curso.";
    }
}
   }


        
    

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="admin_cocina.css" />
    <title>Administrador</title>
</head>
<body>
    <header>
        <div id="div_user">
            <h2 id="nombre_user">Administrador</h2>
        </div>
        <nav>
            <a href="admin_cocina.php">Bocatas</a>
        </nav>
        <div id="div_logo">
            <img src="img/login-logo.png" id="img_logo" />
        </div>
    </header>
    <section class="container">
        <!-- CREAR BOCADILLO -->
        <div class="section">
            <form method="POST">
                <h3>Crear bocadillo</h3>
                <input type="text" name="nombre" placeholder="Nombre del bocadillo" required />
                <textarea name="alergenos" placeholder="Alérgenos" required></textarea>
                <textarea name="ingredientes" placeholder="Ingredientes" required></textarea>
                <select name="estado" required>
                    <option value="caliente">Caliente</option>
                    <option value="frio">Frío</option>
                </select>
                <input type="number" name="coste" placeholder="coste" required />
                <button type="submit" name="crear">Crear bocadillo</button>
            </form>
        </div>

        <!-- MODIFICAR BOCADILLO -->
        <div class="section">
            <form method="POST">
                <h3>Modificar bocadillo</h3>
                <input type="text" name="nombre" placeholder="Nombre" required />
                <textarea name="alergenos" placeholder="Alérgenos" required></textarea>
                <textarea name="ingredientes" placeholder="Ingredientes" required></textarea>
                <select name="estado" required>
                    <option value="caliente">Caliente</option>
                    <option value="frio">Frío</option>
                </select>
                <input type="number" name="coste" placeholder="coste" step="0.01" required />
                <button type="submit" name="modificar">Modificar bocadillo</button>
            </form>
        </div>


        <!-- SOLICITAR BOCADILLO -->
        <div class="section">
            <form method="POST">
                <h3>Solicitud de bocadillo</h3>
            <input type="text" name="nombre_alumno" placeholder="Nombre completo del alumno" required />
            <select name="curso" required>
            <option value="1ºESO">1ºESO</option>
            <option value="2ºESO">2ºESO</option>
            <option value="3ºESO">3ºESO</option>
            <option value="4ºESO">4ºESO</option>
            <option value="Grado Medio 1º año">Grado Medio 1º año</option>
            <option value="Grado Medio 2º año">Grado Medio 2º año</option>
        </select>

                <select name="bocadillo" required>
                    <option value="Tomatito">Tomatito</option>
                    <option value="Tortilla">Tortilla</option>
                    <option value="Salchichon">Salchichón</option>
                    <option value="Jamoncin">Jamoncín</option>
                </select>
                <select name="estado" required>
                    <option value="Frio">Frío</option>
                    <option value="Caliente">Caliente</option>
                </select>
                <button type="submit" name="solicitar">Solicitar bocadillo</button>
            </form>
        </div>
        <!-- ELIMINAR BOCADILLO -->
        <div class="section">
            <form method="POST">
                <h3>Eliminar Bocadillo</h3>
                <input type="text" name="nombre" placeholder="Nombre del bocadillo a eliminar" required />
                 <button type="submit" name="eliminar">Eliminar Bocadillo</button>
            </form>
        </div>
    </section>
</body>
</html>