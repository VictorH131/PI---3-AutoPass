<?php

$paginaAtiva = 'vagas';

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

echo '<title>Mapa de Vagas</title>';

/* =========================
BUSCAR VAGAS
========================= */

$vagas = $conn->query("
    SELECT v.*, s.nome AS setor
    FROM vagas v
    INNER JOIN setores s ON s.id_setor = v.id_setor
    ORDER BY s.nome, v.id_vaga
")->fetchAll();

/* =========================
BUSCAR SETORES (AJUSTADO)
========================= */

$setores = $conn->query("
    SELECT id_setor, nome
    FROM setores
    WHERE ativo = 1
    ORDER BY nome
")->fetchAll();

/* =========================
CARDS
========================= */

$total = count($vagas);
$livres = 0;
$ocupadas = 0;
$pcd = 0;

foreach ($vagas as $v) {
    if ($v['status'] === 'livre') $livres++;
    if ($v['status'] === 'ocupada') $ocupadas++;
    if ($v['pcd']) $pcd++;
}

$ocupacao = $total > 0 ? round(($ocupadas / $total) * 100) : 0;

?>

<!-- BOOTSTRAP ICONS (garante ícones funcionando) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid mapa-page">

    <div class="header-mapa">
        <h2>Gestão de Vagas</h2>
        <small>Gestão visual inteligente</small>
    </div>

    <!-- CARDS -->
    <div class="cards-grid">

        <div class="card-info azul">
            <div class="card-top">
                <span>OCUPAÇÃO</span>
                <div class="icon-box azul">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
            </div>
            <h1><?= $ocupacao ?>%</h1>
        </div>

        <div class="card-info verde">
            <div class="card-top">
                <span>LIVRES</span>
                <div class="icon-box verde">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <h1><?= $livres ?></h1>
        </div>

        <div class="card-info vermelho">
            <div class="card-top">
                <span>OCUPADAS</span>
                <div class="icon-box vermelho">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
            <h1><?= $ocupadas ?></h1>
        </div>

        <div class="card-info azul">
            <div class="card-top">
                <span>PCD</span>
                <div class="icon-box azul">
                    <i class="bi bi-universal-access-circle"></i>
                </div>
            </div>
            <h1><?= $pcd ?></h1>
        </div>

    </div>

    <!-- FILTRO -->
    <div class="setores-tabs">

        <button class="tab active" data-setor="todos">Todos</button>

        <?php foreach ($setores as $s) { ?>
            <button class="tab" data-setor="<?= $s['nome'] ?>">
                Setor <?= $s['nome'] ?>
            </button>
        <?php } ?>

    </div>

    <!-- MAPA -->
    
    <div id="Permite-popup"> <?php include 'mapa.php'; ?>  </div>
</div>

<style>


.mapa-page{padding:30px;}

.header-mapa h2{
    font-weight:900;
    color:#1463ff;
}

/* CARDS */
.cards-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin:20px 0;
}

.card-info{
    background:#fff;
    border-radius:18px;
    padding:22px;
    box-shadow:0 6px 18px rgba(0,0,0,.06);
    transition:.2s;
}

.card-info:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.card-info.azul{border-left:5px solid #1463ff;}
.card-info.verde{border-left:5px solid #22c55e;}
.card-info.vermelho{border-left:5px solid #ef4444;}

.card-info span{
    font-size:12px;
    font-weight:800;
    color:#6b7280;
}

.card-info h1{
    font-size:44px;
    font-weight:900;
    margin:8px 0 0 0;
}

.card-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

/* ÍCONE LIMPO */
.icon-box{
    width:42px;
    height:42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f3f4f6;
    font-size:20px;
}

.icon-box.azul i{color:#1463ff;}
.icon-box.verde i{color:#22c55e;}
.icon-box.vermelho i{color:#ef4444;}

/* TABS */
.setores-tabs{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.tab{
    padding:10px 16px;
    border:none;
    border-radius:12px;
    background:#eef2f7;
    font-weight:700;
    cursor:pointer;
}

.tab:hover{background:#dbeafe;}

.tab.active{
    background:#1463ff;
    color:#fff;
}

/* MAPA */
.grid-estacionamento{
    position:relative;
    min-height:400px;
}

.vaga-card{
    width:100px;
    height:140px;
    position:absolute;
    border-radius:14px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.vaga-card.livre{background:#dcfce7;border:2px solid #22c55e;}
.vaga-card.ocupada{background:#fee2e2;border:2px solid #ef4444;}
.vaga-card.pcd{background:#dbeafe;border:2px solid #2563eb;}

.vaga-numero{
    font-weight:900;
    font-size:20px;
}


</style>

<script>

/* FILTRO SETORES */
document.addEventListener("click", e => {

    const tab = e.target.closest(".tab");
    if (!tab) return;

    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
    tab.classList.add("active");

    const setor = tab.dataset.setor;

    document.querySelectorAll(".setor-box").forEach(box => {

        const match =
            setor === "todos" ||
            box.dataset.setor === setor;

        box.style.display = match ? "block" : "none";
    });

});

</script>

<?php include '../src/includes/footer.php'; ?>