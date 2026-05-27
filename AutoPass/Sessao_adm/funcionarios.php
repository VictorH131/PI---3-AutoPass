<?php

$horaAtual = false;
$paginaAtiva = 'funcionarios';

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo '<title>Funcionários - AutoPass</title>';

// ================= PAGINAÇÃO =================
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$porPagina = 10;
$inicio = ($pagina - 1) * $porPagina;

// TOTAL DE REGISTROS
$totalSql = "SELECT COUNT(*) as total FROM usuarios";
$totalStmt = $conn->prepare($totalSql);
$totalStmt->execute();
$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalPaginas = ceil($total / $porPagina);

// ================= LISTA USUÁRIOS =================
$sql = "
SELECT 
    usuarios.*,
    MAX(acessos.data_acesso) AS ultimo_acesso
FROM usuarios
LEFT JOIN veiculos 
    ON veiculos.id_usuarios = usuarios.id_usuario
LEFT JOIN acessos 
    ON acessos.id_veiculo = veiculos.id_veiculo
GROUP BY usuarios.id_usuario
ORDER BY usuarios.id_usuario
LIMIT :inicio, :porPagina
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<body>

    <div class="content p-4" style="background:#f5f6fa; min-height:100vh;">

        <!-- TOPO -->
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h2 class="fw-bold mb-0">Gestão de Funcionários</h2>
                <small class="text-secondary">Gerencie a equipe cadastrada no sistema.</small>
            </div>

            <button id="btnNovoFuncionario" class="btn btn-primary rounded-3 px-4">
                + Novo Funcionário
            </button>

        </div>

        <!-- FILTROS -->
        <div class="d-flex mb-4 align-items-center">

            <div class="d-flex gap-2 flex-wrap">

                <div class="dropdown">
                    <button id="btnTipo" class="btn btn-outline-primary dropdown-toggle border"
                        data-bs-toggle="dropdown" style="min-width:180px; border-radius:10px;">
                        Tipo: Todos
                    </button>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="setTipo('todos','Todos')">Todos</a></li>
                        <li><a class="dropdown-item" href="#"
                                onclick="setTipo('3','Administradores')">Administradores</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTipo('2','Funcionários')">Funcionários</a>
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="setTipo('1','Clientes')">Clientes</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button id="btnStatus" class="btn btn-outline-success dropdown-toggle border"
                        data-bs-toggle="dropdown" style="min-width:180px; border-radius:10px;">
                        Status: Todos
                    </button>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="setStatus('todos','Todos')">Todos</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setStatus('ativo','Ativo')">Ativo</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setStatus('desativado','Inativo')">Inativo</a>
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="setStatus('bloqueado','Bloqueado')">Bloqueado</a>
                        </li>
                    </ul>
                </div>

            </div>

            <button onclick="limparFiltros()" class="btn btn-outline-danger border ms-auto"
                style="min-width:160px; border-radius:10px;">
                Limpar filtros
            </button>

        </div>

        <!-- TABELA -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead style="background:#f1f3f6;">
                            <tr>
                                <th class="p-4">Funcionário</th>
                                <th>Cargo</th>
                                <th>Status</th>
                                <th>Último Acesso</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($usuarios as $dados) { ?>

                                <tr data-tipo="<?= $dados['tipo'] ?>" data-status="<?= $dados['status'] ?>">

                                    <td class="p-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="../<?= $dados['foto'] ?>" width="48" height="48"
                                                style="object-fit:cover;border-radius:50%;">
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    <?= $dados['nome'] . ' ' . $dados['sobrenome'] ?>
                                                </div>
                                                <small class="text-secondary">
                                                    <?= $dados['email'] ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if ($dados['tipo'] == 3) { ?>
                                            <span class="badge text-bg-primary">ADMIN</span>
                                        <?php } elseif ($dados['tipo'] == 2) { ?>
                                            <span class="badge bg-primary-subtle text-primary">FUNCIONÁRIO</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary-subtle text-secondary">CLIENTE</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= ucfirst($dados['status']) ?>
                                    </td>

                                    <td>
                                        <?= $dados['ultimo_acesso']
                                            ? date('d/m/Y H:i', strtotime($dados['ultimo_acesso']))
                                            : 'Nunca acessou' ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            <button class="btn btn-light border rounded-circle btn-carros"
                                                data-id="<?= $dados['id_usuario'] ?>" style="width:38px;height:38px;">
                                                <i class="bi bi-car-front"></i>
                                            </button>

                                            <?php if ($_SESSION['usuario']['tipo'] == 3) { ?>
                                                <?php
                                                $ativo = $dados['status'] === 'ativo';

                                                $corBtn = $ativo ? 'btn-success' : 'btn-danger';
                                                $icone = $ativo ? 'bi-check-circle' : 'bi-x-circle';
                                                ?>

                                                <button
                                                    class="apagar btn <?= $corBtn ?> btn-toggle-status d-flex align-items-center justify-content-center"
                                                    data-id="<?= $dados['id_usuario'] ?>" data-status="<?= $dados['status'] ?>"
                                                    style="width:38px;height:38px; border-radius:50%;">

                                                    <i class="bi <?= $icone ?>"></i>
                                                </button>
                                            <?php } ?>

                                        </div>
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- PAGINAÇÃO -->
        <div class="d-flex justify-content-between align-items-center mt-3">

            <div class="text-muted">
                Página <?= $pagina ?> de <?= $totalPaginas ?>
            </div>

            <div class="btn-group">

                <?php if ($pagina > 1) { ?>
                    <a class="btn btn-outline-primary" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <a class="btn <?= $i == $pagina ? 'btn-primary' : 'btn-outline-primary' ?>" href="?pagina=<?= $i ?>">
                        <?= $i ?>
                    </a>
                <?php } ?>

                <?php if ($pagina < $totalPaginas) { ?>
                    <a class="btn btn-outline-primary" href="?pagina=<?= $pagina + 1 ?>">Próxima</a>
                <?php } ?>

            </div>

        </div>

    </div>

    <script src="../js/adm_veiculo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


    <?php
    include '../src/includes/footer.php';
    ?>
