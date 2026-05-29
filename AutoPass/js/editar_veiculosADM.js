document.body.insertAdjacentHTML("beforeend", `

<div class="evadm-overlay" id="evadmOverlay">

    <div class="evadm-drawer" id="evadmDrawer">

        <div class="p-4 border-bottom">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold m-0">
                        Editar Veículo
                    </h5>

                    <small class="text-secondary">
                        Atualize as informações
                    </small>

                </div>

                <button class="btn-close" id="evadmFechar"></button>

            </div>

        </div>

        <div class="p-4">

            <!-- INPUT 1 (sem ID - mantido) -->
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo_extra">
            </div>

            <!-- INPUT 2 (sem ID - mantido) -->
            <div class="mb-3">
                <label class="form-label">Placa</label>
                <input type="text" class="form-control" id="placa_extra">
            </div>

            <!-- INPUT 3 -->
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" id="Marca" name="Marca">
            </div>

            <!-- INPUT 4 -->
            <div class="mb-3">
                <label class="form-label">Ano</label>
                <input type="text" class="form-control" id="Ano" name="Ano">
            </div>

            <button type="button" class="btn btn-primary w-100" id="salvarVeiculo">
                Salvar Alterações
            </button>
        </div>

    </div>

</div>

`);


/* =========================================
CSS
========================================= */

const style = document.createElement("style");

style.innerHTML = `
.evadm-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    opacity: 0;
    visibility: hidden;
    transition: .3s;
    z-index: 999999;
}

.evadm-overlay.ativo{
    opacity: 1;
    visibility: visible;
}

.evadm-drawer{
    position: absolute;
    top: 0;
    right: -520px;
    width: 520px;
    max-width: 100%;
    height: 100vh;
    background: #fff;
    transition: .3s ease;
    overflow-y: auto;
}

.evadm-drawer.ativo{
    right: 0;
}
`;

document.head.appendChild(style);


/* =========================================
FECHAR
========================================= */

document.addEventListener("click", function (e) {

    if (
        e.target.id === "evadmOverlay" ||
        e.target.id === "evadmFechar"
    ) {
        document.getElementById("evadmOverlay").classList.remove("ativo");
        document.getElementById("evadmDrawer").classList.remove("ativo");
    }

});


/* =========================================
ABRIR (exemplo)
========================================= */

function abrirDrawerVeiculo(dados) {

    document.getElementById("modelo_extra").value = dados.modelo || "";
    document.getElementById("placa_extra").value = dados.placa || "";
    document.getElementById("Marca").value = dados.marca || "";
    document.getElementById("Ano").value = dados.ano || "";

    document.getElementById("evadmOverlay").classList.add("ativo");
    document.getElementById("evadmDrawer").classList.add("ativo");
}






document.addEventListener("click", function (e) {

    const btn = e.target.closest("#abrirDrawerEditar");

    if (!btn) return;

    drawer.classList.remove("ativo");

    const evadmOverlay = document.getElementById("evadmOverlay");
    const evadmDrawer = document.getElementById("evadmDrawer");

    if (evadmOverlay && evadmDrawer) {

        /* 🔥 AQUI ENTRA O PREENCHIMENTO */

        document.getElementById("modelo_extra").value = veiculoAtual?.modelo || "";
        document.getElementById("placa_extra").value = veiculoAtual?.placa || "";
        document.getElementById("Marca").value = veiculoAtual?.marca || "";
        document.getElementById("Ano").value = veiculoAtual?.ano || "";

        evadmOverlay.classList.add("ativo");
        evadmDrawer.classList.add("ativo");
    }

});


/* =========================================
SALVAR
========================================= */
document.addEventListener("click", function (e) {

    const btn = e.target.closest(".bloquear");

    if (!btn) return;

    if (!veiculoAtual || !veiculoAtual.placa) return;

    const url =
        "../src/includes/status_veiculo_adm.php?" +
        "placa=" + encodeURIComponent(veiculoAtual.placa) +
        "&status=bloqueado";

    window.location.href = url;

});