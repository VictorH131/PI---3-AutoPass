const form = document.querySelector('form');
const senha = document.querySelector('input[name="senha"]');
const confirmar = document.querySelector('input[name="confirmar"]');

function validarSenhas() {

    if (!senha || !confirmar) return;

    if (senha.value === confirmar.value && senha.value.length > 0) {

        senha.classList.add('valido');
        confirmar.classList.add('valido');

        senha.classList.remove('invalido');
        confirmar.classList.remove('invalido');

    } else {

        if (senha.value.length > 0) senha.classList.add('invalido');
        if (confirmar.value.length > 0) confirmar.classList.add('invalido');

        senha.classList.remove('valido');
        confirmar.classList.remove('valido');
    }
}

/* vibração visual no erro */
function vibrarCampos() {

    senha.classList.add("vibrar");
    confirmar.classList.add("vibrar");

    setTimeout(() => {
        senha.classList.remove("vibrar");
        confirmar.classList.remove("vibrar");
    }, 300);
}

/* bloqueio de envio */
form.addEventListener('submit', function (e) {

    if (senha.value !== confirmar.value) {
        e.preventDefault();
        vibrarCampos();
    }

});

/* validação em tempo real */
senha.addEventListener('input', validarSenhas);
confirmar.addEventListener('input', validarSenhas);


const cpf = document.querySelector('input[name="cpf"]');

function aplicarMascaraCPF(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    return v;
}

cpf.addEventListener("input", (e) => {
    e.target.value = aplicarMascaraCPF(e.target.value);
});


document.querySelectorAll(".toggle-senha").forEach(btn => {

    btn.addEventListener("click", () => {

        const target = btn.getAttribute("data-target");
        const input = document.querySelector(`input[name="${target}"]`);
        const icon = btn.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }

    });

});


const profileBtn = document.getElementById("profile-btn");
const fileInput = document.getElementById("file-input");
const preview = document.getElementById("preview");
const defaultIcon = document.getElementById("default-icon");

/* abre seletor ao clicar */
profileBtn.addEventListener("click", () => {
    fileInput.click();
});

/* preview da imagem */
fileInput.addEventListener("change", (event) => {

    const file = event.target.files[0];

    if (file) {

        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";

        defaultIcon.style.display = "none"; // esconde ícone

    }

});