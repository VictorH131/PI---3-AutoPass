document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".btn-carros").forEach(btn => {

        btn.addEventListener("click", function () {

            const id = this.dataset.id;

            const overlay = document.createElement("div");
            overlay.className = "position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center";
            overlay.style.background = "rgba(0,0,0,0.6)";
            overlay.style.zIndex = "9999";

            // fechar ao clicar fora
            overlay.addEventListener("click", function (e) {
                if (e.target === overlay) overlay.remove();
            });

            const box = document.createElement("div");
            box.className = "bg-white rounded-4 shadow p-4 position-relative";
            box.style.width = "700px";
            box.style.maxHeight = "80vh";
            box.style.overflowY = "auto";

            // BOTÃO FECHAR (fixo, nunca é apagado)
            const close = document.createElement("button");
            close.innerHTML = "×";
            close.className = "btn btn-danger btn-sm rounded-circle position-absolute";
            close.style.top = "10px";
            close.style.right = "10px";
            close.style.width = "32px";
            close.style.height = "32px";
            close.style.display = "flex";
            close.style.alignItems = "center";
            close.style.justifyContent = "center";

            close.onclick = () => overlay.remove();

            // conteúdo (wrapper separado)
            const content = document.createElement("div");
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <div class="mt-2 text-muted">Carregando...</div>
                </div>
            `;

            box.appendChild(close);
            box.appendChild(content);
            overlay.appendChild(box);
            document.body.appendChild(overlay);

            fetch(`../src/includes/popup_carros.php?id=${id}`)
                .then(r => r.text())
                .then((html) => {

                    content.innerHTML = html;

                });
        });

    });

});


// Filtros

let filtroTipo = 'todos';
let filtroStatus = 'todos';

function aplicarFiltro() {

    document.querySelectorAll('tbody tr').forEach(tr => {

        const tipo = tr.getAttribute('data-tipo');
        const status = tr.getAttribute('data-status');

        let mostrar = true;

        if (filtroTipo !== 'todos' && tipo !== filtroTipo) {
            mostrar = false;
        }

        if (filtroStatus !== 'todos' && status !== filtroStatus) {
            mostrar = false;
        }

        tr.style.display = mostrar ? '' : 'none';
    });
}

function setTipo(valor, label) {
    filtroTipo = valor;
    document.getElementById('btnTipo').innerText = 'Tipo: ' + label;
    aplicarFiltro();
}

function setStatus(valor, label) {
    filtroStatus = valor;
    document.getElementById('btnStatus').innerText = 'Status: ' + label;
    aplicarFiltro();
}

function limparFiltros() {

    filtroTipo = 'todos';
    filtroStatus = 'todos';

    document.getElementById('btnTipo').innerText = 'Tipo: Todos';
    document.getElementById('btnStatus').innerText = 'Status: Todos';

    aplicarFiltro();
}




// Modal de confirmação para desativar/reativar usuário

document.addEventListener("click", function (e) {

    const btn = e.target.closest(".btn-toggle-status");
    if (!btn) return;

    const id = btn.dataset.id;
    const statusAtual = btn.dataset.status; // ativo | desativado

    const vaiDesativar = statusAtual === "ativo";

    const overlay = document.createElement("div");
    overlay.className = "position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center";
    overlay.style.background = "rgba(0,0,0,0.6)";
    overlay.style.zIndex = "9999";

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) overlay.remove();
    });

    const box = document.createElement("div");
    box.className = "bg-white rounded-5 shadow p-4 text-center position-relative";
    box.style.width = "420px";

    const iconHtml = vaiDesativar
        ? `<div style="width:70px;height:70px;margin:auto;background:#ffe5e5;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-x text-danger fs-2"></i>
           </div>`
        : `<div style="width:70px;height:70px;margin:auto;background:#e6f4ea;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-arrow-clockwise text-success fs-2"></i>
           </div>`;

    const titulo = vaiDesativar ? "Desativar usuário" : "Reativar usuário";
    const texto = vaiDesativar
        ? "O usuário será bloqueado e não poderá acessar o sistema."
        : "O usuário voltará a ter acesso ao sistema.";

    const botao = vaiDesativar ? "Desativar" : "Ativar";
    const classeBtn = vaiDesativar ? "btn-danger" : "btn-success";

    box.innerHTML = `
        <button class="btn btn-light border rounded-circle position-absolute"
            style="top:10px;right:10px;width:32px;height:32px;">
            ×
        </button>

        ${iconHtml}

        <h5 class="fw-bold mt-3">${titulo}</h5>

        <p class="text-muted small mb-4">${texto}</p>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary w-50 btn-cancelar">
                Cancelar
            </button>

            <button class="btn ${classeBtn} w-50 btn-confirmar">
                ${botao}
            </button>
        </div>
    `;

    overlay.appendChild(box);
    document.body.appendChild(overlay);

    // fechar
    box.querySelector("button").onclick = () => overlay.remove();
    box.querySelector(".btn-cancelar").onclick = () => overlay.remove();

    // confirmar ação
    box.querySelector(".btn-confirmar").addEventListener("click", () => {

        box.innerHTML = `
            <div class="py-4">
                <div class="spinner-border"></div>
                <div class="mt-2 text-muted">Processando...</div>
            </div>
        `;

        fetch(`../src/includes/desativar_usuario.php?id=${id}&status=${statusAtual}`)
            .then(r => r.text())
            .then(() => {

                const novoStatus = statusAtual === "ativo" ? "desativado" : "ativo";

                btn.dataset.status = novoStatus;

                btn.innerHTML = novoStatus === "ativo"
                    ? `<i class="bi bi-arrow-clockwise text-success"></i>`
                    : `<i class="bi bi-person-x text-danger"></i>`;

                overlay.remove();

                location.reload();
            });

    });

});








document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btnNovoFuncionario");
    if (!btn) return;

    btn.addEventListener("click", function () {

        const overlay = document.createElement("div");
        overlay.className = "position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center";
        overlay.style.background = "rgba(0,0,0,0.6)";
        overlay.style.zIndex = "9999";

        overlay.addEventListener("click", function (e) {
            if (e.target === overlay) overlay.remove();
        });

        const box = document.createElement("div");
        box.className = "bg-white rounded-4 shadow p-4 position-relative";
        box.style.width = "420px";

        const close = document.createElement("button");
        close.innerHTML = `<i class="bi bi-x-lg"></i>`;
        close.className = "btn btn-danger rounded-circle position-absolute d-flex align-items-center justify-content-center";
        close.style.top = "12px";
        close.style.right = "12px";
        close.style.width = "36px";
        close.style.height = "36px";
        close.style.padding = "0";
        close.onclick = () => overlay.remove();

        box.innerHTML = `
            <h5 class="fw-bold mb-3">Adicionar Funcionário</h5>

            <p class="text-muted small mb-3">
                Informe o e-mail do cliente que será promovido.
            </p>

            <input type="email" id="emailCliente"
                class="form-control mb-2"
                placeholder="ex: cliente@email.com">

            <div id="msgBox" class="small text-center mb-2"></div>

            <button class="btn btn-primary w-100" id="btnSalvarFuncionario">
                Confirmar
            </button>
        `;

        box.appendChild(close);
        overlay.appendChild(box);
        document.body.appendChild(overlay);

        const msgBox = box.querySelector("#msgBox");
        const btnSalvar = box.querySelector("#btnSalvarFuncionario");

        btnSalvar.addEventListener("click", function () {

            const email = box.querySelector("#emailCliente").value.trim();

            if (!email) {
                msgBox.innerHTML = "Digite um e-mail";
                msgBox.className = "text-danger small text-center mb-2";
                return;
            }

            btnSalvar.disabled = true;
            btnSalvar.innerHTML = "Processando...";

            fetch("../src/includes/novo_funcionario.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "email=" + encodeURIComponent(email)
            })
            .then(r => r.text())
            .then(res => {

                msgBox.innerHTML = res;

                const ok = res.toLowerCase().includes("sucesso");

                msgBox.className = ok
                    ? "text-success small text-center mb-2"
                    : "text-danger small text-center mb-2";

                setTimeout(() => {
                    overlay.remove();
                    if (ok) location.reload();
                }, 1200);

            })
            .catch(() => {
                msgBox.innerHTML = "Erro no servidor";
                msgBox.className = "text-danger small text-center mb-2";
                btnSalvar.disabled = false;
                btnSalvar.innerHTML = "Confirmar";
            });

        });

    });

});