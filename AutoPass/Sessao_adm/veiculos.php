<?php

$horaAtual = false;
$paginaAtiva = 'veiculos';

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo "<title>Veículos - AutoPass</title>";

$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

$porPagina = 10;
$inicio = ($pagina - 1) * $porPagina;


/* TOTAL */

$totalSQL = "
SELECT COUNT(*) total
FROM veiculos
";

$totalStmt = $conn->prepare($totalSQL);
$totalStmt->execute();

$total = $totalStmt->fetch(PDO::FETCH_ASSOC);

$totalPaginas = ceil(
    $total['total'] / $porPagina
);


/* CORES EXISTENTES */

$sqlCores = "

SELECT DISTINCT cor
FROM veiculos
WHERE cor IS NOT NULL
AND cor != ''
ORDER BY cor ASC

";

$stmtCores = $conn->prepare($sqlCores);

$stmtCores->execute();

$cores = $stmtCores->fetchAll(PDO::FETCH_ASSOC);

/* VEÍCULOS */
$sql = "

SELECT

v.*,

r.codigo_rfid,

u.nome,
u.sobrenome,
u.email,
u.telefone,
u.foto,
u.tipo,
u.status usuario_status,

MAX(a.data_acesso) ultimo_acesso,

GROUP_CONCAT(

CONCAT(
a.tipo,
'|',
c.localizacao,
'|',
DATE_FORMAT(
a.data_acesso,
'%d/%m/%Y %H:%i'
)
)

ORDER BY a.data_acesso DESC
SEPARATOR '###'

) historico,

CASE
WHEN u.status='bloqueado'
THEN 'bloqueado'
ELSE 'ativo'
END status

FROM veiculos v

LEFT JOIN usuarios u
ON u.id_usuario = v.id_usuarios

LEFT JOIN acessos a
ON a.id_veiculo = v.id_veiculo

LEFT JOIN cancelas c
ON c.id_cancela = a.id_cancela

LEFT JOIN rfids r
ON r.id_veiculo = v.id_veiculo

GROUP BY
v.id_veiculo,
r.codigo_rfid,
u.nome,
u.sobrenome,
u.email,
u.telefone,
u.foto,
u.tipo,
u.status

ORDER BY v.id_veiculo DESC

LIMIT :inicio,:porPagina

";

$stmt = $conn->prepare($sql);

$stmt->bindValue(
    ':inicio',
    $inicio,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':porPagina',
    $porPagina,
    PDO::PARAM_INT
);

$stmt->execute();

$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="content p-4" style="background:#f6f7fb;min-height:100vh;">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h2 class="fw-bold mb-0">

                Veículos Registrados

            </h2>

            <small class="text-secondary">

                Gerencie permissões e visualize o histórico.

            </small>

        </div>

    </div>



    <!-- FILTROS -->

    <div class="d-flex mb-4 align-items-center">

        <div class="d-flex gap-2">

            <div class="dropdown">

                <button id="btnStatus" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">

                    Status: Todos

                </button>

                <ul class="dropdown-menu">

                    <li>
                        <a class="dropdown-item" onclick="setStatus('todos','Todos')">

                            Todos

                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" onclick="setStatus('ativo','Ativo')">

                            Ativo

                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" onclick="setStatus('bloqueado','Bloqueado')">

                            Bloqueado

                        </a>
                    </li>

                </ul>

            </div>


            <div class="dropdown">

                <button id="btnCor" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">

                    Cor: Todas

                </button>

                <ul class="dropdown-menu">

                    <li>

                        <a class="dropdown-item" onclick="setCor('todos','Todas')">

                            Todas

                        </a>

                    </li>

                    <?php foreach ($cores as $cor) { ?>

                        <li>

                            <a class="dropdown-item" onclick="setCor(
                                '<?= strtolower($cor['cor']) ?>',
                                '<?= ucfirst($cor['cor']) ?>'
                                )">

                                <?= ucfirst($cor['cor']) ?>

                            </a>

                        </li>

                    <?php } ?>

                </ul>

            </div>

        </div>

        <button onclick="limparFiltros()" class="btn btn-outline-danger ms-auto">

            Limpar filtros

        </button>

    </div>



    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead style="background:#f1f3f6">

                    <tr>

                        <th class="p-4">VEÍCULO</th>
                        <th>PLACA</th>
                        <th>RFID</th>
                        <th>ANO</th>
                        <th>PROPRIETÁRIO</th>
                        <th>ÚLTIMO ACESSO</th>
                        <th>STATUS</th>
                        <th class="text-center">AÇÕES</th>

                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($veiculos as $dados) { ?>

                        <tr data-status="<?= $dados['status'] ?>" data-cor="<?= strtolower($dados['cor']) ?>">

                            <td class="p-4">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="bg-light p-2 rounded">

                                        <i class="bi bi-car-front"></i>

                                    </div>

                                    <div>

                                        <div class="fw-semibold">

                                            <?= $dados['marca'] ?>
                                            <?= $dados['modelo'] ?>

                                        </div>

                                        <small class="text-secondary">

                                            <?= $dados['cor'] ?>

                                        </small>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="badge bg-light text-dark">

                                    <?= $dados['placa'] ?>

                                </span>

                            </td>


                            <td>

                                <?php if (!empty($dados['codigo_rfid'])) { ?>

                                    <span class="badge rounded-pill bg-primary">

                                        <?= $dados['codigo_rfid'] ?>

                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-secondary">

                                        Sem RFID

                                    </span>

                                <?php } ?>

                            </td>


                            <td>

                                <?= $dados['ano'] ?>

                            </td>


                            <td>

                                <?= $dados['nome'] ?>
                                <?= $dados['sobrenome'] ?>

                            </td>


                            <td>

                                <?= $dados['ultimo_acesso']

                                    ?

                                    date(
                                        'd/m/Y H:i',
                                        strtotime(
                                            $dados['ultimo_acesso']
                                        )
                                    )

                                    :

                                    'Nunca acessou'

                                    ?>

                            </td>


                            <td>

                                <?php if ($dados['status'] == "ativo") { ?>

                                    <span class="badge rounded-pill bg-primary">

                                        Ativo

                                    </span>

                                <?php } else { ?>

                                    <span class="badge rounded-pill bg-danger">

                                        Bloqueado

                                    </span>

                                <?php } ?>

                            </td>


                            <td class="text-center">

                                <button class="btn btn-light border btnDetalhes"
                                    data-modelo="<?= $dados['marca'] ?> <?= $dados['modelo'] ?>"
                                    data-cor="<?= $dados['cor'] ?>" data-placa="<?= $dados['placa'] ?>"
                                    data-ano="<?= $dados['ano'] ?>" data-rfid="<?= $dados['codigo_rfid'] ?>"
                                    data-proprietario="<?= $dados['nome'] ?> <?= $dados['sobrenome'] ?>"
                                    data-email="<?= $dados['email'] ?>" data-telefone="<?= $dados['telefone'] ?>" data-foto="<?= !empty($dados['foto'])
                                            ? '../' . $dados['foto']
                                            : '../img/user.png' ?>" data-tipo="<?= $dados['tipo'] ?>"
                                    data-historico="<?= htmlspecialchars($dados['historico'] ?? '') ?>"
                                    data-status="<?= $dados['status'] ?>">

                                    <i class="bi bi-three-dots"></i>

                                </button>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>




    <div class="d-flex justify-content-between align-items-center mt-3">

        <div class="text-secondary">

            Exibindo

            <?= count($veiculos) ?>

            de

            <?= $total['total'] ?>

            veículos

        </div>


        <div class="btn-group">

            <?php if ($pagina > 1) { ?>

                <a class="btn btn-light border" href="?pagina=<?= $pagina - 1 ?>">

                    <i class="bi bi-chevron-left"></i>

                </a>

            <?php } ?>


            <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

                <a href="?pagina=<?= $i ?>" class="btn <?= $pagina == $i ? 'btn-primary' : 'btn-light border' ?>">

                    <?= $i ?>

                </a>

            <?php } ?>


            <?php if ($pagina < $totalPaginas) { ?>

                <a class="btn btn-light border" href="?pagina=<?= $pagina + 1 ?>">

                    <i class="bi bi-chevron-right"></i>

                </a>

            <?php } ?>

        </div>

    </div>

</div>



<script src="../js/veiculos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<?php
include '../src/includes/footer.php';
?>