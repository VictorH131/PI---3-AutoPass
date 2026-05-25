// popup.js

const popupData = {
    "Sobre Nós": {

        icon: "bi-buildings",

        html: `

<p>
O AutoPass surgiu como um projeto acadêmico focado na modernização
e automação inteligente de estacionamentos através da utilização
de tecnologias atuais como Internet das Coisas (IoT), sensores,
monitoramento em tempo real e sistemas automatizados.
</p>

<p>
A proposta busca reduzir filas, diminuir falhas operacionais,
melhorar a experiência dos usuários e tornar processos tradicionais
mais eficientes através da integração entre software e hardware.
Além disso, o sistema permite maior controle operacional,
maior segurança e geração de informações úteis para tomadas
de decisão.
</p>

<div class="team-box">

<h4>Participantes</h4>

<div class="member">
<i class="bi bi-person-circle"></i>
Victor Hernandez Soares de Almeida
</div>

<div class="member">
<i class="bi bi-person-circle"></i>
Gabriel Henrique Delalana Borges
</div>

<div class="member">
<i class="bi bi-person-circle"></i>
Gustavo de Oliveira
</div>

</div>

<img class="about-image"
src="img/autopass.png">

`

    },
    "Política de Privacidade": {

        icon: "bi-shield-lock",

        html: `

<h5>Política de Privacidade</h5>

<p>

O AutoPass respeita a privacidade dos usuários e busca garantir
proteção adequada para todas as informações armazenadas em sua
plataforma. Durante a utilização do sistema podem ser coletados
dados pessoais como nome, CPF, telefone, endereço eletrônico,
placas de veículos, horários de entrada e saída, registros de
utilização e informações relacionadas ao estacionamento.

Os dados coletados possuem finalidade operacional, administrativa,
estatística e de segurança. As informações poderão ser utilizadas
para gerenciamento de acesso, processamento de pagamentos,
controle interno, suporte técnico e melhorias do sistema.

O armazenamento das informações poderá ocorrer em servidores
próprios ou serviços externos que atendam critérios mínimos
de segurança e disponibilidade.

Os dados poderão ser compartilhados apenas quando necessário,
incluindo exigências legais, ordens judiciais ou integrações
autorizadas entre serviços.

O usuário possui direito de solicitar alterações, atualizações
ou remoção de determinadas informações quando permitido por
legislação aplicável.

O AutoPass poderá atualizar esta política periodicamente
para acompanhar melhorias da plataforma e mudanças legais.

</p>

`

    },


    "Termos de Uso": {

        icon: "bi-file-earmark-text",

        html: `

<h5>Termos de Uso</h5>

<p>

Ao acessar ou utilizar a plataforma AutoPass o usuário declara
estar ciente e concordar com os termos apresentados neste documento.

O sistema destina-se ao gerenciamento e automação de estacionamentos,
permitindo controle de acessos, gerenciamento de veículos,
registros operacionais e utilização de recursos integrados.

O usuário compromete-se a fornecer informações verdadeiras,
atualizadas e completas durante o cadastro e utilização da plataforma.

Não é permitido utilizar o sistema para atividades ilegais,
tentativas de invasão, envio de informações falsas,
modificação indevida de registros ou qualquer ação capaz
de comprometer a integridade da plataforma.

A plataforma poderá realizar atualizações, correções,
manutenções ou alterações em funcionalidades sem aviso prévio.

O AutoPass não se responsabiliza por falhas causadas por
problemas externos, indisponibilidade de serviços de terceiros,
falhas de conexão ou uso inadequado da plataforma.

O descumprimento destes termos poderá resultar em suspensão,
restrição ou encerramento de acesso.

A continuidade do uso da plataforma após alterações futuras
representa aceitação automática dos novos termos estabelecidos.

</p>

`

    }

};


// criar popup

const popup = document.createElement("div");

popup.innerHTML = `

<div class="popup-overlay">

<div class="popup-box">

<button class="popup-close">

<i class="bi bi-x-lg"></i>

</button>

<div class="popup-icon">

<i></i>

</div>

<h3></h3>

<div class="popup-content"></div>

</div>

</div>

`;

document.body.appendChild(popup);


const overlay = document.querySelector(".popup-overlay");

const title = document.querySelector(".popup-box h3");

const content = document.querySelector(".popup-content");

const icon = document.querySelector(".popup-icon i");



document
    .querySelectorAll(".footer-links p, .policy-link")
    .forEach(item => {

        item.addEventListener("click", () => {

            const texto = item.innerText.trim();

            const data = popupData[texto];

            if (!data) return;

            title.innerText = texto;

            content.innerHTML = data.html;

            icon.className = `bi ${data.icon}`;

            overlay.classList.add("show");

            document.body.style.overflow = "hidden";

        });

    });



function fechar() {

    overlay.classList.remove("show");

    document.body.style.overflow = "auto";

}

document
    .querySelector(".popup-close")
    .addEventListener("click", fechar);


overlay.addEventListener("click", (e) => {

    if (e.target === overlay) {

        fechar();

    }

});




// CSS automático

const style = document.createElement("style");

style.innerHTML = `

.popup-overlay{

position:fixed;
top:0;
left:0;

width:100%;
height:100%;

display:flex;
justify-content:center;
align-items:center;

background:rgba(0,0,0,.65);

backdrop-filter:blur(10px);

opacity:0;
visibility:hidden;

transition:.4s;

z-index:99999;

}

.popup-overlay.show{

opacity:1;
visibility:visible;

}

.popup-box{

width:800px;
max-width:95%;

max-height:85vh;

overflow-y:auto;

padding:40px;

background:#1f1f22;

color:white;

border-radius:30px;

position:relative;

transform:translateY(50px);

transition:.4s;

border:1px solid rgba(255,255,255,.1);

box-shadow:
0 25px 60px rgba(0,0,0,.4);

}


.popup-box{

overflow-y:auto;
overflow-x:hidden;
word-wrap:break-word;
}





.popup-overlay.show .popup-box{

transform:translateY(0);

}

.popup-box::before{

content:"";

position:absolute;

top:-150px;
right:-150px;

width:300px;
height:300px;

background:
linear-gradient(
135deg,
#0057d9,
#347cff
);

opacity:.18;

border-radius:50%;

}

.popup-close{

position:absolute;

right:25px;
top:25px;

border:none;
background:none;

color:white;

font-size:20px;

cursor:pointer;

}

.popup-icon{

width:70px;
height:70px;

display:flex;
align-items:center;
justify-content:center;

border-radius:20px;

font-size:30px;

margin-bottom:25px;

background:
linear-gradient(
135deg,
#0057d9,
#347cff
);

}

.popup-box h3{

font-size:32px;
margin-bottom:30px;
font-weight:700;

}

.popup-content{

color:#d1d1d1;

line-height:1.8;

}

.popup-content h5{

margin-top:25px;

color:#4d95ff;

}

.popup-content ul{

padding-left:20px;

}

.team-box{

margin-top:30px;

padding:20px;

background:rgba(255,255,255,.05);

border-radius:20px;

}

.member{

margin-top:10px;

display:flex;
align-items:center;
gap:10px;

}

.member i{

color:#4d95ff;

}

.about-image{

width:100%;

margin-top:30px;

border-radius:20px;

}

.popup-box::-webkit-scrollbar{

width:8px;

}

.popup-box::-webkit-scrollbar-thumb{

background:#0057d9;
border-radius:20px;

}

`;

document.head.appendChild(style);