<?php
require_once 'conexion.php';
// Configuración
$bocadillos_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $bocadillos_por_pagina;

// Inicializar mensaje
$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        // Validar que el id sea numérico para seguridad
        $id = (int)$id;
        if (isset($_POST['modificar'])) {
            // Recoger y sanitizar datos
            $nombre = trim($_POST['nombre_' . $id]);
            $alergenos = trim($_POST['alergenos_' . $id]);
            $ingredientes = trim($_POST['ingredientes_' . $id]);
            $estado = $_POST['estado_' . $id];
            $coste = $_POST['coste_' . $id];

            // Validaciones simples
            if ($nombre === '' || !is_numeric($coste) || ($estado !== 'Caliente' && $estado !== 'Frío')) {
                $mensaje = "<p class='mensaje error'>Datos inválidos para modificar el bocadillo.</p>";
            } else {
                $upd = $pdo->prepare("UPDATE bocadillos SET nombre=?, alergenos=?, ingredientes=?, estado=?, coste=? WHERE id=?");
                if ($upd->execute([$nombre, $alergenos, $ingredientes, $estado, $coste, $id])) {
                    $mensaje = "<p class='mensaje exito'>Bocadillo modificado con éxito.</p>";
                } else {
                    $mensaje = "<p class='mensaje error'>Error al modificar el bocadillo. Por favor, inténtelo de nuevo.</p>";
                }
            }
        }
        if (isset($_POST['eliminar'])) {
            $del = $pdo->prepare("DELETE FROM bocadillos WHERE id=?");
            if ($del->execute([$id])) {
                $mensaje = "<p class='mensaje exito'>Bocadillo eliminado con éxito.</p>";
            } else {
                $mensaje = "<p class='mensaje error'>Error al eliminar el bocadillo. Por favor, inténtelo de nuevo.</p>";
            }
        }
    } else {
        $mensaje = "<p class='mensaje error'>ID de bocadillo no válido.</p>";
    }
}

// Obtener total y páginas después de posible modificación para mostrar datos actualizados
$total_bocadillos = $pdo->query("SELECT COUNT(*) FROM bocadillos")->fetchColumn();
$total_paginas = ceil($total_bocadillos / $bocadillos_por_pagina);

// Obtener bocadillos de la página actual
$sql = "SELECT id, nombre, coste, estado, alergenos, ingredientes 
        FROM bocadillos ORDER BY id ASC 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $bocadillos_por_pagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$bocadillos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Bocadillos</title>
    <link rel="stylesheet" href="listado_alumnos.css" />
</head>

<body>
    <main class="contenedor">
        <h1 class="titulo">Gestión de Bocadillos</h1>
        <div class="contenedor-boton-crear">
    <a href="admin_cocina.php" class="btn-crear-bocadillo">➕ Añadir Bocadillos</a>
</div>

        <?php if ($mensaje): ?>
            <div class="mensaje-container">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
        <form method="post" novalidate>
            <table class="tabla-bocadillos" aria-label="Lista de bocadillos con opciones">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Coste (€)</th>
                        <th>Estado</th>
                        <th>Alérgenos</th>
                        <th>Ingredientes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bocadillos as $b) { ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($b['id']) ?></td>
                            <td data-label="Nombre"><input type="text" name="nombre_<?= $b['id'] ?>" value="<?= htmlspecialchars($b['nombre']) ?>" required></td>
                            <td data-label="Coste (€)"><input type="number" step="0.01" name="coste_<?= $b['id'] ?>" value="<?= htmlspecialchars($b['coste']) ?>" required></td>
                            <td data-label="Estado">
                                <select name="estado_<?= $b['id'] ?>" required>
                                    <option value="Caliente" <?= $b['estado'] === 'Caliente' ? 'selected' : '' ?>>Caliente</option>
                                    <option value="Frío" <?= $b['estado'] === 'Frío' ? 'selected' : '' ?>>Frío</option>
                                </select>
                            </td>
                            <td data-label="Alérgenos"><input type="text" name="alergenos_<?= $b['id'] ?>" value="<?= htmlspecialchars($b['alergenos']) ?>"></td>
                            <td data-label="Ingredientes"><input type="text" name="ingredientes_<?= $b['id'] ?>" value="<?= htmlspecialchars($b['ingredientes']) ?>"></td>
                            <td class="acciones" data-label="Acciones">
                                <button type="submit" name="modificar" value="1" class="btn-verde" onclick="document.querySelector('input[name=id]').value='<?= $b['id']?>'">Modificar</button>
                                <button type="submit" name="eliminar" value="1" class="btn-rojo" onclick="if(confirm('¿Eliminar este bocadillo?')) { document.querySelector('input[name=id]').value='<?= $b['id']?>'; return true; } else { return false; }">Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- Campo id oculto para enviar el id correcto al backend -->
            <input type="hidden" name="id" value="" />
        </form>
        <nav class="paginacion" aria-label="Paginación bocadillos">
            <?php
            if ($total_paginas > 1) {
                if ($pagina_actual > 1) {
                    echo '<a href="?pagina=1" class="pagina-enlace">« Primero</a>';
                    echo '<a href="?pagina=' . ($pagina_actual - 1) . '" class="pagina-enlace">Anterior</a>';
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    $activo = $i == $pagina_actual ? 'pagina-activa' : '';
                    echo '<a href="?pagina=' . $i . '" class="pagina-enlace ' . $activo . '">' . $i . '</a>';
                }
                if ($pagina_actual < $total_paginas) {
                    echo '<a href="?pagina=' . ($pagina_actual + 1) . '" class="pagina-enlace">Siguiente</a>';
                    echo '<a href="?pagina=' . $total_paginas . '" class="pagina-enlace">Último »</a>';
                }
            }
            ?>
        </nav>
    </main>
</body>

</html>

