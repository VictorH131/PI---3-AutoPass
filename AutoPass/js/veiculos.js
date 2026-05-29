/* =========================================
CRIA DRAWER AUTOMATICAMENTE
========================================= */

let veiculoAtual = null;

document.body.insertAdjacentHTML("beforeend", `

<div class="drawer" id="drawer">

    <div class="drawer-header">

        <div>
            <h5 class="fw-bold text-white m-0">
                Informações do Veículo
            </h5>
            <small class="text-light">
                Informações detalhadas
            </small>
        </div>

        <button id="fecharDrawer" class="btn-close btn-close-white"></button>

    </div>

    <div class="drawer-body">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <h4 class="fw-bold mb-1" id="modelo"></h4>
                <small class="text-secondary" id="cor"></small>
            </div>

            <span id="status" class="badge rounded-pill"></span>

        </div>

        <hr>

        <h6 class="titulo">ESPECIFICAÇÕES</h6>

        <div class="row g-2">

            <div class="col-6">
                <div class="box-info">
                    <small>ANO</small>
                    <div id="ano"></div>
                </div>
            </div>

            <div class="col-6">
                <div class="box-info">
                    <small>PLACA</small>
                    <div id="placa"></div>
                </div>
            </div>

            <div class="col-12">
                <div class="box-info">
                    <small>RFID</small>
                    <div id="rfid"></div>
                </div>
            </div>

        </div>

        <hr>

        <h6 class="titulo">PROPRIETÁRIO</h6>

        <div class="proprietario-card">

            <img id="fotoUsuario" class="avatar">

            <div>
                <div class="fw-bold" id="proprietario"></div>
                <small id="tipoUsuario"></small>
            </div>

        </div>

        <div class="dados">

            <div class="linha">
                <span>Email:</span>
                <span id="emailUsuario"></span>
            </div>

            <div class="linha">
                <span>Telefone:</span>
                <span id="telefoneUsuario"></span>
            </div>

        </div>

        <hr>

        <h6 class="titulo">HISTÓRICO DE ACESSO</h6>

        <div id="historicoContainer"></div>

        <div class="d-grid gap-2 mt-4">

            <div class="d-flex gap-2">

                <button class="btn btn-primary flex-fill" id="abrirDrawerEditar">
                    <i class="bi bi-pencil"></i>
                    Editar
                </button>

                <button class="btn bloquear">
                    <i class="bi bi-ban"></i>
                    Bloquear acesso
                </button>

            </div>

        </div>

    </div>

</div>

`);

/* =========================================
REFERÊNCIAS
========================================= */

const drawer = document.getElementById("drawer");
const fecharDrawer = document.getElementById("fecharDrawer");

const modelo = document.getElementById("modelo");
const cor = document.getElementById("cor");
const placa = document.getElementById("placa");
const ano = document.getElementById("ano");
const rfid = document.getElementById("rfid");

const proprietario = document.getElementById("proprietario");
const emailUsuario = document.getElementById("emailUsuario");
const telefoneUsuario = document.getElementById("telefoneUsuario");
const fotoUsuario = document.getElementById("fotoUsuario");
const tipoUsuario = document.getElementById("tipoUsuario");

const status = document.getElementById("status");
const historicoContainer = document.getElementById("historicoContainer");

/* =========================================
ABRIR DETALHES
========================================= */

document.querySelectorAll(".btnDetalhes").forEach(btn => {

    btn.onclick = () => {

        drawer.classList.add("ativo");

        veiculoAtual = {
            modelo: btn.dataset.modelo,
            cor: btn.dataset.cor,
            placa: btn.dataset.placa,
            ano: btn.dataset.ano,
            marca: btn.dataset.marca,
            rfid: btn.dataset.rfid,
            proprietario: btn.dataset.proprietario,
            email: btn.dataset.email,
            telefone: btn.dataset.telefone,
            foto: btn.dataset.foto,
            tipo: btn.dataset.tipo,
            status: btn.dataset.status
        };

        modelo.textContent = btn.dataset.modelo || "-";
        cor.textContent = btn.dataset.cor || "-";
        placa.textContent = btn.dataset.placa || "-";
        ano.textContent = btn.dataset.ano || "-";
        rfid.textContent = btn.dataset.rfid || "Não vinculado";

        proprietario.textContent = btn.dataset.proprietario || "-";
        emailUsuario.textContent = btn.dataset.email || "-";
        telefoneUsuario.textContent = btn.dataset.telefone || "-";
        fotoUsuario.src = btn.dataset.foto || "../img/user.png";

        let tipo = "Cliente";
        if (btn.dataset.tipo == "2") tipo = "Funcionário";
        if (btn.dataset.tipo == "3") tipo = "Administrador";
        tipoUsuario.textContent = tipo;

        /* STATUS */
        atualizarBadgeStatus();

        /* BOTÃO BLOQUEAR */
        atualizarBotaoStatus();

        /* HISTÓRICO */
        historicoContainer.innerHTML = "";

        const historico = btn.dataset.historico;

        if (!historico || historico === "null" || historico.trim() === "") {

            historicoContainer.innerHTML = `
                <div class="text-center p-3 text-secondary">
                    Nenhum acesso registrado
                </div>
            `;

        } else {

            historico.split("###").reverse().forEach(item => {

                const dados = item.split("|");
                if (dados.length < 3) return;

                historicoContainer.innerHTML += `
                    <div class="historico-item">

                        <div class="icone ${dados[0] === "saida" ? "saida" : "entrada"}">
                            <i class="bi bi-${dados[0] === "entrada" ? "arrow-right" : "arrow-left"}"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                ${dados[0] === "entrada" ? "Entrada" : "Saída"}
                            </div>

                            <small class="text-secondary">${dados[1]}</small>
                            <br>
                            <small>${dados[2]}</small>
                        </div>

                    </div>
                `;

            });

        }

    };

});

/* =========================================
FECHAR DRAWER
========================================= */

fecharDrawer.onclick = () => {
    drawer.classList.remove("ativo");
};

document.addEventListener("click", function (e) {

    const btn = e.target.closest(".bloquear");
    if (!btn) return;

    if (!veiculoAtual) return;

    const novoStatus =
        veiculoAtual.status === "ativo"
            ? "bloqueado"
            : "ativo";

    const formData = new FormData();
    formData.append("placa", veiculoAtual.placa);
    formData.append("status", novoStatus);

    fetch("../src/includes/status_veiculo_adm.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) {
            alert("Erro ao atualizar");
            return;
        }

        veiculoAtual.status = novoStatus;

        atualizarBadgeStatus();
        atualizarBotaoStatus();

    });

});

/* =========================================
EDITAR DRAWER
========================================= */

document.addEventListener("click", (e) => {

    const btn = e.target.closest("#abrirDrawerEditar");
    if (!btn) return;

    drawer.classList.remove("ativo");

    const evadmOverlay = document.getElementById("evadmOverlay");
    const evadmDrawer = document.getElementById("evadmDrawer");

    if (evadmOverlay && evadmDrawer) {
        evadmOverlay.classList.add("ativo");
        evadmDrawer.classList.add("ativo");
    }

});

/* =========================================
STATUS BOTÃO (TOGGLE)
========================================= */

document.addEventListener("click", function (e) {

    const btn = e.target.closest(".bloquear");
    if (!btn) return;

    if (!veiculoAtual) return;

    const novoStatus =
        veiculoAtual.status === "ativo"
            ? "bloqueado"
            : "ativo";

    const formData = new FormData();
    formData.append("placa", veiculoAtual.placa);
    formData.append("status", novoStatus);

    fetch("../src/includes/status_veiculo_adm.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (!data.success) {
            alert("Erro ao atualizar");
            return;
        }

        veiculoAtual.status = novoStatus;

        atualizarBadgeStatus();
        atualizarBotaoStatus();

    });

});

/* =========================================
FUNÇÕES AUXILIARES
========================================= */

function atualizarBadgeStatus() {

    if (veiculoAtual.status === "bloqueado") {

        status.textContent = "Bloqueado";
        status.className = "badge bg-danger rounded-pill";

    } else {

        status.textContent = "Ativo";
        status.className = "badge bg-primary rounded-pill";

    }

}

function atualizarBotaoStatus() {

    const btnBloquear = document.querySelector(".bloquear");

    if (!btnBloquear) return;

    if (veiculoAtual.status === "bloqueado") {

        btnBloquear.innerHTML = `
            <i class="bi bi-unlock"></i>
            Desbloquear acesso
        `;

        btnBloquear.classList.remove("btn-danger");
        btnBloquear.classList.add("btn-success");

    } else {

        btnBloquear.innerHTML = `
            <i class="bi bi-ban"></i>
            Bloquear acesso
        `;

        btnBloquear.classList.remove("btn-success");
        btnBloquear.classList.add("btn-danger");

    }

}