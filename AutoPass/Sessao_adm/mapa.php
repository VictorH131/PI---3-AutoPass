<?php
include '../src/includes/dbconnect.php';

echo '<title>Mapa de Vagas</title>';

/* =========================================
BUSCAR VAGAS
========================================= */

$vagas = $conn->query("
    SELECT
        v.*,
        s.nome AS setor
    FROM vagas v
    INNER JOIN setores s
        ON s.id_setor = v.id_setor
    ORDER BY s.nome, v.codigo
")->fetchAll();



/* =========================================
BUSCAR SETORES
========================================= */

$listaSetores = $conn->query("
    SELECT *
    FROM setores
    ORDER BY nome
")->fetchAll();

/* =========================================
AGRUPAR
========================================= */

$setores = [];

foreach ($listaSetores as $setor) {

    $setores[$setor['nome']] = [];

}

foreach ($vagas as $vaga) {

    $setores[$vaga['setor']][] = $vaga;

}

?>
<style>
    body {
        background: #f4f7fb;
        font-family: Arial;
    }

    .mapa-page {
        padding: 30px;
    }

    .topo-mapa {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .topo-mapa h2 {
        margin: 0;
        font-weight: 900;
    }

    .topo-mapa small {
        color: #6b7280;
    }

    .acoes {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-mapa {
        border: none;
        height: 50px;
        padding: 0 22px;
        border-radius: 14px;
        color: #fff;
        font-weight: 800;
        transition: .2s;
    }

    .btn-editar {
        background: #64748b;
    }

    .btn-editar.ativo {
        background: #1463ff;
    }

    .btn-verde {
        background: #22c55e;
    }

    .btn-roxo {
        background: #7c3aed;
    }

    .setor-box {
        background: #fff;
        border-radius: 28px;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
    }

    .setor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .setor-header h4 {
        margin: 0;
        font-weight: 900;
    }

    .setor-header small {
        color: #6b7280;
    }

    .btn-delete-setor {

        width: 44px;
        height: 44px;

        border: none;

        border-radius: 14px;

        background: #fee2e2;

        color: #ef4444;

        font-size: 18px;

    }

    .grid-estacionamento {

        position: relative;

        min-height: 220px;

        width: 100%;

        overflow: auto;

        border-radius: 22px;

        border: 2px solid #e5e7eb;

        background: #fff;

        background-image:

            linear-gradient(to right,
                rgba(0, 0, 0, .06) 1px,
                transparent 1px),

            linear-gradient(to bottom,
                rgba(0, 0, 0, .06) 1px,
                transparent 1px);

        background-size: 120px 160px;

    }

    .editando .grid-estacionamento {
        border: 2px solid #ef4444;
    }

    .vaga-card {

        width: 100px;
        height: 140px;

        position: absolute;

        border-radius: 18px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        user-select: none;

        transition: .15s;

        box-shadow: 0 5px 15px rgba(0, 0, 0, .08);

    }

    .vaga-card * {
        pointer-events: none;
    }

    .vaga-card:hover {
        transform: scale(1.03);
    }

    .vaga-card.livre {
        background: #dcfce7;
        border: 2px solid #22c55e;
    }

    .vaga-card.ocupada {
        background: #fee2e2;
        border: 2px solid #ef4444;
    }

    .vaga-card.pcd {
        background: #dbeafe;
        border: 2px solid #2563eb;
    }

    .vaga-numero {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 10px;
    }

    .vaga-icon {
        font-size: 34px;
    }

    .editando .vaga-card {
        cursor: grab;
    }

    .popup-overlay {

        position: fixed;

        inset: 0;

        background: rgba(0, 0, 0, .5);

        display: none;

        align-items: center;

        justify-content: center;

        z-index: 9999;

        padding: 20px;

    }

    .popup-overlay.ativo {
        display: flex;
    }

    .popup-box {

        width: 100%;
        max-width: 460px;

        background: #fff;

        border-radius: 24px;

        overflow: hidden;

    }

    .popup-header {

        padding: 20px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        border-bottom: 1px solid #e5e7eb;

    }

    .popup-header h5 {
        margin: 0;
        font-weight: 900;
    }

    .popup-header button {

        border: none;
        background: none;
        font-size: 20px;

    }

    .popup-content {
        padding: 20px;
    }

    .input-popup,
    .select-popup {

        width: 100%;

        height: 52px;

        border: 1px solid #d1d5db;

        border-radius: 14px;

        padding: 0 15px;

        margin-bottom: 15px;

        outline: none;

        background: #fff;

    }

    .btn-popup {

        width: 100%;

        height: 52px;

        border: none;

        border-radius: 14px;

        background: #1463ff;

        color: #fff;

        font-weight: 800;

    }

    .check-popup {
        margin-bottom: 15px;
    }

    .info-popup {

        background: #f3f4f6;

        border-radius: 14px;

        padding: 14px;

        margin-bottom: 15px;

        font-size: 14px;

    }

    #Permite-popup {
        position: static !important;
        transform: none !important;
        overflow: visible !important;
    }
</style>

<body>

    <div class="container-fluid mapa-page">

        <div class="topo-mapa">

            <div>

                <h2>
                    Mapa de Vagas
                </h2>

                <small>
                    Gestão visual inteligente
                </small>

            </div>

            <div class="acoes">

                <button id="btnEditar" class="btn-mapa btn-editar">
                    <i class="bi bi-pencil-fill"></i>
                    Editar
                </button>

                <button id="btnNovoSetor" class="btn-mapa btn-roxo">
                    <i class="bi bi-building-add"></i>
                    Novo setor
                </button>

                <button id="btnCriarVaga" class="btn-mapa btn-verde">
                    <i class="bi bi-plus-circle-fill"></i>
                    Criar vagas
                </button>

            </div>

        </div>

        <div id="mapaContainer">

            <?php foreach ($setores as $nome => $lista) { ?>

                <div class="setor-box" data-setor="<?= $nome ?>">

                    <div class="setor-header">

                        <div>

                            <h4>
                                Setor <?= $nome ?>
                            </h4>

                            <small class="contador-vagas">
                                <?= count($lista) ?> vagas
                            </small>

                        </div>

                        <button class="btn-delete-setor" data-id="<?=
                            array_values(
                                array_filter(
                                    $listaSetores,
                                    fn($s) => $s['nome'] == $nome
                                )
                            )[0]['id_setor']
                            ?>" data-setor="<?= $nome ?>">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                    </div>

                    <div class="grid-estacionamento">

                        <?php foreach ($lista as $index => $vaga) {

                            $x = $vaga['x'] ?? 10;
                            $y = $vaga['y'] ?? 10;

                            ?>

                            <div class="vaga-card

                            <?= $vaga['status'] == 'ocupada'
                                ? 'ocupada'
                                : 'livre'
                                ?>

                            <?= $vaga['pcd']
                                ? 'pcd'
                                : ''
                                ?>" data-id="<?= $vaga['id_vaga'] ?>" data-codigo="<?= $vaga['codigo'] ?>"
                                data-nome="<?= $vaga['nome'] ?>" data-status="<?= $vaga['status'] ?>"
                                data-pcd="<?= $vaga['pcd'] ?>" style="
                                left:<?= $x ?>px;
                                top:<?= $y ?>px;
                            ">

                                <div class="vaga-numero">
                                    <?= $vaga['codigo'] ?>
                                </div>

                                <div class="vaga-icon">

                                    <?php if ($vaga['pcd']) { ?>

                                        <i class="bi bi-universal-access"></i>

                                    <?php } else { ?>

                                        <i class="bi bi-p-circle-fill"></i>

                                    <?php } ?>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <div class="popup-overlay" id="popupOverlay">

        <div class="popup-box">

            <div class="popup-header">

                <h5 id="popupTitulo">
                    Popup
                </h5>

                <button id="fecharPopup">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

            <div class="popup-content" id="popupConteudo"></div>

        </div>

    </div>

    <script>

        let editando = false;

        document
            .getElementById("btnEditar")
            .onclick = () => {

                editando = !editando;

                document.body.classList.toggle(
                    "editando"
                );

                document
                    .getElementById("btnEditar")
                    .classList.toggle(
                        "ativo"
                    );

            };

        /* =========================================
        POPUP
        ========================================= */

        function abrirPopup(
            titulo,
            html
        ) {

            document
                .getElementById("popupTitulo")
                .innerHTML = titulo;

            document
                .getElementById("popupConteudo")
                .innerHTML = html;

            document
                .getElementById("popupOverlay")
                .classList.add("ativo");

        }

        function fecharPopup() {

            document
                .getElementById("popupOverlay")
                .classList.remove("ativo");

        }

        document
            .getElementById("fecharPopup")
            .onclick = fecharPopup;

        document
            .getElementById("popupOverlay")
            .addEventListener(
                "click",
                e => {

                    if (
                        e.target.id ===
                        "popupOverlay"
                    ) {

                        fecharPopup();

                    }

                }
            );

        /* =========================================
        GERAR SETOR
        ========================================= */

        function gerarNomeSetor() {

            const usados = [];

            document
                .querySelectorAll(".setor-box")
                .forEach(setor => {

                    usados.push(
                        setor.dataset.setor
                            .toUpperCase()
                    );

                });

            const letras =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            for (let i = 0; i < letras.length; i++) {

                if (
                    !usados.includes(letras[i])
                ) {

                    return letras[i];

                }

            }

            return "X";

        }

        /* =========================================
        NOVO SETOR
        ========================================= */

        document
            .getElementById("btnNovoSetor")
            .onclick = () => {

                const nome =
                    gerarNomeSetor();

                fetch("mapa/salvar_setor.php", {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:
                        "nome=" + encodeURIComponent(nome)

                });

                const html = `

        <div
            class="setor-box"
            data-setor="${nome}"
        >

            <div class="setor-header">

                <div>

                    <h4>
                        Setor ${nome}
                    </h4>

                    <small class="contador-vagas">
                        0 vagas
                    </small>

                </div>

                <button 
                    class="btn-delete-setor" 
                    data-id="<?= $lista[0]['id_setor'] ?? 0 ?>"
                    data-setor="<?= $nome ?>"
                >
                    <i class="bi bi-trash-fill"></i>
                </button>

            </div>

            <div class="grid-estacionamento"></div>

        </div>

        `;

                document
                    .getElementById("mapaContainer")
                    .insertAdjacentHTML(
                        "beforeend",
                        html
                    );

            };

        /* =========================================
        CRIAR VAGA
        ========================================= */

        document
            .getElementById("btnCriarVaga")
            .onclick = () => {

                abrirPopup(
                    "Criar vagas",
                    `

        <select
            id="setorVaga"
            class="select-popup"
        >

            <option value="">
                Selecione o setor
            </option>

            <?php foreach ($listaSetores as $setor) { ?>

    <div 
        class="setor-box"
        data-setor="<?= $setor['nome'] ?>"
    >

        <div class="setor-header">

            <div>

                <h4>
                    Setor <?= $setor['nome'] ?>
                </h4>

                <small class="contador-vagas">
                    <?= count($setores[$setor['nome']]) ?> vagas
                </small>

            </div>

            <button 
                class="btn-delete-setor"
                data-id="<?= $setor['id_setor'] ?>"
                data-setor="<?= $setor['nome'] ?>"
            >
                <i class="bi bi-trash-fill"></i>
            </button>

        </div>

        <div class="grid-estacionamento">

            <?php foreach ($setores[$setor['nome']] as $vaga) {

                $x = $vaga['x'] ?? 10;
                $y = $vaga['y'] ?? 10;

                ?>

                <div class="vaga-card

                <?= $vaga['status'] == 'ocupada'
                    ? 'ocupada'
                    : 'livre'
                    ?>

                <?= $vaga['pcd']
                    ? 'pcd'
                    : ''
                    ?>"

                    data-id="<?= $vaga['id_vaga'] ?>"
                    data-codigo="<?= $vaga['codigo'] ?>"
                    data-nome="<?= $vaga['nome'] ?>"
                    data-status="<?= $vaga['status'] ?>"
                    data-pcd="<?= $vaga['pcd'] ?>"

                    style="
                        left:<?= $x ?>px;
                        top:<?= $y ?>px;
                    "
                >

                    <div class="vaga-numero">
                        <?= $vaga['codigo'] ?>
                    </div>

                    <div class="vaga-icon">

                        <?php if ($vaga['pcd']) { ?>

                            <i class="bi bi-universal-access"></i>

                        <?php } else { ?>

                            <i class="bi bi-p-circle-fill"></i>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

<?php } ?>

        </select>

        <input
            type="number"
            id="quantidadeVaga"
            class="input-popup"
            placeholder="Quantidade"
            value="1"
        >

        <input
            type="text"
            id="nomeVaga"
            class="input-popup"
            placeholder="Nome da vaga"
        >

        <div class="check-popup">

            <input
                type="checkbox"
                id="vagaPcd"
            >

            <label for="vagaPcd">
                Vaga PCD
            </label>

        </div>

        <button
            class="btn-popup"
            onclick="criarVagas()"
        >
            Criar vagas
        </button>

        `
                );

            };

        function criarVagas() {

            const setor =
                document
                    .getElementById("setorVaga")
                    .value;

            const quantidade =
                parseInt(
                    document
                        .getElementById("quantidadeVaga")
                        .value
                );

            const nome =
                document
                    .getElementById("nomeVaga")
                    .value;

            const pcd =
                document
                    .getElementById("vagaPcd")
                    .checked;

            if (!setor) return;

            const area = document.querySelector(
                '.setor-box[data-setor="' + setor + '"] .grid-estacionamento'
            );

            if (!area) return;

            const vagasExistentes =
                area.querySelectorAll(
                    ".vaga-card"
                ).length;

            for (let i = 0; i < quantidade; i++) {

                const index =
                    vagasExistentes + i;

                const coluna =
                    index % 8;

                const linha =
                    Math.floor(index / 8);

                const x =
                    (coluna * 120) + 10;

                const y =
                    (linha * 160) + 10;

                const codigo =
                    setor +
                    String(index + 1)
                        .padStart(2, '0');

                const vaga =
                    document.createElement("div");

                vaga.className =
                    `vaga-card livre ${pcd ? 'pcd' : ''}`;

                vaga.dataset.codigo =
                    codigo;

                vaga.dataset.nome =
                    nome;

                vaga.dataset.status =
                    "livre";

                vaga.dataset.pcd =
                    pcd ? 1 : 0;

                vaga.style.left =
                    x + "px";

                vaga.style.top =
                    y + "px";

                vaga.innerHTML = `

            <div class="vaga-numero">
                ${codigo}
            </div>

            <div class="vaga-icon">

                ${pcd
                        ?
                        '<i class="bi bi-universal-access"></i>'
                        :
                        '<i class="bi bi-p-circle-fill"></i>'
                    }

            </div>

            `;

                area.appendChild(vaga);

                fetch("mapa/criar_vaga.php", {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:

                        "setor=" + encodeURIComponent(setor) +

                        "&codigo=" + encodeURIComponent(codigo) +

                        "&nome=" + encodeURIComponent(nome) +

                        "&status=livre" +

                        "&pcd=" + (pcd ? 1 : 0) +

                        "&x=" + x +

                        "&y=" + y

                })
                    .then(r => r.text())
                    .then(id => {

                        vaga.dataset.id = id;

                    });

            }

            atualizarContador(area);

            fecharPopup();

        }

        /* =========================================
        CONTADOR
        ========================================= */

        function atualizarContador(area) {

            const total =
                area.querySelectorAll(
                    ".vaga-card"
                ).length;

            area
                .closest(".setor-box")
                .querySelector(".contador-vagas")
                .innerHTML =
                total + " vagas";

        }

        /* =========================================
        EDITAR VAGA
        ========================================= */

        document.addEventListener(
            "dblclick",
            e => {

                const vaga =
                    e.target.closest(".vaga-card");

                if (!vaga) return;

                window.vagaEditando = vaga;

                abrirPopup(
                    "Editar vaga",
                    `

            <div class="info-popup">
                Código: <strong>${vaga.dataset.codigo}</strong>
            </div>

            <input
                type="text"
                id="editarNome"
                class="input-popup"
                value="${vaga.dataset.nome || ''}"
            >

            <input
                type="text"
                id="editarCodigo"
                class="input-popup"
                value="${vaga.dataset.codigo}"
            >

            <select
                id="editarStatus"
                class="select-popup"
            >

                <option
                    value="livre"
                    ${vaga.dataset.status == 'livre'
                        ? 'selected'
                        : ''
                    }
                >
                    Livre
                </option>

                <option
                    value="ocupada"
                    ${vaga.dataset.status == 'ocupada'
                        ? 'selected'
                        : ''
                    }
                >
                    Ocupada
                </option>

            </select>

            <div class="check-popup">

                <input
                    type="checkbox"
                    id="editarPcd"
                    ${vaga.dataset.pcd == 1
                        ? 'checked'
                        : ''
                    }
                >

                <label for="editarPcd">
                    Vaga PCD
                </label>

            </div>

            <button
                class="btn-popup"
                onclick="salvarEdicaoVaga()"
            >
                Salvar alterações
            </button>

            `
                );

            }
        );

        function salvarEdicaoVaga() {

            const vaga =
                window.vagaEditando;

            const nome =
                document
                    .getElementById("editarNome")
                    .value;

            const codigo =
                document
                    .getElementById("editarCodigo")
                    .value;

            const status =
                document
                    .getElementById("editarStatus")
                    .value;

            const pcd =
                document
                    .getElementById("editarPcd")
                    .checked;

            vaga.dataset.nome =
                nome;

            vaga.dataset.codigo =
                codigo;

            vaga.dataset.status =
                status;

            vaga.dataset.pcd =
                pcd ? 1 : 0;

            vaga.classList.remove(
                "livre",
                "ocupada",
                "pcd"
            );

            vaga.classList.add(status);

            if (pcd) {

                vaga.classList.add("pcd");

            }

            vaga.querySelector(
                ".vaga-numero"
            ).innerHTML =
                codigo;

            vaga.querySelector(
                ".vaga-icon"
            ).innerHTML =
                pcd
                    ?
                    '<i class="bi bi-universal-access"></i>'
                    :
                    '<i class="bi bi-p-circle-fill"></i>';

            fetch("mapa/editar_vaga.php", {

                method: "POST",

                headers: {
                    "Content-Type":
                        "application/x-www-form-urlencoded"
                },

                body:

                    "id=" + vaga.dataset.id +

                    "&nome=" + encodeURIComponent(nome) +

                    "&codigo=" + encodeURIComponent(codigo) +

                    "&status=" + status +

                    "&pcd=" + (pcd ? 1 : 0)

            });

            fecharPopup();

        }

        /* =========================================
        DRAG
        ========================================= */

        document.addEventListener("mousedown", (e) => {

            const vaga = e.target.closest(".vaga-card");
            if (!vaga) return;
            if (!editando) return;

            console.log(vaga.dataset.id);

            e.preventDefault();

            const pai = vaga.parentElement;

            const rectPai = pai.getBoundingClientRect();
            const rect = vaga.getBoundingClientRect();

            const offsetX = e.clientX - rect.left;
            const offsetY = e.clientY - rect.top;

            vaga.style.zIndex = 999;

            function mover(ev) {

                let x = ev.clientX - rectPai.left - offsetX;
                let y = ev.clientY - rectPai.top - offsetY;

                x = Math.round(x / 120) * 120 + 10;
                y = Math.round(y / 160) * 160 + 10;

                if (x < 10) x = 10;
                if (y < 10) y = 10;

                vaga.style.left = x + "px";
                vaga.style.top = y + "px";

                const area = pai;
                const minHeight = y + 200;

                if (area.scrollHeight < minHeight) {
                    area.style.minHeight = minHeight + "px";
                }
            }

            function parar() {

                vaga.style.zIndex = 1;

                document.removeEventListener("mousemove", mover);
                document.removeEventListener("mouseup", parar);

                fetch("mapa/mover_vaga.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body:
                        "id=" + vaga.dataset.id +
                        "&x=" + vaga.style.left.replace("px", "") +
                        "&y=" + vaga.style.top.replace("px", "")
                })
                    .then(r => r.text())
                    .then(r => {
                        console.log("RESPOSTA PHP:", r);
                    });
            }

            document.addEventListener("mousemove", mover);
            document.addEventListener("mouseup", parar);
        });

        /* =========================================
 DELETE SETOR
 ========================================= */

        document.addEventListener(
            "click",
            e => {

                const btn =
                    e.target.closest(
                        ".btn-delete-setor"
                    );

                if (!btn) return;

                const setor =
                    btn.dataset.setor;

                const id =
                    btn.dataset.id;

                abrirPopup(
                    "Excluir setor",
                    `

            <div style="margin-bottom:20px;">

                Deseja apagar o setor
                <strong>${setor}</strong>
                e todas as vagas?

            </div>

            <button
                class="btn-popup"
                onclick="confirmarDelete('${id}','${setor}')"
                style="
                    background:#ef4444;
                "
            >
                Apagar setor
            </button>

            `
                );

            }
        );

        function confirmarDelete(
            id,
            setor
        ) {

            fetch(
                "mapa/deletar_setor.php",
                {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:
                        "id=" + encodeURIComponent(id)

                }
            )
                .then(r => r.text())
                .then(() => {

                    const el =
                        document.querySelector(
                            '.setor-box[data-setor="' + setor + '"]'
                        );

                    if (el) {

                        el.remove();

                    }

                    fecharPopup();

                });

        }

    </script>