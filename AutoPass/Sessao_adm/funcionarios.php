<?php

$horaAtual = false;
$paginaAtiva = 'funcionarios';

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo '<title>Funcionários - AutoPass</title>';

// BUSCAR FUNCIONÁRIOS + ÚLTIMO ACESSO
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
";

$stmt = $conn->prepare($sql);
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

            <button class="btn btn-primary rounded-3 px-4">
                + Novo Funcionário
            </button>

        </div>

        <!-- FILTROS + LIMPAR -->
        <div class="d-flex mb-4 align-items-center">

            <!-- BLOCO ESQUERDA (FILTROS) -->
            <div class="d-flex gap-2 flex-wrap">

                <!-- TIPO -->
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

                <!-- STATUS -->
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

            <!-- BOTÃO DIREITA -->
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

                                <tr data-tipo="<?php echo $dados['tipo']; ?>" data-status="<?php echo $dados['status']; ?>">

                                    <td class="p-4">

                                        <div class="d-flex align-items-center gap-3">

                                            <img src="../<?php echo $dados['foto']; ?>" width="48" height="48"
                                                style="object-fit:cover;border-radius:50%;">

                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    <?php echo $dados['nome'] . ' ' . $dados['sobrenome']; ?>
                                                </div>
                                                <small class="text-secondary">
                                                    <?php echo $dados['email']; ?>
                                                </small>
                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        <?php if ($dados['tipo'] == 3) { ?>
                                            <span class="badge text-bg-primary px-3 py-2">ADMIN</span>
                                        <?php } elseif ($dados['tipo'] == 2) { ?>
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2">FUNCIONÁRIO</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">CLIENTE</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php if ($dados['status'] == 'ativo') { ?>
                                            <span class="fw-semibold text-primary">● Ativo</span>
                                        <?php } elseif ($dados['status'] == 'desativado') { ?>
                                            <span class="fw-semibold text-secondary">● Inativo</span>
                                        <?php } elseif ($dados['status'] == 'bloqueado') { ?>
                                            <span class="fw-semibold text-danger">● Bloqueado</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php if ($dados['ultimo_acesso']) { ?>
                                            <?php echo date('d/m/Y H:i', strtotime($dados['ultimo_acesso'])); ?>
                                        <?php } else { ?>
                                            Nunca acessou
                                        <?php } ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            <button class="btn btn-light border rounded-circle btn-carros"
                                                data-id="<?php echo $dados['id_usuario']; ?>"
                                                style="width:38px;height:38px;">
                                                <i class="bi bi-car-front"></i>
                                            </button>

                                            <?php if ($_SESSION['usuario']['tipo'] == 3) { ?>
                                                <button class="btn btn-light border rounded-circle btn-toggle-status"
                                                    data-id="<?php echo $dados['id_usuario']; ?>"
                                                    data-status="<?php echo $dados['status']; ?>"
                                                    style="width:38px;height:38px;">

                                                    <?php if ($dados['status'] == 'ativo') { ?>
                                                        <i class="bi bi-x-circle text-danger"></i>
                                                    <?php } else { ?>
                                                        <i class="bi bi-arrow-repeat text-success"></i>
                                                    <?php } ?>

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

    </div>

    <script src="../js/adm_veiculo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>