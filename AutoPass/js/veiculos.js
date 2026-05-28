/* CRIA POPUP AUTOMATICAMENTE */

document.body.insertAdjacentHTML("beforeend", `

<div class="drawer" id="drawer">

<div class="drawer-header">

<div>

<h5 class="fw-bold text-white m-0">
Editar Veículo
</h5>

<small class="text-light">
Informações detalhadas
</small>

</div>

<button
id="fecharDrawer"
class="btn-close btn-close-white">
</button>

</div>

<div class="drawer-body">

<div class="d-flex justify-content-between align-items-start">

<div>

<h4
class="fw-bold mb-1"
id="modelo">
</h4>

<small
class="text-secondary"
id="cor">
</small>

</div>

<span
id="status"
class="badge rounded-pill">
</span>

</div>

<hr>

<h6 class="titulo">
ESPECIFICAÇÕES
</h6>

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

<h6 class="titulo">
PROPRIETÁRIO
</h6>

<div class="proprietario-card">

<img
id="fotoUsuario"
class="avatar">

<div>

<div
class="fw-bold"
id="proprietario">
</div>

<small
id="tipoUsuario">
</small>

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

<h6 class="titulo">
HISTÓRICO DE ACESSO
</h6>

<div id="historicoContainer"></div>

<div class="d-grid gap-2 mt-4">

<div class="d-flex gap-2">

<button class="btn btn-primary flex-fill">

<i class="bi bi-pencil"></i>
Editar

</button>

<button class="btn btn-light border flex-fill">

<i class="bi bi-file-earmark-text"></i>
Relatório

</button>

</div>

<button class="btn bloquear">

<i class="bi bi-ban"></i>
Bloquear acesso

</button>

</div>

</div>

</div>

`);





/* REFERÊNCIAS */

const drawer=document.getElementById("drawer");
const fecharDrawer=document.getElementById("fecharDrawer");

const modelo=document.getElementById("modelo");
const cor=document.getElementById("cor");
const placa=document.getElementById("placa");
const ano=document.getElementById("ano");
const rfid=document.getElementById("rfid");

const proprietario=document.getElementById("proprietario");
const emailUsuario=document.getElementById("emailUsuario");
const telefoneUsuario=document.getElementById("telefoneUsuario");
const fotoUsuario=document.getElementById("fotoUsuario");
const tipoUsuario=document.getElementById("tipoUsuario");

const status=document.getElementById("status");
const historicoContainer=document.getElementById("historicoContainer");


/* BOTÕES */

document
.querySelectorAll(".btnDetalhes")
.forEach(btn=>{

btn.onclick=()=>{

drawer.classList.add("ativo");


/* VEÍCULO */

modelo.textContent=
btn.dataset.modelo || "-";

cor.textContent=
btn.dataset.cor || "-";

placa.textContent=
btn.dataset.placa || "-";

ano.textContent=
btn.dataset.ano || "-";

rfid.textContent=
btn.dataset.rfid || "Não vinculado";


/* PROPRIETÁRIO */

proprietario.textContent=
btn.dataset.proprietario || "-";

emailUsuario.textContent=
btn.dataset.email || "-";

telefoneUsuario.textContent=
btn.dataset.telefone || "-";

fotoUsuario.src=
btn.dataset.foto || "../img/user.png";


/* TIPO USUÁRIO */

let tipo="Cliente";

if(btn.dataset.tipo=="2"){
tipo="Funcionário";
}

if(btn.dataset.tipo=="3"){
tipo="Administrador";
}

tipoUsuario.textContent=tipo;


/* STATUS */

if(btn.dataset.status=="ativo"){

status.textContent=
"Ativo";

status.className=
"badge bg-primary rounded-pill";

}else{

status.textContent=
"Bloqueado";

status.className=
"badge bg-danger rounded-pill";

}


/* HISTÓRICO */

historicoContainer.innerHTML="";

const historico=
btn.dataset.historico;


if(
!historico ||
historico==="null" ||
historico.trim()===""
){

historicoContainer.innerHTML=`

<div class="text-center p-3 text-secondary">

Nenhum acesso registrado

</div>

`;

return;

}


let acessos=
historico
.split("###")
.reverse();


acessos.forEach(item=>{

let dados=item.split("|");

if(dados.length<3){
return;
}

let tipo=dados[0];
let local=dados[1];
let hora=dados[2];


historicoContainer.innerHTML += `

<div class="historico-item">

<div
class="icone ${tipo=="saida" ? "saida":"entrada"}">

<i class="bi bi-${tipo=="entrada"
? "arrow-right"
: "arrow-left"}"></i>

</div>

<div>

<div class="fw-semibold">

${tipo=="entrada"
? "Entrada"
: "Saída"}

</div>

<small class="text-secondary">

${local}

</small>

<br>

<small>

${hora}

</small>

</div>

</div>

`;

});

};

});


/* FECHAR DRAWER */

fecharDrawer.onclick=()=>{

drawer.classList.remove("ativo");

};


window.onclick=(e)=>{

if(e.target===drawer){

drawer.classList.remove("ativo");

}

};



// FILTROS

let filtroCor = 'todos';
let filtroStatus = 'todos';


function aplicarFiltro() {

document
.querySelectorAll('tbody tr')
.forEach(tr => {

const cor =
tr.getAttribute('data-cor');

const status =
tr.getAttribute('data-status');

let mostrar = true;


/* STATUS */

if (
filtroStatus !== 'todos'
&&
status !== filtroStatus
) {

mostrar = false;

}


/* COR */

if (
filtroCor !== 'todos'
&&
cor !== filtroCor
) {

mostrar = false;

}


tr.style.display =
mostrar
?
''
:
'none';

});

}



/* STATUS */

function setStatus(valor,label){

filtroStatus = valor;

document
.getElementById('btnStatus')
.innerText =
'Status: ' + label;

aplicarFiltro();

}



/* COR */

function setCor(valor,label){

filtroCor = valor;

document
.getElementById('btnCor')
.innerText =
'Cor: ' + label;

aplicarFiltro();

}



/* LIMPAR */

function limparFiltros(){

filtroCor = 'todos';
filtroStatus = 'todos';

document
.getElementById('btnCor')
.innerText =
'Cor: Todas';

document
.getElementById('btnStatus')
.innerText =
'Status: Todos';

aplicarFiltro();

}




