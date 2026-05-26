document.addEventListener("DOMContentLoaded", () => {

    document.body.insertAdjacentHTML(
        "beforeend",

`
<div class="popup-overlay" id="popupSenha">

    <div class="popup-box">

        <div class="popup-header">

            <h5>Recuperar senha</h5>

            <button
                type="button"
                id="fecharPopup">

                ×

            </button>

        </div>

        <form
            action="src/includes/esqueci_senha.php"
            method="POST">

            <div class="popup-body">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Digite seu email"
                    required
                >

            </div>

            <div class="popup-footer">

                <button
                    type="button"
                    id="cancelarPopup">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn-enviar">

                    Enviar

                </button>

            </div>

        </form>

    </div>

</div>
`
    );


    /* ========= ELEMENTOS ========= */

    const popup =
    document.getElementById(
        "popupSenha"
    );

    const abrir =
    document.getElementById(
        "abrirPopup"
    );

    const fechar =
    document.getElementById(
        "fecharPopup"
    );

    const cancelar =
    document.getElementById(
        "cancelarPopup"
    );


    /* ========= ABRIR ========= */

    abrir.addEventListener(
        "click",
        (e) => {

            e.preventDefault();

            popup.classList.add(
                "active"
            );

        }
    );


    /* ========= FECHAR ========= */

    function fecharPopup() {

        popup.classList.remove(
            "active"
        );

    }

    fechar.addEventListener(
        "click",
        fecharPopup
    );

    cancelar.addEventListener(
        "click",
        fecharPopup
    );


    /* ========= FECHAR AO CLICAR FORA ========= */

    popup.addEventListener(
        "click",
        (e) => {

            if (e.target === popup) {

                fecharPopup();

            }

        }
    );


    /* ========= ESC ========= */

    document.addEventListener(
        "keydown",
        (e) => {

            if (
                e.key === "Escape" &&
                popup.classList.contains("active")
            ) {

                fecharPopup();

            }

        }
    );

});