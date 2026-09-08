import Swal from 'sweetalert2';

$(document).ready(function () {
    $("#cpf").on("input", function () {
        this.value = this.value.replace(/\D/g, "").slice(0, 11);
    });

    $("#formulario-login").submit(function (evento) {
        evento.preventDefault();

        const formulario = $(this);
        const botaoEntrar = formulario.find("button[type='submit']");

        botaoEntrar.prop("disabled", true).text("ENTRANDO...");

        $.ajax({
            url: formulario.attr("action"),
            type: "POST",
            data: formulario.serialize(),
            dataType: "json",
            headers: {
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            success: function (response) {
                if (response.erro === "s") {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: response.mensagem,
                        confirmButtonColor: "#005baa"
                    });

                    botaoEntrar.prop("disabled", false).text("ENTRAR");
                    return;
                }

                localStorage.setItem("token_usuario", response.token);

                Swal.fire({
                    icon: "success",
                    title: "Login realizado!",
                    text: response.mensagem,
                    confirmButtonColor: "#005baa"
                }).then(function () {
                    window.location.href = response.redirect;
                });
            },
            error: function (xhr) {
                const mensagem = xhr.responseJSON?.message || "CPF ou senha inválidos.";

                Swal.fire({
                    icon: "error",
                    title: "Não foi possível entrar",
                    text: mensagem,
                    confirmButtonColor: "#005baa"
                });

                botaoEntrar.prop("disabled", false).text("ENTRAR");
            }
        });
    });
});
