<?php
session_start();
require_once 'conexion.php';

// Inicializa estado si no existe
if (!isset($_SESSION['pedido_activo'])) {
    $_SESSION['pedido_activo'] = null;
}

$mensaje = '';
$clase_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre_bocadillo"] ?? '';
    $precio = $_POST["precio"] ?? null;
    $accion = $_POST["accion"] ?? '';

    if ($accion === 'pedir' && $nombre && is_numeric($precio) && $_SESSION['pedido_activo'] === null) {
        try {
            $stmt = $pdo->prepare("INSERT INTO pedidos (nombre_bocadillo, precio, estado, fecha_pedido) VALUES (:nombre, :precio, 'activo', NOW())");
            $stmt->execute([
                ':nombre' => $nombre,
                ':precio' => $precio
            ]);
            $_SESSION['pedido_activo'] = $nombre;
            $mensaje = "Pedido realizado de $nombre (Precio: $precio €)";
            $clase_mensaje = 'exito';
        } catch (PDOException $e) {
            $mensaje = "Error al hacer el pedido: " . $e->getMessage();
            $clase_mensaje = 'error';
        }

    } elseif ($accion === 'cancelar' && $nombre) {
        try {
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado', fecha_cancelado = NOW() WHERE nombre_bocadillo = :nombre AND estado = 'activo' ORDER BY id DESC LIMIT 1");
            $stmt->execute([':nombre' => $nombre]);

            if ($stmt->rowCount()) {
                $_SESSION['pedido_activo'] = null;
                $mensaje = "Pedido de $nombre cancelado correctamente.";
                $clase_mensaje = 'error';
            } else {
                $mensaje = "No hay pedidos activos de $nombre para cancelar.";
                $clase_mensaje = 'error';
            }
        } catch (PDOException $e) {
            $mensaje = "Error al cancelar: " . $e->getMessage();
            $clase_mensaje = 'error';
        }

    } else {
        $mensaje = "Acción no válida o ya tienes un pedido activo.";
        $clase_mensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bocadillos</title>
  <link rel="stylesheet" href="bocadillos.css" />
</head>
<body>

<header class="encabezado">
  <span class="nombre-usuario"><?php echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'Usuario'; ?></span>
  <div class="horario">HORARIO<br />8:00 - 10:00</div>
  <img src="img/login-logo.png" alt="Logo EFA" class="logo" />
</header>

<main class="contenido-principal">
  <h2>Elige tu bocadillo</h2>

  <?php if ($mensaje): ?>
    <p class="mensaje <?php echo $clase_mensaje; ?>"><?php echo $mensaje; ?></p>
  <?php endif; ?>

  <?php $pedido_activo = $_SESSION['pedido_activo'] ?? null; ?>

  <section class="grid-bocadillos">

    <!-- Bocadillo Caliente -->
    <article class="tarjeta-bocadillo">
      <h3>Bocadillo Caliente de tortilla</h3>
      <img src="img/bocadillo-tortilla-francesa-lablugo-cangas-del-narcea.jpg" alt="Bocadillo de Tortilla" class="imagen-bocadillo" />
      <p>Ingredientes: Pan, Huevo, Patata, Aceite</p>
      <p>Alérgenos: Gluten, Lactosa, Huevo</p>
      <p>Precio: 2 €</p>

      <div class="contenedor-botones">
        <form method="POST">
          <input type="hidden" name="nombre_bocadillo" value="Bocadillo Caliente de tortilla">
          <input type="hidden" name="precio" value="2">
          <input type="hidden" name="accion" value="pedir">
          <button class="boton boton-pedir" type="submit" <?php echo $pedido_activo ? 'disabled' : ''; ?>>Pedir</button>
        </form>

        <?php if ($pedido_activo === 'Bocadillo Caliente de tortilla'): ?>
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo Caliente de tortilla">
            <input type="hidden" name="accion" value="cancelar">
            <button class="boton boton-cancelar" type="submit">Cancelar</button>
          </form>
        <?php endif; ?>
      </div>
    </article>

    <!-- Bocadillo Frío -->
    <article class="tarjeta-bocadillo">
      <h3>Bocadillo frío de Jamón Serrano</h3>
      <img src="img/sandwich-jamon-serrano-espanol-aislado-sobre-fondo-blanco_123827-23115 (1).jpg" alt="Bocadillo de Jamón Serrano" class="imagen-bocadillo" />
      <p>Ingredientes: Pan, Jamón Serrano, Aceite</p>
      <p>Alérgenos: Gluten</p>
      <p>Precio: 2 €</p>

      <div class="contenedor-botones">
        <form method="POST">
          <input type="hidden" name="nombre_bocadillo" value="Bocadillo frío de Jamón Serrano">
          <input type="hidden" name="precio" value="2">
          <input type="hidden" name="accion" value="pedir">
          <button class="boton boton-pedir" type="submit" <?php echo $pedido_activo ? 'disabled' : ''; ?>>Pedir</button>
        </form>

        <?php if ($pedido_activo === 'Bocadillo frío de Jamón Serrano'): ?>
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo frío de Jamón Serrano">
            <input type="hidden" name="accion" value="cancelar">
            <button class="boton boton-cancelar" type="submit">Cancelar</button>
          </form>
        <?php endif; ?>
      </div>
    </article>

  </section>
</main>

</body>
</html>