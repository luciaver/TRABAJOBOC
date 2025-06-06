<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listado de Bocadillos - Paginador Simple</title>
    <link rel="stylesheet" href="listado_cocina.css" />
</head>
<body>
   
    <header class="encabezado">
        <span class="nombre-usuario"><?php echo isset($_SESSION['nombre_completo']) ?($_SESSION['nombre_completo']) : 'Usuario'; ?></span>
        <div class="horario">HORARIO<br />8:00 - 10:00</div>
        <img src="img/login-logo.png" alt="Logo EFA" class="logo" />
    </header>

    <main class="contenedor" role="main">
        <h1>Listado de Bocadillos</h1>
        <?php
        // Lista de 30 bocadillos
        $bocadillos = [
            "Bocadillo de Jamón y Queso",
            "Bocadillo de Pollo al Ajillo",
            "Bocadillo de Atún con Tomate",
            "Bocadillo de Tortilla Española",
            "Bocadillo Vegetal con Aguacate",
            "Bocadillo de Lomo con Pimientos",
            "Bocadillo de Chorizo Ibérico",
            "Bocadillo de Queso Manchego",
            "Bocadillo de Pechuga de Pavo",
            "Bocadillo de Salchichón",
            "Bocadillo de Calamares Fritos",
            "Bocadillo de Albóndigas",
            "Bocadillo de Jamón Cocido",
            "Bocadillo de Anchoas y Aceitunas",
            "Bocadillo de Huevos Revueltos",
            "Bocadillo de Mortadela con Pistacho",
            "Bocadillo de Bacón y Huevo",
            "Bocadillo de Salmón Ahumado",
            "Bocadillo de Queso Brie con Mermelada",
            "Bocadillo de Pisto con Atún",
            "Bocadillo de Cerdo Asado",
            "Bocadillo de Pimiento y Berenjena",
            "Bocadillo de Pollo Barbacoa",
            "Bocadillo de Hummus y Verduras",
            "Bocadillo de Pavo y Mostaza",
            "Bocadillo de Jamón Serrano y Tomate",
            "Bocadillo de Tortilla con Jamón",
            "Bocadillo de Queso Cheddar y Bacon",
            "Bocadillo Mediterráneo con Aceitunas",
            "Bocadillo de Pollo al Curry"
        ];

        // Configuración dell paginador
        $itemsPorPagina = 5;
        $totalItems = count($bocadillos);
        $totalPaginas = ceil($totalItems / $itemsPorPagina);

        // Página actual (por defecto solo 1)
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($paginaActual < 1) $paginaActual = 1;
        if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

        // indices para cortar el array según página
        $inicio = ($paginaActual - 1) * $itemsPorPagina;
        $bocadillosPagina = array_slice($bocadillos, $inicio, $itemsPorPagina);

        // Mostrar lista de bocadillos actuales
        echo '<ul class="lista-bocadillos">';
        foreach ($bocadillosPagina as $bocadillo) {
            echo '<li>' . htmlspecialchars($bocadillo) . '</li>';
        }
        echo '</ul>';

        
     // Mostrar paginador si hay más de 1 página
if ($totalPaginas > 1) {
    echo '<nav class="paginador" aria-label="Paginación de bocadillos">';
    
    if ($paginaActual > 1) {
        echo '<a href="?pagina=' . ($paginaActual - 1) . '" aria-label="Página anterior"><</a>';
    }

    // Páginas numeradas
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $clase = ($i === $paginaActual) ? 'activo' : '';
        echo '<a href="?pagina=' . $i . '" class="' . $clase . '" aria-current="' . ($clase ? 'page' : '') . '">' . $i . '</a>';
    }

    if ($paginaActual < $totalPaginas) {
        echo '<a href="?pagina=' . ($paginaActual + 1) . '" aria-label="Página siguiente">></a>';
    }
    echo '</nav>';
}

        ?>
    </main>
</body>
</html>

