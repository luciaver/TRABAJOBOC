<?php
$host='localhost';
$dbname='practicalmgsi';
$user='root';
$pass='';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query="select *
    from pedidos";
    $stmt=$pdo->prepare($query);
    $stmt->execute();



} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}



/*

$pedidos = [
    ['bocadillo' => 'Jamón', 'tipo' => 'Frio', 'fecha' => '21-01-2025', 'precio' => '2,50', 'estado' => 'Pendiente','Mes'=> 'Enero'],
    ['bocadillo' => 'Queso', 'tipo' => 'Frio', 'fecha' => '19-01-2025', 'precio' => '3', 'estado' => 'Retirado','Mes'=> 'Dciembre'],
    ['bocadillo' => 'Lomo', 'tipo' => 'Caliente', 'fecha' => '18-01-2025', 'precio' => '2,50', 'estado' => 'Retirado','Mes'=> 'Septiembre'],
    ['bocadillo' => 'Bacon y huevo', 'tipo' => 'Caliente', 'fecha' => '17-01-2025', 'precio' => '3', 'estado' => 'Retirado','Mes'=> 'Junio'],
    ['bocadillo' => 'Hamburguesa', 'tipo' => 'Frio', 'fecha' => '15-01-2025', 'precio' => '4', 'estado' => 'Retirado','Mes'=> 'Octubre'],
    ['bocadillo' => 'Lomo', 'tipo' => 'Frio', 'fecha' => '15-01-2025', 'precio' => '2,50', 'estado' => 'Retirado','Mes'=> 'Mayo'],
    ['bocadillo' => 'Jamón', 'tipo' => 'Frio', 'fecha' => '14-01-2025', 'precio' => '2,50', 'estado' => 'Retirado','Mes'=> 'Abril'],

    
];
*/
$pedidos=($stmt->fetchAll(PDO::FETCH_ASSOC));
print_r($pedidos); 


$total = 0;
foreach ($pedidos as &$p) {
    $p['precio_num'] = (float) str_replace(',', '.', $p['precio']);
    $total += $p['precio_num'];
    $p['clase_btn'] = $p['estado'] === 'Pendiente' ? 'btn-pendiente' : 'btn-retirado';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de pedidos</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>

<header>
  
</header>

<h1>Historial de bocadillos</h1>

<form method="get">
    <label for="mes"><strong>Selecciona un mes:</strong></label>
    <select id="mes" name="mes">
        <option disabled selected>Mes</option>
        <?php
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        foreach ($meses as $mes) {
            echo "<option>$mes</option>";
        }
        ?>
    </select>
</form>

<table>
    <thead>
        <tr>
            <th>Bocadillo</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Precio</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido['bocadillo'] ?></td>
            <td><?=$pedido['tipo'] ?></td>
            <td><?= $pedido['fecha'] ?></td>
            <td><?= number_format($pedido['precio_num'], 2, ',', '.') ?> €</td>
            <td><button class="<?= $pedido['clase_btn'] ?>"><?= $pedido['estado'] ?></button></td>
        </tr>
        <?php endforeach; ?>
        <tr id="total-row">
            <td colspan="3"></td>
            <td>Total: <?= number_format($total, 2, ',', '.') ?> €</td>
            <td></td>
        </tr>
    </tbody>
</table>

</body>
</html>
