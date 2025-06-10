<?php
require_once 'conexion.php';
// Configuración
$bocadillos_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $bocadillos_por_pagina;
// Obtener total y páginas
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

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        if (isset($_POST['modificar'])) {
            $nombre = $_POST['nombre_' . $id];
            $alergenos = $_POST['alergenos_' . $id];
            $ingredientes = $_POST['ingredientes_' . $id];
            $estado = $_POST['estado_' . $id];
            $coste = $_POST['coste_' . $id];
                        $estado = $_POST['estado_' . $id];
            $coste = $_POST['coste_' . $id];
            $upd = $pdo->prepare("UPDATE bocadillos SET nombre=?, alergenos=?, ingredientes=?, estado=?, coste=? WHERE id=?");
            if ($upd->execute([$nombre, $alergenos, $ingredientes, $estado, $coste, $id])) {
                echo "<p class='mensaje exito'>Bocadillo modificado.</p>";
                header("Refresh:1; url=?pagina=$pagina_actual");
                exit;
            } else {
                echo "<p class='mensaje error'>Error al modificar.</p>";
            }
        }
        if (isset($_POST['eliminar'])) {
            $del = $pdo->prepare("DELETE FROM bocadillos WHERE id=?");
            if ($del->execute([$id])) {
                echo "<p class='mensaje exito'>Bocadillo eliminado.</p>";
                header("Refresh:1; url=?pagina=$pagina_actual");
                exit;
            } else {
                echo "<p class='mensaje error'>Error al eliminar.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="listado_alumnos.css" />
    
</head>
<body>
    <main class="contenedor">
    <h1 class="titulo">Gestión de Bocadillos</h1>
    <form method="post">
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
                    <td><?= $b['id'] ?></td>
                    <td><input type="text" name="nombre_<?= $b['id'] ?>" value="<?= $b['nombre'] ?>" required></td>
                    <td><input type="number" step="0.01" name="coste_<?= $b['id'] ?>" value="<?= $b['coste'] ?>" required></td>
                    <td>
                        <select name="estado_<?= $b['id'] ?>" required>
                            <option value="Caliente" <?= $b['estado'] == 'Caliente' ? 'selected' : '' ?>>Caliente</option>
                            <option value="Frío" <?= $b['estado'] == 'Frío' ? 'selected' : '' ?>>Frío</option>
                        </select>
                    </td>
                    <td><input type="text" name="alergenos_<?= $b['id'] ?>" value="<?= $b['alergenos'] ?>"></td>
                    <td><input type="text" name="ingredientes_<?= $b['id'] ?>" value="<?= $b['ingredientes'] ?>"></td>
                    <td class="acciones">
                        <button type="submit" name="modificar" value="<?= $b['id'] ?>" class="btn-verde">Modificar</button>
                        <button type="submit" name="eliminar" value="<?= $b['id'] ?>" class="btn-rojo" onclick="return confirm('¿Eliminar este bocadillo?');">Eliminar</button>
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
    <nav class="paginacion" aria-label="Paginación bocadillos">
        <?php
        if ($total_paginas > 1) {
            if ($pagina_actual > 1) {
                echo '<a href="?pagina=1" class="pagina-enlace">« Primero</a>';
                echo '<a href="?pagina='.($pagina_actual-1).'" class="pagina-enlace">Anterior</a>';
            }
            for ($i = 1; $i <= $total_paginas; $i++) {
                $activo = $i == $pagina_actual ? 'pagina-activa' : '';
                echo '<a href="?pagina='.$i.'" class="pagina-enlace '.$activo.'">'.$i.'</a>';
            }
 if ($pagina_actual < $total_paginas) {
                echo '<a href="?pagina='.($pagina_actual+1).'" class="pagina-enlace">Siguiente</a>';
                echo '<a href="?pagina='.$total_paginas.'" class="pagina-enlace">Último »</a>';
            }
        }
        ?>
    </nav>
</main>
</body>
</html>