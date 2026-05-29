<?php

$horaAtual = true;

include '../src/includes/session.php';
include '../src/includes/header_adm.php';
include '../src/includes/dbconnect.php';

$idUsuario = $_SESSION['usuario']['id_usuario'];

$sql = "
SELECT *
FROM usuarios
WHERE id_usuario = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "

SELECT 
usuarios.*,

enderecos.cep,
enderecos.rua,
enderecos.numero,
enderecos.complemento,
enderecos.bairro,
enderecos.cidade,
enderecos.estado

FROM usuarios

LEFT JOIN enderecos
ON usuarios.id_usuario = enderecos.id_usuario

WHERE usuarios.id_usuario = ?

";

$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<title>Meu Perfil - AutoPass</title>";

$meses = [
    '01' => 'Jan',
    '02' => 'Fev',
    '03' => 'Mar',
    '04' => 'Abr',
    '05' => 'Mai',
    '06' => 'Jun',
    '07' => 'Jul',
    '08' => 'Ago',
    '09' => 'Set',
    '10' => 'Out',
    '11' => 'Nov',
    '12' => 'Dez'
];

$mes = date('m', strtotime($usuario['created_at']));
$ano = date('Y', strtotime($usuario['created_at']));

?>

<style>

body{
    background:#f4f7fb;
}

.profile-card{
    background:white;
    border-radius:24px;
    padding:30px;
    box-shadow:0 2px 15px rgba(0,0,0,.05);
}

.section-card{
    background:white;
    border-radius:22px;
    padding:25px;
    box-shadow:0 2px 15px rgba(0,0,0,.05);
    height:100%;
}

.form-control,
.form-select{
    border-radius:12px;
    height:48px;
}

.btn-save{
    background:#0d6efd;
    border:none;
    padding:14px 35px;
    border-radius:12px;
    font-weight:600;
}

.avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #f1f5f9;
}

.label-title{
    font-size:13px;
    color:#94a3b8;
    margin-bottom:5px;
}

.info-box{
    background:#f8fafc;
    border-radius:16px;
    padding:18px;
}

.toggle-senha{
    cursor:pointer;
}

</style>

<div class="content p-4">

<form
action="../src/includes/processa_editar_perfil.php"
method="POST"
enctype="multipart/form-data">

    <!-- TOPO PERFIL -->

    <div class="profile-card mb-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

            <div class="d-flex align-items-center gap-4">

                <div class="position-relative">

                    <img
                    src="../<?= !empty($usuario['foto'])
                    ? $usuario['foto']
                    : 'src/img/user.png' ?>"
                    class="avatar">

                    <input
                    type="file"
                    name="foto"
                    id="fotoInput"
                    hidden>

                    <label
                    for="fotoInput"
                    class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle">

                        <i class="bi bi-camera"></i>

                    </label>

                </div>

                <div>

                    <h3 class="fw-bold mb-1">

                        <?= $usuario['nome'] ?>
                        <?= $usuario['sobrenome'] ?>

                    </h3>

                    <div class="text-secondary mb-2">

                        <?= $usuario['email'] ?>

                    </div>

                    <div class="d-flex gap-2 flex-wrap">

                        <?php if($usuario['tipo'] == 3): ?>

                            <span class="badge text-bg-danger">

                                Administrador

                            </span>

                        <?php elseif($usuario['tipo'] == 2): ?>

                            <span class="badge text-bg-primary">

                                Funcionário

                            </span>

                        <?php else: ?>

                            <span class="badge text-bg-secondary">

                                Cliente

                            </span>

                        <?php endif; ?>

                        <?php if($usuario['status'] == 'ativo'): ?>

                            <span class="badge text-bg-success">

                                Conta Ativa

                            </span>

                        <?php else: ?>

                            <span class="badge text-bg-danger">

                                Conta Bloqueada

                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <div class="info-box text-end">

                <small class="text-secondary">

                    MEMBRO DESDE

                </small>

                <div class="fw-bold fs-5">

                    <?= $meses[$mes] ?>
                    <?= $ano ?>

                </div>

                <small class="text-secondary">

                    <?= date(
                        'd/m/Y H:i',
                        strtotime($usuario['created_at'])
                    ) ?>

                </small>

            </div>

        </div>

    </div>

    <!-- CONTEÚDO -->

    <div class="row g-4">

        <!-- DADOS -->

        <div class="col-lg-6">

            <div class="section-card">

                <h5 class="fw-bold mb-4">

                    <i class="bi bi-person me-2 text-primary"></i>

                    Informações Pessoais

                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="label-title">

                            Nome

                        </label>

                        <input
                        type="text"
                        name="nome"
                        class="form-control"
                        value="<?= $usuario['nome'] ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="label-title">

                            Sobrenome

                        </label>

                        <input
                        type="text"
                        name="sobrenome"
                        class="form-control"
                        value="<?= $usuario['sobrenome'] ?>">

                    </div>

                    <div class="col-12">

                        <label class="label-title">

                            Email

                        </label>

                        <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= $usuario['email'] ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="label-title">

                            CPF

                        </label>

                        <input
                        type="text"
                        name="cpf"
                        class="form-control"
                        value="<?= $usuario['cpf'] ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="label-title">

                            Telefone

                        </label>

                        <input
                        type="text"
                        name="telefone"
                        class="form-control"
                        value="<?= $usuario['telefone'] ?>">

                    </div>

                </div>

            </div>

        </div>

        <!-- SEGURANÇA -->

        <div class="col-lg-6">

            <div class="section-card">

                <h5 class="fw-bold mb-4">

                    <i class="bi bi-shield-lock me-2 text-primary"></i>

                    Segurança

                </h5>

                <div class="row g-3">

                    <div class="col-12">

                        <label class="label-title">

                            Nova Senha

                        </label>

                        <div class="input-group">

                            <input
                            type="password"
                            name="nova_senha"
                            class="form-control">

                            <button
                            type="button"
                            class="input-group-text toggle-senha"
                            data-target="nova_senha">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                    <div class="col-12">

                        <label class="label-title">

                            Confirmar Nova Senha

                        </label>

                        <div class="input-group">

                            <input
                            type="password"
                            name="confirmar_senha"
                            class="form-control">

                            <button
                            type="button"
                            class="input-group-text toggle-senha"
                            data-target="confirmar_senha">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ENDEREÇO -->

        <div class="col-12">

            <div class="section-card">

                <h5 class="fw-bold mb-4">

                    <i class="bi bi-geo-alt me-2 text-primary"></i>

                    Endereço

                </h5>

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="label-title">

                            CEP

                        </label>

                        <input
                        type="text"
                        name="cep"
                        class="form-control"
                        value="<?= $usuario['cep'] ?? '' ?>">

                    </div>

                    <div class="col-md-5">

                        <label class="label-title">

                            Rua

                        </label>

                        <input
                        type="text"
                        name="rua"
                        class="form-control"
                        value="<?= $usuario['rua'] ?? '' ?>">

                    </div>

                    <div class="col-md-2">

                        <label class="label-title">

                            Número

                        </label>

                        <input
                        type="text"
                        name="numero"
                        class="form-control"
                        value="<?= $usuario['numero'] ?? '' ?>">

                    </div>

                    <div class="col-md-2">

                        <label class="label-title">

                            Complemento

                        </label>

                        <input
                        type="text"
                        name="complemento"
                        class="form-control"
                        value="<?= $usuario['complemento'] ?? '' ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="label-title">

                            Cidade

                        </label>

                        <input
                        type="text"
                        name="cidade"
                        class="form-control"
                        value="<?= $usuario['cidade'] ?? '' ?>">

                    </div>

                    <div class="col-md-6">

                        <label class="label-title">

                            Estado

                        </label>

                        <input
                        type="text"
                        name="estado"
                        class="form-control"
                        value="<?= $usuario['estado'] ?? '' ?>">

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- BOTÕES -->

    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">

        <button class="btn-save text-white">

            <i class="bi bi-check-circle me-2"></i>

            Salvar Alterações

        </button>

</form>

<form
action="../src/includes/processa_excluir_conta.php"
method="POST">

    <button
    class="btn btn-outline-danger">

        <i class="bi bi-trash me-2"></i>

        Excluir Conta

    </button>

</form>

</div>

</div>

<script>

/* =========================================
FOTO
========================================= */

const fotoInput =
document.getElementById('fotoInput');

fotoInput?.addEventListener(
    'change',
    function(e){

        const file =
        e.target.files[0];

        if(!file) return;

        const reader =
        new FileReader();

        reader.onload = function(){

            document
            .querySelector('.avatar')
            .src = reader.result;

        }

        reader.readAsDataURL(file);

    }
);

/* =========================================
MOSTRAR SENHA
========================================= */

document
.querySelectorAll('.toggle-senha')
.forEach(btn => {

    btn.addEventListener(
        'click',
        () => {

            const target =
            btn.getAttribute(
                'data-target'
            );

            const input =
            document.querySelector(
                `input[name="${target}"]`
            );

            const icon =
            btn.querySelector('i');

            if(input.type === 'password'){

                input.type = 'text';

                icon.classList.remove(
                    'bi-eye'
                );

                icon.classList.add(
                    'bi-eye-slash'
                );

            }else{

                input.type = 'password';

                icon.classList.remove(
                    'bi-eye-slash'
                );

                icon.classList.add(
                    'bi-eye'
                );

            }

        }
    );

});

/* =========================================
VALIDAR SENHAS
========================================= */

const form =
document.querySelector('form');

const senha =
document.querySelector(
    'input[name="nova_senha"]'
);

const confirmar =
document.querySelector(
    'input[name="confirmar_senha"]'
);

function validarSenhas(){

    if(!senha || !confirmar){
        return;
    }

    senha.classList.remove(
        'is-valid',
        'is-invalid'
    );

    confirmar.classList.remove(
        'is-valid',
        'is-invalid'
    );

    if(
        senha.value.length === 0 &&
        confirmar.value.length === 0
    ){
        return;
    }

    if(
        senha.value === confirmar.value
    ){

        senha.classList.add(
            'is-valid'
        );

        confirmar.classList.add(
            'is-valid'
        );

    }else{

        senha.classList.add(
            'is-invalid'
        );

        confirmar.classList.add(
            'is-invalid'
        );

    }

}

senha?.addEventListener(
    'input',
    validarSenhas
);

confirmar?.addEventListener(
    'input',
    validarSenhas
);

form?.addEventListener(
    'submit',
    function(e){

        if(
            senha.value !== confirmar.value
        ){

            e.preventDefault();

            senha.classList.add(
                'is-invalid'
            );

            confirmar.classList.add(
                'is-invalid'
            );

        }

    }
);

/* =========================================
MÁSCARA CPF
========================================= */

const cpf =
document.querySelector(
    'input[name="cpf"]'
);

cpf?.addEventListener(
    'input',
    (e) => {

        let valor =
        e.target.value
        .replace(/\D/g,'');

        valor = valor
        .replace(/(\d{3})(\d)/,'$1.$2')
        .replace(/(\d{3})(\d)/,'$1.$2')
        .replace(/(\d{3})(\d{1,2})$/,'$1-$2');

        e.target.value = valor;

    }
);

/* =========================================
MÁSCARA CEP
========================================= */

const cepInput =
document.querySelector(
    'input[name="cep"]'
);

cepInput?.addEventListener(
    'input',
    (e) => {

        let valor =
        e.target.value
        .replace(/\D/g,'');

        valor = valor
        .replace(/(\d{5})(\d)/,'$1-$2');

        e.target.value = valor;

    }
);

/* =========================================
VIACEP API
========================================= */

cepInput?.addEventListener(
    'blur',
    async function(){

        const cep =
        this.value.replace(
            /\D/g,
            ''
        );

        if(cep.length !== 8){
            return;
        }

        try{

            const response =
            await fetch(
                `https://viacep.com.br/ws/${cep}/json/`
            );

            const data =
            await response.json();

            if(data.erro){
                return;
            }

            document.querySelector(
                'input[name="rua"]'
            ).value =
            data.logradouro || '';

            document.querySelector(
                'input[name="cidade"]'
            ).value =
            data.localidade || '';

            document.querySelector(
                'input[name="estado"]'
            ).value =
            data.uf || '';

        }catch(error){

            console.log(
                'Erro ViaCEP:',
                error
            );

        }

    }
);

</script>

<?php include '../src/includes/footer.php'; ?>