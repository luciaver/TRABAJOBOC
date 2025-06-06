<?php
session_start();

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre_bocadillo"] ?? '';
    $precio = $_POST["precio"] ?? null;
    $accion = $_POST["accion"] ?? '';

    if ($accion === 'pedir' && $nombre && is_numeric($precio)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO pedidos (nombre_bocadillo, precio, estado, fecha_pedido) VALUES (:nombre, :precio, 'activo', NOW())");
            $stmt->execute([
                ':nombre' => $nombre,
                ':precio' => $precio
            ]);
            echo "<p>Pedido realizado de <strong>$nombre</strong> (Precio: $precio €) a las <strong>" . date("H:i") . "</strong> del día <strong>" . date("d/m/Y") . "</strong></p>";
        } catch (PDOException $e) {
            echo "Error al hacer el pedido: " . $e->getMessage();
        }

    } elseif ($accion === 'cancelar' && $nombre) {
        try {
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado', fecha_cancelado = NOW() WHERE nombre_bocadillo = :nombre AND estado = 'activo' ORDER BY id DESC LIMIT 1");
            $stmt->execute([':nombre' => $nombre]);

            if ($stmt->rowCount()) {
                echo "<p>Pedido de <strong>$nombre</strong> cancelado a las <strong>" . date("H:i") . "</strong> del día <strong>" . date("d/m/Y") . "</strong></p>";
            } else {
                echo "<p>No hay pedidos activos de <strong>$nombre</strong> para cancelar.</p>";
            }
        } catch (PDOException $e) {
            echo "Error al cancelar: " . $e->getMessage();
        }

    } else {
        echo "Acción o datos inválidos.";
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

  <!--  ENCABEZADO -->
  <header class="encabezado">
    <span class="nombre-usuario"><?php echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'Usuario'; ?></span>
    <div class="horario">HORARIO<br />8:00 - 10:00</div>
    <img src="img/login-logo.png" alt="Logo EFA" class="logo" />
  </header>

  <!--  CONTENIDO PRINCIPAL -->
  <main class="contenido-principal">
    <h2>Elige tu bocadillo</h2>

    <section class="grid-bocadillos">

      <!--  Bocadillo caliente -->
      <article class="tarjeta-bocadillo">
        <h3>Bocadillo Caliente de tortilla</h3>
        <img
          src="img/bocadillo-tortilla-francesa-lablugo-cangas-del-narcea.jpg"
          alt="Bocadillo de Tortilla"
          class="imagen-bocadillo"
        />
        <p class="descripcion-bocadillo">
         ¡Un clásico reconfortante: esponjosa tortilla de patatas recién hecha, servida en pan tierno o crujiente.
        Ideal para un desayuno energético o una merienda sustanciosa, siempre un acierto para cualquier momento del día.
        </p>
        <p><strong>Ingredientes:</strong> Pan, Huevo, Patata, Aceite</p>
        <p>
          <strong>Alérgenos:</strong>
          <img src="img/gluten.png" alt="Gluten" title="Gluten" class="icono-alergeno" />
          <img src="img/lactosa.png" alt="Lactosa" title="Lactosa" class="icono-alergeno" />
          <img src="img/huevo.png" alt="Huevo" title="Huevo" class="icono-alergeno" />
        </p>
        <p class="precio">Precio: 2 €</p>

        <div class="contenedor-botones">
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo Caliente de tortilla">
            <input type="hidden" name="precio" value="2">
            <input type="hidden" name="accion" value="pedir">
            <button type="submit" class="boton boton-pedir">Pedir</button>
          </form>
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo Caliente de tortilla">
            <input type="hidden" name="accion" value="cancelar">
            <button type="submit" class="boton boton-cancelar">Cancelar</button>
          </form>
        </div>
      </article>

      <!--  Bocadillo frío -->
      <article class="tarjeta-bocadillo">
        <h3>Bocadillo frío de Jamón Serrano </h3>
        <img
          src="img/sandwich-jamon-serrano-espanol-aislado-sobre-fondo-blanco_123827-23115 (1).jpg"
          alt="Bocadillo de Jamón Serrano"
          class="imagen-bocadillo"
        />
        <p class="descripcion-bocadillo">
         Disfruta de la sencillez y el sabor intenso del jamón serrano curado en pan crujiente, una delicia clásica.
        Perfecto para un almuerzo rápido o una cena ligera, realzado con un toque de aceite de oliva virgen extra.
        </p>
        <p><strong>Ingredientes:</strong> Pan, Jamón Serrano, Aceite</p>
        <p>
          <strong>Alérgenos:</strong>
          <img src="img/gluten.png" alt="Gluten" title="Gluten" class="icono-alergeno" />
        </p>
        <p class="precio">Precio: 2 €</p>

        <div class="contenedor-botones">
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo frío de Jamón Serrano">
            <input type="hidden" name="precio" value="2">
            <input type="hidden" name="accion" value="pedir">
            <button type="submit" class="boton boton-pedir">Pedir</button>
          </form>
          <form method="POST">
            <input type="hidden" name="nombre_bocadillo" value="Bocadillo frío de Jamón Serrano">
            <input type="hidden" name="accion" value="cancelar">
            <button type="submit" class="boton boton-cancelar">Cancelar</button>
          </form>
        </div>
      </article>

    </section>
  </main>

</body>
</html>
