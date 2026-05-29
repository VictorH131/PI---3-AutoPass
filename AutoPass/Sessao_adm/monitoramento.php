<?php

$horaAtual = false;
$paginaAtiva = 'monitoramento';


include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo "<title>Monitoramento - AutoPass</title>";

/* =========================================
ERROS SISTEMA + HARDWARE
========================================= */

/* ERROS DO SISTEMA */

$errosSistema = $conn->query("
    SELECT COUNT(*) total
    FROM eventos_sistema
    WHERE nivel='erro'
    AND DATE(data_evento)=CURDATE()
")->fetch(PDO::FETCH_ASSOC);

/* HARDWARE OFFLINE */

$hardwareOffline = $conn->query("
    SELECT COUNT(*) total
    FROM sensores
    WHERE status='offline'
")->fetch(PDO::FETCH_ASSOC);

/* TOTAL PROBLEMAS */

$totalProblemas =
    (int)$errosSistema['total']
    +
    (int)$hardwareOffline['total'];

/* STATUS */

$statusSistema = 'Perfeito';
$corSistema = 'success';
$iconeSistema = 'bi-shield-check';
$descricaoSistema = 'Nenhum problema detectado';

if($totalProblemas >= 1 && $totalProblemas < 3){

    $statusSistema = 'Estável';
    $corSistema = 'warning';
    $iconeSistema = 'bi-exclamation-triangle';
    $descricaoSistema = "$totalProblemas problema(s) detectado(s)";

}

if($totalProblemas >= 3){

    $statusSistema = 'Ruim';
    $corSistema = 'danger';
    $iconeSistema = 'bi-x-octagon';
    $descricaoSistema = "$totalProblemas problema(s) crítico(s)";

}

/* =========================================
TOTAL OCUPAÇÃO
========================================= */

$totalVagas = $conn->query("
    SELECT COUNT(*) total
    FROM vagas
")->fetch(PDO::FETCH_ASSOC);

$totalOcupadas = $conn->query("
    SELECT COUNT(*) total
    FROM estacionamento
    WHERE status='ativo'
")->fetch(PDO::FETCH_ASSOC);

/* =========================================
SENSORES ONLINE
========================================= */

$sensoresOnline = $conn->query("
    SELECT COUNT(*) total
    FROM sensores
    WHERE status='online'
")->fetch(PDO::FETCH_ASSOC);

/* =========================================
ENTRADAS / SAIDAS
========================================= */

$entradas = $conn->query("
    SELECT COUNT(*) total
    FROM acessos
    WHERE tipo='entrada'
    AND DATE(data_acesso)=CURDATE()
")->fetch(PDO::FETCH_ASSOC);

$saidas = $conn->query("
    SELECT COUNT(*) total
    FROM acessos
    WHERE tipo='saida'
    AND DATE(data_acesso)=CURDATE()
")->fetch(PDO::FETCH_ASSOC);

$negados = $conn->query("
    SELECT COUNT(*) total
    FROM acessos
    WHERE status='negado'
    AND DATE(data_acesso)=CURDATE()
")->fetch(PDO::FETCH_ASSOC);

/* =========================================
EVENTOS
========================================= */

$eventos = $conn->query("
    SELECT

    a.id_acesso,
    a.tipo,
    a.data_acesso,
    a.status,

    v.placa,
    v.modelo,
    v.cor,

    u.nome,

    c.nome cancela,
    c.localizacao,

    s.nome setor

    FROM acessos a

    INNER JOIN veiculos v
    ON v.id_veiculo = a.id_veiculo

    INNER JOIN usuarios u
    ON u.id_usuario = v.id_usuarios

    INNER JOIN cancelas c
    ON c.id_cancela = a.id_cancela

    LEFT JOIN estacionamento est
    ON est.id_veiculo = v.id_veiculo

    LEFT JOIN vagas vg
    ON vg.id_vaga = est.id_vaga

    LEFT JOIN setores s
    ON s.id_setor = vg.id_setor

    ORDER BY a.data_acesso DESC
    LIMIT 20

")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================
SENSORES
========================================= */

$sensores = $conn->query("
    SELECT *
    FROM sensores
")->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    body {
        background: #f4f7fb;
    }

    /* ============================= */

    .dashboard-card {
        background: white;
        border: none;
        border-radius: 22px;
        padding: 25px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
    }

    .dashboard-card h6 {
        font-size: 14px;
        color: #9aa3b2;
    }

    .dashboard-card h2 {
        font-weight: 700;
    }

    /* ============================= */

    .eventos-card,
    .side-card {
        background: white;
        border: none;
        border-radius: 22px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
    }

    .side-card {
        padding: 20px;
    }

    /* ============================= */

    .vehicle-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: #edf4ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* ============================= */

    .badge-status {
        padding: 8px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ============================= */

    .badge-entrada {
        background: #dcfce7;
        color: #166534;
    }

    .badge-saida {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-negado {
        background: #fee2e2;
        color: #dc2626;
    }

    /* ============================= */

    .sensor-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    /* ============================= */

    .filter-btn {
        border: none;
        border-radius: 40px;
        padding: 10px 20px;
        font-weight: 600;
        transition: .2s;
    }

    .filter-all {
        background: #111827;
        color: white;
    }

    .filter-entrada {
        background: #dcfce7;
        color: #166534;
    }

    .filter-saida {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .filter-negado {
        background: #fee2e2;
        color: #dc2626;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
    }

    /* ============================= */

    .drawer {
        position: fixed;
        top: 0;
        right: -420px;
        width: 400px;
        height: 100%;
        background: white;
        z-index: 9999;
        transition: .3s;
        box-shadow: -5px 0 20px rgba(0, 0, 0, .08);
        overflow: auto;
    }

    .drawer.active {
        right: 0;
    }

    .drawer-header {
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .drawer-body {
        padding: 25px;
    }

    .closeDrawer {
        cursor: pointer;
        font-size: 24px;
    }

    /* ============================= */

    .table td {
        vertical-align: middle;
    }


    .sensor-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="container-fluid p-4">

    <!-- TOPO -->

    <div class="row g-3 mb-4">

        <div class="col-lg-4">

            <div class="dashboard-card">

                <h6>Total Occupancy</h6>

                <h2>

                    <?= $totalOcupadas['total'] ?>

                    /

                    <?= $totalVagas['total'] ?>

                </h2>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-card">

                <h6>Active Sensors</h6>

                <h2>

                    <?= $sensoresOnline['total'] ?>

                    Online

                </h2>

            </div>

        </div>

        <div class="col-lg-4">

    <div class="dashboard-card">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h6>Integridade do Sistema</h6>

                <h2 class="text-<?= $corSistema ?>">

                    <?= $statusSistema ?>

                </h2>

                <small class="text-secondary">

                    <?= $descricaoSistema ?>

                </small>

            </div>

            <div class="rounded-circle d-flex align-items-center justify-content-center
            bg-<?= $corSistema ?>-subtle"
            style="width:60px;height:60px;">

                <i class="bi <?= $iconeSistema ?> text-<?= $corSistema ?>"
                style="font-size:28px;"></i>

            </div>

        </div>

    </div>

</div>

    <!-- CONTEUDO -->

    <div class="row">

        <!-- EVENTOS -->

        <div class="col-lg-9">

            <div class="eventos-card p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h4 class="fw-bold mb-0">

                            Fluxo de Eventos

                        </h4>

                        <small class="text-secondary">

                            Atividade em tempo real

                        </small>

                    </div>

                </div>

                <!-- FILTROS -->

                <div class="d-flex gap-2 mb-4">

                    <button class="filter-btn filter-all" onclick="filtrar('todos')">

                        Todos

                    </button>

                    <button class="filter-btn filter-entrada" onclick="filtrar('entrada')">

                        Entradas

                    </button>

                    <button class="filter-btn filter-saida" onclick="filtrar('saida')">

                        Saídas

                    </button>

                    <button class="filter-btn filter-negado" onclick="filtrar('negado')">

                        Negados

                    </button>

                </div>

                <!-- TABELA -->

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Veículo</th>
                            <th>Horário</th>
                            <th>Setor</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($eventos as $e): ?>

                            <tr class="linhaEvento" data-tipo="<?= $e['tipo'] ?>" data-status="<?= $e['status'] ?>"
                                onclick="abrirDrawer(this)" data-placa="<?= $e['placa'] ?>"
                                data-modelo="<?= $e['modelo'] ?>" data-cor="<?= $e['cor'] ?>"
                                data-proprietario="<?= $e['nome'] ?>" data-hora="<?= $e['data_acesso'] ?>"
                                data-setor="<?= $e['setor'] ?>" data-local="<?= $e['localizacao'] ?>"
                                data-tipoacesso="<?= $e['tipo'] ?>" data-statusacesso="<?= $e['status'] ?>"
                                style="cursor:pointer">

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="vehicle-icon">

                                            <i class="bi bi-car-front-fill"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                <?= $e['placa'] ?>

                                            </div>

                                            <small class="text-secondary">

                                                <?= $e['modelo'] ?>

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?= date(
                                        'H:i:s',
                                        strtotime($e['data_acesso'])
                                    ) ?>

                                </td>

                                <td>

                                    <?= $e['setor'] ?: 'Sem setor' ?>

                                </td>

                                <td>

                                    <?php if ($e['status'] == 'negado'): ?>

                                        <span class="badge-status badge-negado">

                                            NEGADO

                                        </span>

                                    <?php elseif ($e['tipo'] == 'entrada'): ?>

                                        <span class="badge-status badge-entrada">

                                            ENTRADA

                                        </span>

                                    <?php else: ?>

                                        <span class="badge-status badge-saida">

                                            SAÍDA

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- LATERAL -->

        <div class="col-lg-3">

            <div class="side-card mb-3">

                <h5 class="fw-bold mb-4">

                    Resumo Diário

                </h5>

                <div class="sensor-item">

                    <span>Total Entradas</span>

                    <strong>

                        <?= $entradas['total'] ?>

                    </strong>

                </div>

                <div class="sensor-item">

                    <span>Total Saídas</span>

                    <strong>

                        <?= $saidas['total'] ?>

                    </strong>

                </div>

                <div class="sensor-item border-0">

                    <span>Acessos Negados</span>

                    <strong class="text-danger">

                        <?= $negados['total'] ?>

                    </strong>

                </div>

            </div>

            <!-- SENSORES -->

            <div class="side-card">

                <h5 class="fw-bold mb-4">

                    <i class="bi bi-cpu me-2"></i>

                    Status Hardware

                </h5>

                <?php foreach ($sensores as $s): ?>

                    <div class="sensor-item">

                        <div class="d-flex align-items-center gap-2">

                            <?php if ($s['status'] == 'online'): ?>

                                <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:35px;height:35px;">

                                    <i class="bi bi-wifi text-success"></i>

                                </div>

                            <?php else: ?>

                                <div class="bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:35px;height:35px;">

                                    <i class="bi bi-wifi-off text-danger"></i>

                                </div>

                            <?php endif; ?>

                            <div>

                                <div class="fw-semibold">

                                    <?= $s['codigo'] ?>

                                </div>

                                <small class="text-secondary">

                                    <?= strtoupper($s['tipo']) ?>

                                    •

                                    <?= $s['setor'] ?>

                                </small>

                            </div>

                        </div>

                        <?php if ($s['status'] == 'online'): ?>

                            <span class="badge rounded-pill text-bg-success">

                                ONLINE

                            </span>

                        <?php else: ?>

                            <span class="badge rounded-pill text-bg-danger">

                                OFFLINE

                            </span>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>
        </div>

    </div>

</div>

<!-- DRAWER -->

<div class="drawer" id="drawer">

    <div class="drawer-header d-flex justify-content-between">

        <h4 class="fw-bold">

            Detalhes do Evento

        </h4>

        <div class="closeDrawer" onclick="fecharDrawer()">

            ×

        </div>

    </div>

    <div class="drawer-body" id="drawerContent"></div>

</div>

<script>

    function abrirDrawer(el) {

        const placa = el.dataset.placa;
        const modelo = el.dataset.modelo;
        const cor = el.dataset.cor;
        const proprietario = el.dataset.proprietario;
        const hora = el.dataset.hora;
        const setor = el.dataset.setor;
        const local = el.dataset.local;
        const tipo = el.dataset.tipoacesso;
        const status = el.dataset.statusacesso;

        document.getElementById('drawerContent').innerHTML = `

        <div class="mb-4">

            <small class="text-secondary">VEÍCULO</small>

            <h2 class="fw-bold">${placa}</h2>

            <div class="text-secondary">

                ${modelo} - ${cor}

            </div>

        </div>

        <hr>

        <div class="mb-3">

            <small class="text-secondary">

                PROPRIETÁRIO

            </small>

            <div class="fw-semibold">

                ${proprietario}

            </div>

        </div>

        <div class="mb-3">

            <small class="text-secondary">

                TIPO

            </small>

            <div class="fw-bold text-uppercase">

                ${tipo}

            </div>

        </div>

        <div class="mb-3">

            <small class="text-secondary">

                SETOR

            </small>

            <div class="fw-semibold">

                ${setor}
            </div>

        </div>

        <div class="mb-3">

            <small class="text-secondary">

                HORÁRIO

            </small>

            <div class="fw-semibold">

                ${hora}

            </div>

        </div>

        <div class="mb-3">

            <small class="text-secondary">

                LOCALIZAÇÃO

            </small>

            <div class="fw-semibold">

                ${local}

            </div>

        </div>

        <div class="mb-3">

            <small class="text-secondary">

                STATUS

            </small>

            <div class="fw-bold">

                ${status}

            </div>

        </div>

    `;

        document.getElementById('drawer')
            .classList.add('active');
    }

    function fecharDrawer() {

        document.getElementById('drawer')
            .classList.remove('active');

    }

    function filtrar(tipo) {

        const linhas = document.querySelectorAll('.linhaEvento');

        linhas.forEach(linha => {

            linha.style.display = '';

            if (tipo == 'entrada') {

                if (linha.dataset.tipo != 'entrada') {

                    linha.style.display = 'none';

                }

            }

            if (tipo == 'saida') {

                if (linha.dataset.tipo != 'saida') {

                    linha.style.display = 'none';

                }

            }

            if (tipo == 'negado') {

                if (linha.dataset.status != 'negado') {

                    linha.style.display = 'none';

                }

            }

        });

    }

    /* ======================================
    AUTO REFRESH
    ====================================== */

    setInterval(() => {

        location.reload();

    }, 10000);

</script>

<?php include '../src/includes/footer.php'; ?>