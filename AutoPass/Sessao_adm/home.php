<?php

$horaAtual = true;
$paginaAtiva = 'home';

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo '<title>Home - AutoPass</title>';

/* =========================
DADOS PRINCIPAIS
========================= */

$totalVeiculos = $conn->query("SELECT COUNT(*) AS total FROM veiculos")->fetch();

$vagas = $conn->query("
SELECT 
SUM(status='livre') AS livre,
SUM(status='ocupada') AS ocupadas
FROM vagas
")->fetch();

$ocupacao = $conn->query("
SELECT 
CASE 
    WHEN COUNT(*) = 0 THEN 0
    ELSE ROUND((SUM(status='ocupada')/COUNT(*))*100)
END AS ocupacao
FROM vagas
")->fetch();

$entradasHoje = $conn->query("
SELECT COUNT(*) AS entradas
FROM acessos
WHERE tipo='entrada'
AND DATE(data_acesso)=CURDATE()
")->fetch();

$saidasHoje = $conn->query("
SELECT COUNT(*) AS saidas
FROM acessos
WHERE tipo='saida'
AND DATE(data_acesso)=CURDATE()
")->fetch();

$movimentacoes = $conn->query("
SELECT a.tipo, a.data_acesso, v.placa, c.nome
FROM acessos a
INNER JOIN veiculos v ON a.id_veiculo=v.id_veiculo
INNER JOIN cancelas c ON a.id_cancela=c.id_cancela
ORDER BY a.data_acesso DESC
LIMIT 4
")->fetchAll();

/* =========================
GRÁFICO DIA
========================= */

$grafDiaEnt = array_fill(0, 24, 0);
$grafDiaSai = array_fill(0, 24, 0);

foreach ($conn->query("
SELECT HOUR(data_acesso) h, COUNT(*) t
FROM acessos
WHERE tipo='entrada' AND DATE(data_acesso)=CURDATE()
GROUP BY h
")->fetchAll() as $d) {
    $grafDiaEnt[(int) $d['h']] = (int) $d['t'];
}

foreach ($conn->query("
SELECT HOUR(data_acesso) h, COUNT(*) t
FROM acessos
WHERE tipo='saida' AND DATE(data_acesso)=CURDATE()
GROUP BY h
")->fetchAll() as $d) {
    $grafDiaSai[(int) $d['h']] = (int) $d['t'];
}


/* =========================
PEsquisa de setores
========================= */
$setores = $conn->query("
SELECT 
    setor,

    COUNT(*) AS total_vagas,

    SUM(
        CASE 
            WHEN status='ocupada' 
            THEN 1 
            ELSE 0 
        END
    ) AS vagas_ocupadas,

    SUM(
        CASE 
            WHEN status='livre' 
            THEN 1 
            ELSE 0 
        END
    ) AS vagas_livres,

    SUM(
        CASE 
            WHEN pcd=1 
            THEN 1 
            ELSE 0 
        END
    ) AS vagas_pcd

FROM vagas

GROUP BY setor
ORDER BY setor
")->fetchAll();

/* =========================
GRÁFICO SEMANA (FIXO 7 DIAS)
========================= */

$grafSemanaEnt = array_fill(0, 7, 0);
$grafSemanaSai = array_fill(0, 7, 0);

$entSemana = $conn->query("
SELECT DATE(data_acesso) d, COUNT(*) t
FROM acessos
WHERE tipo='entrada'
AND data_acesso >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
GROUP BY d
ORDER BY d
")->fetchAll();

$saiSemana = $conn->query("
SELECT DATE(data_acesso) d, COUNT(*) t
FROM acessos
WHERE tipo='saida'
AND data_acesso >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
GROUP BY d
ORDER BY d
")->fetchAll();

$i = 0;
foreach ($entSemana as $d) {
    $grafSemanaEnt[$i++] = (int) $d['t'];
}

$i = 0;
foreach ($saiSemana as $d) {
    $grafSemanaSai[$i++] = (int) $d['t'];
}

/* =========================
GRÁFICO MÊS
========================= */

$grafMesEnt = array_fill(1, 31, 0);
$grafMesSai = array_fill(1, 31, 0);

foreach ($conn->query("
SELECT DAY(data_acesso) d, COUNT(*) t
FROM acessos
WHERE tipo='entrada'
AND MONTH(data_acesso)=MONTH(CURDATE())
AND YEAR(data_acesso)=YEAR(CURDATE())
GROUP BY d
")->fetchAll() as $d) {
    $grafMesEnt[(int) $d['d']] = (int) $d['t'];
}

foreach ($conn->query("
SELECT DAY(data_acesso) d, COUNT(*) t
FROM acessos
WHERE tipo='saida'
AND MONTH(data_acesso)=MONTH(CURDATE())
AND YEAR(data_acesso)=YEAR(CURDATE())
GROUP BY d
")->fetchAll() as $d) {
    $grafMesSai[(int) $d['d']] = (int) $d['t'];
}

?>
<div class="dashboard-home">
    <div class="content p-4" style="background:#f6f7fb;min-height:100vh;">

        <!-- TOPO -->
        <div class="row g-3 mb-4">

            <!-- TOTAL VEÍCULOS -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Total de<br>Veículos
                    </div>

                    <div class="card-number text-primary">
                        <?= $totalVeiculos['total'] ?>
                    </div>

                    <div class="card-icon text-secondary">
                        <i class="bi bi-car-front-fill"></i>
                    </div>

                </div>

            </div>

            <!-- VAGAS DISPONÍVEIS -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Vagas<br>Disponíveis
                    </div>

                    <div class="card-number text-success">
                        <?= $vagas['livre'] ?>
                    </div>

                    <div class="card-icon text-success opacity-75">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>

            </div>

            <!-- VAGAS OCUPADAS -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Vagas<br>Ocupadas
                    </div>

                    <div class="card-number text-danger">
                        <?= $vagas['ocupadas'] ?>
                    </div>

                    <div class="card-icon text-info opacity-75">
                        <i class="bi bi-person-bounding-box"></i>
                    </div>

                </div>

            </div>

            <!-- ENTRADAS -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Entradas<br>Hoje
                    </div>

                    <div class="card-number text-success-emphasis">
                        <?= $entradasHoje['entradas'] ?>
                    </div>

                    <div class="card-icon text-secondary opacity-75">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>

                </div>

            </div>

            <!-- SAÍDAS -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Saídas<br>Hoje
                    </div>

                    <div class="card-number text-danger-emphasis">
                        <?= $saidasHoje['saidas'] ?>
                    </div>

                    <div class="card-icon text-secondary opacity-75">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>

                </div>

            </div>

            <!-- OCUPAÇÃO -->
            <div class="col-lg-2 col-md-4 col-6">

                <div class="dashboard-card">

                    <div class="card-title">
                        Ocupação<br>

                        <?php if ($ocupacao['ocupacao'] >= 80) { ?>
                            <span class="status-critico">Crítico</span>
                        <?php } ?>

                    </div>

                    <div class="card-number ">
                        <?= $ocupacao['ocupacao'] ?>
                    </div>

                    <div class="card-icon text-secondary opacity-50">
                        <i class="bi bi-percent"></i>
                    </div>

                </div>

            </div>

        </div>



        <div class="row g-4 align-items-stretch">

            <!-- GRÁFICO -->
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h5 class="fw-bold mb-0">Fluxo de Movimentação</h5>
                            <small class="text-secondary">Entradas e saídas em tempo real</small>
                        </div>

                        <div class="btn-group">
                            <button id="btnDia" class="btn btn-dark btn-sm">Dia</button>
                            <button id="btnSemana" class="btn btn-outline-dark btn-sm">Semana</button>
                            <button id="btnMes" class="btn btn-outline-dark btn-sm">Mês</button>
                        </div>

                    </div>

                    <!-- isso aqui resolve o “esticado” -->
                    <div class="flex-grow-1">
                        <canvas id="graficoFluxo"></canvas>
                    </div>

                </div>

            </div>

            <!-- HISTÓRICO -->
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">

                    <h5 class="fw-bold mb-4">Últimas Movimentações</h5>

                    <div style="overflow-y:auto; flex:1; max-height: 420px;">

                        <?php foreach ($movimentacoes as $mov) { ?>

                            <div class="bg-light rounded-4 p-3 mb-3">

                                <div class="d-flex justify-content-between">

                                    <div>
                                        <strong><?= $mov['placa'] ?></strong><br>
                                        <small class="text-secondary">
                                            <?= ucfirst($mov['tipo']) ?> - <?= $mov['nome'] ?>
                                        </small>
                                    </div>

                                    <small class="text-secondary">
                                        <?= date('H:i', strtotime($mov['data_acesso'])) ?>
                                    </small>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                    <!-- BOTÃO FINAL -->
                    <a href="monitoramento.php" class="btn btn-primary w-100 mt-3">
                        VER TODO O HISTÓRICO
                    </a>

                </div>

            </div>

            <!-- STATUS DOS PÁTIOS -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h5 class="fw-bold mb-0">
                            Status dos Pátios
                        </h5>

                        <small class="text-secondary">
                            Monitoramento dos setores
                        </small>
                    </div>

                </div>

                <div class="row align-items-center">

                    <!-- SETORES -->
                    <div class="col-lg-6">
                        <div class="setores-grid">

                            <?php foreach ($setores as $setor) {

                                $percentual = 0;

                                if ($setor['total_vagas'] > 0) {

                                    $percentual =
                                        ($setor['vagas_ocupadas'] / $setor['total_vagas']) * 100;
                                }

                                if ($percentual <= 25) {

                                    $cor = 'setor-verde';

                                } elseif ($percentual <= 50) {

                                    $cor = 'setor-amarelo';

                                } elseif ($percentual <= 75) {

                                    $cor = 'setor-azul';

                                } else {

                                    $cor = 'setor-vermelho';
                                }

                                ?>

                                <div class="setor-box <?= $cor ?>">

                                    <?= strtoupper($setor['setor']) ?>

                                </div>

                            <?php } ?>

                        </div>

                    </div>

                    <!-- LEGENDA -->
                    <!-- LEGENDA -->
                    <div class="col-lg-6 mt-4 mt-lg-0">

                        <div class="info-patio">

                            <!-- RESTANTE -->
                            <div class="restante-box">

                                <h6 class="info-title">
                                    Restante
                                </h6>

                                <div class="restante-grid">

                                    <div class="restante-item">
                                        <span class="restante-dot verde"></span>
                                        <span>100%</span>
                                    </div>

                                    <div class="restante-item">
                                        <span class="restante-dot azul"></span>
                                        <span>- 75%</span>
                                    </div>

                                    <div class="restante-item">
                                        <span class="restante-dot amarelo"></span>
                                        <span>- 50%</span>
                                    </div>

                                    <div class="restante-item">
                                        <span class="restante-dot vermelho"></span>
                                        <span>- 25%</span>
                                    </div>

                                </div>

                            </div>

                            <!-- VAGAS -->
                            <div class="vagas-gerais-box">

                                <h6 class="info-title">
                                    Vagas Gerais
                                </h6>

                                <div class="vagas-wrapper">

                                    <div class="vaga-info">

                                        <div class="vaga-left">
                                            <span class="vaga-circle total"></span>
                                            <span>Total</span>
                                        </div>

                                        <strong>
                                            <?= array_sum(array_column($setores, 'total_vagas')) ?>
                                        </strong>

                                    </div>

                                    <div class="vaga-info">

                                        <div class="vaga-left">
                                            <span class="vaga-circle"></span>
                                            <span>Livre</span>
                                        </div>

                                        <strong>
                                            <?= array_sum(array_column($setores, 'vagas_livres')) ?>
                                        </strong>

                                    </div>

                                    <div class="vaga-info">

                                        <div class="vaga-left">
                                            <span class="vaga-circle"></span>
                                            <span>Ocupada</span>
                                        </div>

                                        <strong>
                                            <?= array_sum(array_column($setores, 'vagas_ocupadas')) ?>
                                        </strong>

                                    </div>

                                    <div class="vaga-info">

                                        <div class="vaga-left">
                                            <span class="vaga-circle"></span>
                                            <span>Livre PCD</span>
                                        </div>

                                        <strong>
                                            <?= array_sum(array_column($setores, 'vagas_pcd')) ?>
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/home_adm.js"></script>
<script>

    const entradasDia = <?= json_encode($grafDiaEnt) ?>;
    const saidasDia = <?= json_encode($grafDiaSai) ?>;

    const entradasSemana = <?= json_encode(array_values($grafSemanaEnt)) ?>;
    const saidasSemana = <?= json_encode(array_values($grafSemanaSai)) ?>;

    const entradasMes = <?= json_encode(array_values($grafMesEnt)) ?>;
    const saidasMes = <?= json_encode(array_values($grafMesSai)) ?>;

    const labelsDia = Array.from({ length: 24 }, (_, i) => i + "h");
    const labelsSemana = ["Dia1", "Dia2", "Dia3", "Dia4", "Dia5", "Dia6", "Dia7"];
    const labelsMes = Array.from({ length: 31 }, (_, i) => (i + 1).toString());

    const ctx = document.getElementById('graficoFluxo');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsDia,
            datasets: [
                {
                    label: 'Entradas',
                    data: entradasDia,
                    borderColor: '#00a884',
                    backgroundColor: 'rgba(0,168,132,0.12)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                },
                {
                    label: 'Saídas',
                    data: saidasDia,
                    borderColor: '#ff5b5b',
                    backgroundColor: 'rgba(255,91,91,0.10)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    /* BOTÕES */
    btnDia.onclick = () => {
        setActive(btnDia);

        chart.data.labels = labelsDia;
        chart.data.datasets[0].data = entradasDia;
        chart.data.datasets[1].data = saidasDia;
        chart.update();
    };

    btnSemana.onclick = () => {
        setActive(btnSemana);

        chart.data.labels = labelsSemana;
        chart.data.datasets[0].data = entradasSemana;
        chart.data.datasets[1].data = saidasSemana;
        chart.update();
    };

    btnMes.onclick = () => {
        setActive(btnMes);

        chart.data.labels = labelsMes;
        chart.data.datasets[0].data = entradasMes;
        chart.data.datasets[1].data = saidasMes;
        chart.update();
    };

    function setActive(btn) {
        document.querySelectorAll(".btn-group button").forEach(b => {
            b.classList.remove("btn-dark");
            b.classList.add("btn-outline-dark");
        });

        btn.classList.add("btn-dark");
        btn.classList.remove("btn-outline-dark");
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<?php
include '../src/includes/footer.php';
?>