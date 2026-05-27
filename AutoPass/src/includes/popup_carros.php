<?php
require_once 'dbconnect.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM veiculos WHERE id_usuarios = :id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4 class="mb-3">Veículos do Usuário</h4>

<?php if (!empty($carros)) { ?>

<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Modelo</th>
            <th>Placa</th>
            <th>Cor</th>
            <th>Ano</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($carros as $c) { ?>
            <tr>
                <td><?= htmlspecialchars($c['modelo']) ?></td>
                <td><?= htmlspecialchars($c['placa']) ?></td>
                <td><?= htmlspecialchars($c['cor']) ?></td>
                <td><?= htmlspecialchars($c['ano']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php } else { ?>

<div class="alert alert-warning">
    Nenhum veículo encontrado para este usuário.
</div>

<?php } ?>