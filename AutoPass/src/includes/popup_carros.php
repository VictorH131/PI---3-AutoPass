<?php
include 'dbconnect.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM veiculos WHERE id_usuarios = :id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Veículos do Usuário</h3>

<?php if ($carros) { ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Modelo</th>
            <th>Placa</th>
            <th>Cor</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($carros as $c) { ?>
        <tr>
            <td><?= $c['modelo'] ?></td>
            <td><?= $c['placa'] ?></td>
            <td><?= $c['cor'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php } else { ?>

<p>Nenhum veículo encontrado.</p>

<?php } ?>